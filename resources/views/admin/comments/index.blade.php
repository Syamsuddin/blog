@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Comment Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Comments</li>
                    </ol>
                </nav>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Total</h6>
                                    <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-comments fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Approved</h6>
                                    <h4 class="mb-0">{{ $stats['approved'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Pending</h6>
                                    <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Spam</h6>
                                    <h4 class="mb-0">{{ $stats['spam'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-ban fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Flagged</h6>
                                    <h4 class="mb-0">{{ $stats['flagged'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-flag fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Rejected</h6>
                                    <h4 class="mb-0">{{ $stats['rejected'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.comments.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Filter by Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Comments</option>
                                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="spam" {{ $status === 'spam' ? 'selected' : '' }}>Spam</option>
                                <option value="flagged" {{ $status === 'flagged' ? 'selected' : '' }}>Flagged</option>
                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Search by content, author, email, or post title..."
                                   value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Comments Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Comments 
                        @if($status !== 'all')
                            <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                        @endif
                    </h5>
                    
                    @if($comments->count() > 0)
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" 
                                id="bulkActions" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i> Bulk Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="bulkAction('approve')">
                                <i class="fas fa-check text-success"></i> Approve Selected
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="bulkAction('reject')">
                                <i class="fas fa-times text-warning"></i> Reject Selected
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="bulkAction('spam')">
                                <i class="fas fa-ban text-danger"></i> Mark as Spam
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">
                                <i class="fas fa-trash"></i> Delete Selected
                            </a></li>
                        </ul>
                    </div>
                    @endif
                </div>
                
                <div class="card-body">
                    @if($comments->count() > 0)
                    <form id="bulkForm" method="POST" action="{{ route('admin.comments.bulk') }}">
                        @csrf
                        <input type="hidden" name="action" id="bulkAction" value="">
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Author</th>
                                        <th>Comment</th>
                                        <th>Post</th>
                                        <th>Status</th>
                                        <th>Spam Score</th>
                                        <th>Date</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comments as $comment)
                                    <tr class="{{ $comment->isSpam() ? 'table-danger' : ($comment->is_flagged ? 'table-warning' : '') }}">
                                        <td>
                                            <input type="checkbox" name="comment_ids[]" value="{{ $comment->id }}" 
                                                   class="form-check-input comment-checkbox">
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $comment->user ? $comment->user->name : $comment->author_name }}</strong>
                                                @if($comment->author_email)
                                                    <br><small class="text-muted">{{ $comment->author_email }}</small>
                                                @endif
                                                @if($comment->ip_address)
                                                    <br><small class="text-muted">IP: {{ $comment->ip_address }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="comment-content">
                                                {{ Str::limit($comment->body, 100) }}
                                                @if($comment->is_flagged)
                                                    <span class="badge bg-warning ms-1">
                                                        <i class="fas fa-flag"></i> Flagged
                                                    </span>
                                                @endif
                                            </div>
                                            @if($comment->spam_reasons)
                                                <div class="mt-1">
                                                    @foreach(array_slice($comment->spam_reasons, 0, 2) as $reason)
                                                        <small class="badge bg-danger me-1">{{ $reason }}</small>
                                                    @endforeach
                                                    @if(count($comment->spam_reasons) > 2)
                                                        <small class="text-muted">+{{ count($comment->spam_reasons) - 2 }} more</small>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($comment->post)
                                                <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank" 
                                                   class="text-decoration-none">
                                                    {{ Str::limit($comment->post->title, 30) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Post deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($comment->status)
                                                @case('approved')
                                                    <span class="badge bg-success">Approved</span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('spam')
                                                    <span class="badge bg-danger">Spam</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-secondary">Rejected</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">Unknown</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($comment->spam_score > 0)
                                                <span class="badge bg-{{ $comment->spam_score >= 50 ? 'danger' : ($comment->spam_score >= 30 ? 'warning' : 'info') }}">
                                                    {{ $comment->spam_score }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $comment->created_at->format('M j, Y') }}</small>
                                            <br>
                                            <small class="text-muted">{{ $comment->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.comments.show', $comment) }}" 
                                                   class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($comment->status !== 'approved')
                                                <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success btn-sm" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                
                                                @if($comment->status !== 'rejected')
                                                <form method="POST" action="{{ route('admin.comments.reject', $comment) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-warning btn-sm" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                
                                                @if($comment->status !== 'spam')
                                                <form method="POST" action="{{ route('admin.comments.spam', $comment) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Mark as Spam">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                
                                                <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                    
                    <!-- Pagination -->
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $comments->appends(request()->query())->links('pagination.admin') }}
                    </div>
                    
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No comments found</h5>
                        <p class="text-muted">
                            @if($status !== 'all')
                                No {{ $status }} comments available.
                            @else
                                There are no comments to display.
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Select All functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.comment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

// Bulk actions
function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.comment-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('Please select at least one comment.');
        return;
    }
    
    const actionText = {
        'approve': 'approve',
        'reject': 'reject', 
        'spam': 'mark as spam',
        'delete': 'delete'
    };
    
    if (confirm('Are you sure you want to ' + actionText[action] + ' ' + checkedBoxes.length + ' selected comments?')) {
        console.log('Setting action to:', action);
        const bulkActionInput = document.getElementById('bulkAction');
        const form = document.getElementById('bulkForm');
        
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);
        
        bulkActionInput.value = action;
        console.log('Action input value set to:', bulkActionInput.value);
        
        form.submit();
    }
}
</script>
@endsection