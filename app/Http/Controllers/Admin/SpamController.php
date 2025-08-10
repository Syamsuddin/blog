<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\SpamKeyword;
use App\Services\SpamFilterService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class SpamController extends Controller
{
    /**
     * Show spam management dashboard
     */
    public function index()
    {
        $stats = [
            'spam_comments' => Comment::where('status', 'spam')->count(),
            'flagged_comments' => Comment::where('is_flagged', true)->count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
            'blocked_ips' => count(Cache::get('spam_ips', [])),
            'spam_keywords' => SpamKeyword::active()->count(),
        ];

        $recentSpam = Comment::spam()
            ->with(['post', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $flaggedComments = Comment::flagged()
            ->with(['post', 'user'])
            ->orderBy('spam_score', 'desc')
            ->limit(10)
            ->get();

        return view('admin.spam.index', compact('stats', 'recentSpam', 'flaggedComments'));
    }

    /**
     * Show spam keywords management
     */
    public function keywords()
    {
        $keywords = SpamKeyword::orderBy('category')
            ->orderBy('weight', 'desc')
            ->paginate(20);

        $categories = SpamKeyword::distinct('category')->pluck('category');

        return view('admin.spam.keywords', compact('keywords', 'categories'));
    }

    /**
     * Store new spam keyword
     */
    public function storeKeyword(Request $request)
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_regex' => ['boolean'],
        ]);

        // Test regex if it's a regex pattern
        if ($validated['is_regex']) {
            if (@preg_match($validated['keyword'], '') === false) {
                return back()->withErrors(['keyword' => 'Invalid regex pattern.']);
            }
        }

        SpamKeyword::create($validated);

        // Clear cache
        Cache::forget('spam_keywords');

        return back()->with('success', 'Spam keyword added successfully.');
    }

    /**
     * Update spam keyword
     */
    public function updateKeyword(Request $request, SpamKeyword $keyword)
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_regex' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        // Test regex if it's a regex pattern
        if ($validated['is_regex']) {
            if (@preg_match($validated['keyword'], '') === false) {
                return back()->withErrors(['keyword' => 'Invalid regex pattern.']);
            }
        }

        $keyword->update($validated);

        // Clear cache
        Cache::forget('spam_keywords');

        return back()->with('success', 'Spam keyword updated successfully.');
    }

    /**
     * Delete spam keyword
     */
    public function deleteKeyword(SpamKeyword $keyword)
    {
        $keyword->delete();
        Cache::forget('spam_keywords');

        return back()->with('success', 'Spam keyword deleted successfully.');
    }

    /**
     * Show flagged comments
     */
    public function flaggedComments()
    {
        $comments = Comment::flagged()
            ->with(['post', 'user', 'reviewer'])
            ->orderBy('spam_score', 'desc')
            ->paginate(20);

        return view('admin.spam.flagged', compact('comments'));
    }

    /**
     * Show spam comments
     */
    public function spamComments()
    {
        $comments = Comment::spam()
            ->with(['post', 'user', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.spam.spam', compact('comments'));
    }

    /**
     * Approve comment
     */
    public function approveComment(Comment $comment)
    {
        $comment->approve(Auth::user());

        return back()->with('success', 'Comment approved successfully.');
    }

    /**
     * Reject comment
     */
    public function rejectComment(Comment $comment)
    {
        $comment->reject(Auth::user());

        return back()->with('success', 'Comment rejected successfully.');
    }

    /**
     * Mark comment as spam
     */
    public function markAsSpam(Comment $comment)
    {
        $comment->markAsSpam(Auth::user());

        return back()->with('success', 'Comment marked as spam successfully.');
    }

    /**
     * Bulk actions on comments
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject,spam,delete'],
            'comment_ids' => ['required', 'array'],
            'comment_ids.*' => ['exists:comments,id'],
        ]);

        $comments = Comment::whereIn('id', $validated['comment_ids'])->get();

        foreach ($comments as $comment) {
            match($validated['action']) {
                'approve' => $comment->approve(Auth::user()),
                'reject' => $comment->reject(Auth::user()),
                'spam' => $comment->markAsSpam(Auth::user()),
                'delete' => $comment->delete(),
            };
        }

        $count = count($validated['comment_ids']);
        return back()->with('success', "{$count} comments processed successfully.");
    }

    /**
     * Test spam filter
     */
    public function testFilter(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
            'email' => ['nullable', 'email'],
        ]);

        $result = SpamFilterService::isSpam(
            $validated['content'],
            $validated['email'],
            $request->ip()
        );

        return response()->json($result);
    }

    /**
     * Manage blocked IPs
     */
    public function blockedIps()
    {
        $blockedIps = Cache::get('spam_ips', []);
        
        return view('admin.spam.blocked-ips', compact('blockedIps'));
    }

    /**
     * Add IP to spam list
     */
    public function blockIp(Request $request)
    {
        $validated = $request->validate([
            'ip' => ['required', 'ip'],
        ]);

        SpamFilterService::addSpamIp($validated['ip']);

        return back()->with('success', 'IP address blocked successfully.');
    }

    /**
     * Remove IP from spam list
     */
    public function unblockIp(Request $request)
    {
        $validated = $request->validate([
            'ip' => ['required', 'ip'],
        ]);

        SpamFilterService::removeSpamIp($validated['ip']);

        return back()->with('success', 'IP address unblocked successfully.');
    }
}
