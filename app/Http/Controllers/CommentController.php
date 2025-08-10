<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;
use App\Services\SpamFilterService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Rate limiting per IP
        $key = 'comment-store:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'body' => "Too many comments. Please try again in {$seconds} seconds."
            ]);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:2000'],
            'author_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'author_email' => ['nullable', 'email', 'max:255'],
        ]);

        // Pre-filter spam before creating
        $spamCheck = SpamFilterService::isSpam(
            $validated['body'],
            $validated['author_email'] ?? null,
            $request->ip()
        );

        // Block immediately if spam score is too high
        if ($spamCheck['action'] === 'block') {
            Log::warning('Spam comment blocked', [
                'ip' => $request->ip(),
                'email' => $validated['author_email'] ?? null,
                'score' => $spamCheck['score'],
                'reasons' => $spamCheck['reasons']
            ]);

            RateLimiter::hit($key, 3600); // Block for 1 hour
            
            return back()->withErrors([
                'body' => 'Your comment was rejected. Please review our community guidelines.'
            ]);
        }

        $data = [
            'body' => Purifier::clean($validated['body'], 'default'),
            'ip_address' => $request->ip(),
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        } else {
            $data['author_name'] = $validated['author_name'] ?? null;
            $data['author_email'] = $validated['author_email'] ?? null;
        }

        $comment = $post->comments()->create($data);

        RateLimiter::hit($key, 60); // Normal rate limiting

        // Determine response message based on spam action
        $message = match($spamCheck['action']) {
            'moderate' => 'Comment submitted and requires moderation due to content review.',
            'flag' => 'Comment submitted and awaiting approval.',
            default => 'Comment posted successfully!'
        };

        // Log suspicious activity
        if ($spamCheck['score'] > 30) {
            Log::info('Suspicious comment detected', [
                'comment_id' => $comment->id,
                'score' => $spamCheck['score'],
                'reasons' => $spamCheck['reasons'],
                'ip' => $request->ip()
            ]);
        }

        return back()->with('status', $message);
    }
}
