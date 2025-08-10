<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ __('Comment Details') }}</h2>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Comments
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                <!-- Comment Details -->
                <div class="col-lg-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            @if(session('status'))
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="comment-header mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">{{ $comment->user->name ?? $comment->author_name ?? 'Guest' }}</h5>
                                        @if($comment->author_email)
                                            <small class="text-muted">{{ $comment->author_email }}</small>
                                        @endif
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i> 
                                                {{ $comment->created_at->format('F d, Y \a\t H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @if($comment->is_approved)
                                            <span class="badge bg-success fs-6">Approved</span>
                                        @else
                                            <span class="badge bg-warning fs-6">Pending Approval</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="comment-content mb-4">
                                <h6>Comment:</h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $comment->body }}
                                </div>
                            </div>

                            <div class="comment-post-info mb-4">
                                <h6>Posted on:</h6>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-newspaper text-primary me-2"></i>
                                    <div>
                                        <a href="{{ route('posts.show', $comment->post->slug) }}" 
                                           class="text-decoration-none" target="_blank">
                                            {{ $comment->post->title }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            by {{ $comment->post->user->name }} • 
                                            {{ $comment->post->published_at?->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Sidebar -->
                <div class="col-lg-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h6 class="mb-4">Actions</h6>
                            
                            <div class="d-grid gap-2">
                                @if(!$comment->is_approved)
                                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-check me-2"></i> Approve Comment
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.comments.reject', $comment) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="fas fa-times me-2"></i> Reject Comment
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('posts.show', $comment->post->slug) }}#comment-{{ $comment->id }}" 
                                   class="btn btn-outline-primary w-100" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i> View on Site
                                </a>

                                <form method="POST" 
                                      action="{{ route('admin.comments.destroy', $comment) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this comment? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash me-2"></i> Delete Comment
                                    </button>
                                </form>
                            </div>

                            <!-- Comment Stats -->
                            <div class="mt-4 pt-4 border-top">
                                <h6 class="mb-3">Comment Information</h6>
                                <div class="small">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Status:</span>
                                        <span class="fw-bold">
                                            {{ $comment->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Posted:</span>
                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($comment->updated_at != $comment->created_at)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Modified:</span>
                                            <span>{{ $comment->updated_at->diffForHumans() }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Word Count:</span>
                                        <span>{{ str_word_count($comment->body) }} words</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Author Info -->
                            @if($comment->user)
                                <div class="mt-4 pt-4 border-top">
                                    <h6 class="mb-3">Author Information</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $comment->user->name }}</div>
                                            <small class="text-muted">{{ $comment->user->email }}</small>
                                        </div>
                                    </div>
                                    <div class="mt-3 small">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Member since:</span>
                                            <span>{{ $comment->user->created_at->format('M Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Total comments:</span>
                                            <span>{{ $comment->user->comments()->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
