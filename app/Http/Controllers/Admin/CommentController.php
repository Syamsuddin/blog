<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        
        $query = Comment::with(['post', 'user', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        switch ($status) {
            case 'approved':
                $query->where('status', 'approved');
                break;
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'spam':
                $query->where('status', 'spam');
                break;
            case 'flagged':
                $query->where('is_flagged', true);
                break;
            case 'rejected':
                $query->where('status', 'rejected');
                break;
        }

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('author_email', 'like', "%{$search}%")
                  ->orWhereHas('post', function($postQuery) use ($search) {
                      $postQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $comments = $query->paginate(20)->withQueryString();
        
        // Statistics for dashboard
        $stats = [
            'total' => Comment::count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'spam' => Comment::where('status', 'spam')->count(),
            'flagged' => Comment::where('is_flagged', true)->count(),
            'rejected' => Comment::where('status', 'rejected')->count(),
        ];
        
        return view('admin.comments.index', compact('comments', 'status', 'search', 'stats'));
    }

    public function show(Comment $comment)
    {
        $comment->load(['post', 'user', 'reviewer']);
        return view('admin.comments.show', compact('comment'));
    }

    public function approve(Comment $comment)
    {
        $comment->approve(Auth::user());
        
        return redirect()->back()->with('success', 'Comment approved successfully!');
    }

    public function reject(Comment $comment)
    {
        $comment->reject(Auth::user());
        
        return redirect()->back()->with('success', 'Comment rejected successfully!');
    }

    public function markAsSpam(Comment $comment)
    {
        $comment->markAsSpam(Auth::user());
        
        return redirect()->back()->with('success', 'Comment marked as spam successfully!');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        
        return redirect()->route('admin.comments.index')->with('success', 'Comment deleted successfully!');
    }

    /**
     * Bulk actions for comments
     */
    public function bulkAction(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Bulk action request', [
            'method' => $request->method(),
            'all_data' => $request->all(),
            'user' => Auth::user() ? Auth::user()->id : 'not authenticated'
        ]);
        
        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject,spam,delete'],
            'comment_ids' => ['required', 'array'],
            'comment_ids.*' => ['exists:comments,id'],
        ]);

        $comments = Comment::whereIn('id', $validated['comment_ids'])->get();
        $count = $comments->count();

        foreach ($comments as $comment) {
            switch ($validated['action']) {
                case 'approve':
                    $comment->approve(Auth::user());
                    break;
                case 'reject':
                    $comment->reject(Auth::user());
                    break;
                case 'spam':
                    $comment->markAsSpam(Auth::user());
                    break;
                case 'delete':
                    $comment->delete();
                    break;
            }
        }

        $actionText = match($validated['action']) {
            'approve' => 'approved',
            'reject' => 'rejected', 
            'spam' => 'marked as spam',
            'delete' => 'deleted'
        };

        return redirect()->back()->with('success', "{$count} comments {$actionText} successfully!");
    }
}
