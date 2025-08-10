<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use App\Models\SpamKeyword;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UpdateSpamFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spam:update-filter 
                           {--auto-learn : Automatically learn from manually marked spam}
                           {--analyze : Analyze current spam patterns}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update spam filter based on spam patterns and feedback';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $autoLearn = $this->option('auto-learn');
        $analyze = $this->option('analyze');

        if ($analyze) {
            $this->analyzeSpamPatterns();
        }

        if ($autoLearn) {
            $this->autoLearnFromSpam();
        }

        if (!$analyze && !$autoLearn) {
            $this->info('Please specify --auto-learn or --analyze option');
            return 1;
        }

        // Clear spam keywords cache
        Cache::forget('spam_keywords');
        $this->info('Spam filter cache cleared.');

        return 0;
    }

    /**
     * Analyze current spam patterns
     */
    private function analyzeSpamPatterns()
    {
        $this->info('Analyzing spam patterns...');

        $spamComments = Comment::where('status', 'spam')
            ->where('spam_score', '>', 50)
            ->latest()
            ->limit(100)
            ->get();

        if ($spamComments->isEmpty()) {
            $this->warn('No spam comments found for analysis.');
            return;
        }

        $this->line("Analyzing {$spamComments->count()} spam comments...");

        // Analyze common words in spam
        $wordFrequency = [];
        $urlPatterns = [];
        $emailDomains = [];

        foreach ($spamComments as $comment) {
            // Word frequency analysis
            $words = preg_split('/\s+/', strtolower($comment->body));
            foreach ($words as $word) {
                $word = preg_replace('/[^\w]/', '', $word);
                if (strlen($word) > 3) {
                    $wordFrequency[$word] = ($wordFrequency[$word] ?? 0) + 1;
                }
            }

            // URL pattern analysis
            preg_match_all('/https?:\/\/([^\s\/]+)/', $comment->body, $matches);
            foreach ($matches[1] as $domain) {
                $urlPatterns[$domain] = ($urlPatterns[$domain] ?? 0) + 1;
            }

            // Email domain analysis
            if ($comment->author_email) {
                $domain = substr(strrchr($comment->author_email, "@"), 1);
                if ($domain) {
                    $emailDomains[$domain] = ($emailDomains[$domain] ?? 0) + 1;
                }
            }
        }

        // Show top spam words
        arsort($wordFrequency);
        $topWords = array_slice($wordFrequency, 0, 20, true);
        
        $this->line("\nTop spam words:");
        foreach ($topWords as $word => $count) {
            if ($count >= 3) { // Only show words that appear 3+ times
                $this->line("- {$word}: {$count} times");
            }
        }

        // Show suspicious domains
        if (!empty($urlPatterns)) {
            arsort($urlPatterns);
            $this->line("\nSuspicious domains:");
            foreach (array_slice($urlPatterns, 0, 10, true) as $domain => $count) {
                $this->line("- {$domain}: {$count} times");
            }
        }

        // Show email domains
        if (!empty($emailDomains)) {
            arsort($emailDomains);
            $this->line("\nSpam email domains:");
            foreach (array_slice($emailDomains, 0, 10, true) as $domain => $count) {
                if ($count >= 2) {
                    $this->line("- {$domain}: {$count} times");
                }
            }
        }
    }

    /**
     * Auto-learn from manually marked spam
     */
    private function autoLearnFromSpam()
    {
        $this->info('Auto-learning from spam comments...');

        // Get recently marked spam comments
        $recentSpam = Comment::where('status', 'spam')
            ->where('reviewed_at', '>', now()->subDays(7))
            ->whereNotNull('reviewed_by')
            ->get();

        if ($recentSpam->isEmpty()) {
            $this->warn('No recent manually marked spam found for learning.');
            return;
        }

        $this->line("Learning from {$recentSpam->count()} manually marked spam comments...");

        $newKeywords = [];

        foreach ($recentSpam as $comment) {
            // Extract potential spam phrases (2-4 word combinations)
            $words = preg_split('/\s+/', strtolower($comment->body));
            $words = array_map(function($word) {
                return preg_replace('/[^\w]/', '', $word);
            }, $words);

            // Generate 2-word phrases
            for ($i = 0; $i < count($words) - 1; $i++) {
                if (strlen($words[$i]) > 2 && strlen($words[$i + 1]) > 2) {
                    $phrase = $words[$i] . ' ' . $words[$i + 1];
                    $newKeywords[$phrase] = ($newKeywords[$phrase] ?? 0) + 1;
                }
            }

            // Generate 3-word phrases for high-value spam
            if ($comment->spam_score > 70) {
                for ($i = 0; $i < count($words) - 2; $i++) {
                    if (strlen($words[$i]) > 2 && strlen($words[$i + 1]) > 2 && strlen($words[$i + 2]) > 2) {
                        $phrase = $words[$i] . ' ' . $words[$i + 1] . ' ' . $words[$i + 2];
                        $newKeywords[$phrase] = ($newKeywords[$phrase] ?? 0) + 1;
                    }
                }
            }
        }

        // Filter and add new keywords
        $added = 0;
        foreach ($newKeywords as $phrase => $frequency) {
            if ($frequency >= 2 && strlen($phrase) > 5) { // Must appear 2+ times
                // Check if keyword already exists
                $exists = SpamKeyword::where('keyword', $phrase)->exists();
                
                if (!$exists) {
                    // Determine weight based on frequency and manual marking
                    $weight = min(10 + ($frequency * 5), 30);
                    
                    SpamKeyword::create([
                        'keyword' => $phrase,
                        'weight' => $weight,
                        'category' => 'auto-learned',
                        'description' => "Auto-learned from {$frequency} spam instances",
                        'is_regex' => false,
                        'is_active' => true,
                    ]);

                    $this->line("Added keyword: '{$phrase}' (weight: {$weight})");
                    $added++;
                }
            }
        }

        $this->info("Auto-learning complete. Added {$added} new spam keywords.");
    }
}
