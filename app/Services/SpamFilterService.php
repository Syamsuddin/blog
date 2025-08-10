<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\SpamKeyword;

class SpamFilterService
{
    /**
     * Spam detection patterns
     */
    private static array $spamPatterns = [
        // URL patterns
        'excessive_urls' => '/https?:\/\/[^\s]+.*https?:\/\/[^\s]+.*https?:\/\/[^\s]+/i',
        'suspicious_domains' => '/\b(bit\.ly|tinyurl|goo\.gl|short\.link|t\.co)\b/i',
        
        // Repetitive content
        'excessive_caps' => '/[A-Z]{10,}/',
        'repeated_chars' => '/(.)\1{5,}/',
        'excessive_exclamation' => '/!{3,}/',
        
        // Common spam phrases
        'money_making' => '/\b(make money|earn \$|fast cash|get rich|work from home)\b/i',
        'promotional' => '/\b(buy now|click here|limited time|act now|free trial)\b/i',
        'suspicious_contact' => '/\b(whatsapp|telegram|call me|contact me)\b.*\+?\d{10,}/i',
        
        // Cryptocurrency/Investment
        'crypto_spam' => '/\b(bitcoin|cryptocurrency|forex|trading|investment|profit)\b/i',
        
        // Adult content
        'adult_content' => '/\b(xxx|porn|sex|adult|escort|dating)\b/i',
        
        // Gibberish detection
        'random_text' => '/\b[bcdfghjklmnpqrstvwxyz]{6,}\b/i',
    ];

    /**
     * Check if content is spam
     */
    public static function isSpam(string $content, string $email = null, string $ip = null): array
    {
        $spamScore = 0;
        $reasons = [];
        $maxScore = 100;

        // Check against spam keywords from database
        $keywordCheck = self::checkSpamKeywords($content);
        $spamScore += $keywordCheck['score'];
        $reasons = array_merge($reasons, $keywordCheck['reasons']);

        // Pattern-based detection
        foreach (self::$spamPatterns as $type => $pattern) {
            if (preg_match($pattern, $content)) {
                $score = self::getPatternScore($type);
                $spamScore += $score;
                $reasons[] = "Pattern detected: " . str_replace('_', ' ', $type) . " (+{$score})";
            }
        }

        // Content length analysis
        $lengthCheck = self::checkContentLength($content);
        $spamScore += $lengthCheck['score'];
        if ($lengthCheck['score'] > 0) {
            $reasons[] = $lengthCheck['reason'];
        }

        // Email-based checks
        if ($email) {
            $emailCheck = self::checkEmailSpam($email);
            $spamScore += $emailCheck['score'];
            if ($emailCheck['score'] > 0) {
                $reasons[] = $emailCheck['reason'];
            }
        }

        // IP-based checks
        if ($ip) {
            $ipCheck = self::checkIpSpam($ip);
            $spamScore += $ipCheck['score'];
            if ($ipCheck['score'] > 0) {
                $reasons[] = $ipCheck['reason'];
            }
        }

        // Rate limiting check
        $rateCheck = self::checkRateLimit($ip, $email);
        $spamScore += $rateCheck['score'];
        if ($rateCheck['score'] > 0) {
            $reasons[] = $rateCheck['reason'];
        }

        $isSpam = $spamScore >= 50; // Threshold for spam detection

        return [
            'is_spam' => $isSpam,
            'score' => min($spamScore, $maxScore),
            'reasons' => $reasons,
            'action' => self::getRecommendedAction($spamScore)
        ];
    }

    /**
     * Check against spam keywords in database
     */
    private static function checkSpamKeywords(string $content): array
    {
        $score = 0;
        $reasons = [];
        
        // Get cached spam keywords
        $spamKeywords = Cache::remember('spam_keywords', 3600, function () {
            return SpamKeyword::where('is_active', true)->get();
        });

        foreach ($spamKeywords as $keyword) {
            $pattern = $keyword->is_regex ? $keyword->keyword : '/\b' . preg_quote($keyword->keyword, '/') . '\b/i';
            
            if (preg_match($pattern, $content)) {
                $score += $keyword->weight;
                $reasons[] = "Spam keyword found: '{$keyword->keyword}' (+{$keyword->weight})";
            }
        }

        return ['score' => $score, 'reasons' => $reasons];
    }

    /**
     * Get pattern score based on type
     */
    private static function getPatternScore(string $type): int
    {
        $scores = [
            'excessive_urls' => 40,
            'suspicious_domains' => 30,
            'excessive_caps' => 15,
            'repeated_chars' => 10,
            'excessive_exclamation' => 10,
            'money_making' => 35,
            'promotional' => 25,
            'suspicious_contact' => 30,
            'crypto_spam' => 20,
            'adult_content' => 50,
            'random_text' => 20,
        ];

        return $scores[$type] ?? 10;
    }

    /**
     * Check content length for spam indicators
     */
    private static function checkContentLength(string $content): array
    {
        $length = strlen($content);
        
        if ($length < 10) {
            return ['score' => 15, 'reason' => 'Content too short (+15)'];
        }
        
        if ($length > 2000) {
            return ['score' => 20, 'reason' => 'Content too long (+20)'];
        }
        
        // Check for excessive line breaks
        $lineBreaks = substr_count($content, "\n");
        if ($lineBreaks > 10) {
            return ['score' => 15, 'reason' => 'Excessive line breaks (+15)'];
        }

        return ['score' => 0, 'reason' => ''];
    }

    /**
     * Check email for spam indicators
     */
    private static function checkEmailSpam(string $email): array
    {
        $score = 0;
        $reasons = [];

        // Disposable email domains
        $disposableDomains = [
            '10minutemail.com', 'tempmail.org', 'guerrillamail.com', 
            'mailinator.com', 'yopmail.com', 'temp-mail.org'
        ];
        
        $domain = substr(strrchr($email, "@"), 1);
        
        if (in_array($domain, $disposableDomains)) {
            $score += 40;
            $reasons[] = "Disposable email domain (+40)";
        }

        // Check for suspicious email patterns
        if (preg_match('/\d{5,}/', $email)) {
            $score += 15;
            $reasons[] = "Email contains many numbers (+15)";
        }

        if (preg_match('/[a-z]{20,}/', $email)) {
            $score += 10;
            $reasons[] = "Email unusually long (+10)";
        }

        return [
            'score' => $score, 
            'reason' => implode(', ', $reasons)
        ];
    }

    /**
     * Check IP for spam indicators
     */
    private static function checkIpSpam(string $ip): array
    {
        // Check if IP is in spam cache
        $spamIps = Cache::get('spam_ips', []);
        
        if (in_array($ip, $spamIps)) {
            return ['score' => 60, 'reason' => 'IP in spam blacklist (+60)'];
        }

        return ['score' => 0, 'reason' => ''];
    }

    /**
     * Check rate limiting
     */
    private static function checkRateLimit(string $ip = null, string $email = null): array
    {
        $score = 0;
        $reasons = [];

        if ($ip) {
            $ipKey = "comment_rate_ip_{$ip}";
            $ipCount = Cache::get($ipKey, 0);
            
            if ($ipCount > 5) { // More than 5 comments per hour from same IP
                $score += 30;
                $reasons[] = "IP rate limit exceeded (+30)";
            }
        }

        if ($email) {
            $emailKey = "comment_rate_email_" . md5($email);
            $emailCount = Cache::get($emailKey, 0);
            
            if ($emailCount > 3) { // More than 3 comments per hour from same email
                $score += 25;
                $reasons[] = "Email rate limit exceeded (+25)";
            }
        }

        return [
            'score' => $score,
            'reason' => implode(', ', $reasons)
        ];
    }

    /**
     * Get recommended action based on spam score
     */
    private static function getRecommendedAction(int $score): string
    {
        if ($score >= 80) {
            return 'block'; // Completely block
        } elseif ($score >= 50) {
            return 'moderate'; // Require moderation
        } elseif ($score >= 30) {
            return 'flag'; // Flag for review
        } else {
            return 'allow'; // Allow comment
        }
    }

    /**
     * Update rate limiting counters
     */
    public static function updateRateLimit(string $ip = null, string $email = null): void
    {
        if ($ip) {
            $ipKey = "comment_rate_ip_{$ip}";
            Cache::put($ipKey, Cache::get($ipKey, 0) + 1, 3600); // 1 hour
        }

        if ($email) {
            $emailKey = "comment_rate_email_" . md5($email);
            Cache::put($emailKey, Cache::get($emailKey, 0) + 1, 3600); // 1 hour
        }
    }

    /**
     * Add IP to spam list
     */
    public static function addSpamIp(string $ip): void
    {
        $spamIps = Cache::get('spam_ips', []);
        $spamIps[] = $ip;
        Cache::put('spam_ips', array_unique($spamIps), 86400); // 24 hours
        
        Log::info("IP added to spam list: {$ip}");
    }

    /**
     * Remove IP from spam list
     */
    public static function removeSpamIp(string $ip): void
    {
        $spamIps = Cache::get('spam_ips', []);
        $spamIps = array_diff($spamIps, [$ip]);
        Cache::put('spam_ips', $spamIps, 86400);
        
        Log::info("IP removed from spam list: {$ip}");
    }

    /**
     * Clean content from potential spam elements
     */
    public static function cleanContent(string $content): string
    {
        // Remove excessive whitespace
        $content = preg_replace('/\s+/', ' ', $content);
        
        // Remove excessive punctuation
        $content = preg_replace('/[!]{3,}/', '!!', $content);
        $content = preg_replace('/[?]{3,}/', '??', $content);
        
        // Remove excessive line breaks
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        
        return trim($content);
    }
}
