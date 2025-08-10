<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use Carbon\Carbon;

class CleanSpamComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spam:clean 
                           {--days=30 : Delete spam comments older than specified days}
                           {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old spam comments from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $cutoffDate = Carbon::now()->subDays($days);
        
        $spamCommentsQuery = Comment::where('status', 'spam')
            ->where('created_at', '<', $cutoffDate);
            
        $rejectedCommentsQuery = Comment::where('status', 'rejected')
            ->where('created_at', '<', $cutoffDate);

        $spamCount = $spamCommentsQuery->count();
        $rejectedCount = $rejectedCommentsQuery->count();
        $totalCount = $spamCount + $rejectedCount;

        if ($totalCount === 0) {
            $this->info('No old spam or rejected comments found to clean.');
            return 0;
        }

        $this->info("Found {$totalCount} old comments to clean:");
        $this->line("- {$spamCount} spam comments");
        $this->line("- {$rejectedCount} rejected comments");
        $this->line("- Older than {$days} days (before {$cutoffDate->format('Y-m-d H:i:s')})");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No comments will be deleted');
            
            // Show sample of what would be deleted
            $sampleSpam = $spamCommentsQuery->limit(5)->get();
            if ($sampleSpam->count() > 0) {
                $this->line("\nSample spam comments that would be deleted:");
                foreach ($sampleSpam as $comment) {
                    $this->line("- ID: {$comment->id}, Score: {$comment->spam_score}, Created: {$comment->created_at}");
                }
            }
            
            return 0;
        }

        if (!$this->confirm("Are you sure you want to delete {$totalCount} old comments?")) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->info('Deleting old spam and rejected comments...');

        // Delete spam comments
        $deletedSpam = $spamCommentsQuery->delete();
        
        // Delete rejected comments
        $deletedRejected = $rejectedCommentsQuery->delete();

        $totalDeleted = $deletedSpam + $deletedRejected;

        $this->info("Successfully deleted {$totalDeleted} old comments:");
        $this->line("- {$deletedSpam} spam comments");
        $this->line("- {$deletedRejected} rejected comments");

        return 0;
    }
}
