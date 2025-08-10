<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50 mb-0">Total Posts</h6>
                                    <h2 class="mb-0">{{ \App\Models\Post::count() }}</h2>
                                </div>
                                <i class="fas fa-newspaper fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50 mb-0">Published</h6>
                                    <h2 class="mb-0">{{ \App\Models\Post::published()->count() }}</h2>
                                </div>
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-dark mb-0">Comments</h6>
                                    <h2 class="mb-0">{{ \App\Models\Comment::count() }}</h2>
                                </div>
                                <i class="fas fa-comments fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50 mb-0">Categories</h6>
                                    <h2 class="mb-0">{{ \App\Models\Category::count() }}</h2>
                                </div>
                                <i class="fas fa-folder fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Posts -->
                <div class="col-lg-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">Recent Posts</h5>
                                <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-primary">
                                    View All
                                </a>
                            </div>
                            
                            @php
                                $recentPosts = \App\Models\Post::with(['user', 'category'])
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp

                            @if($recentPosts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPosts as $post)
                                                <tr>
                                                    <td>
                                                        <div class="text-truncate" style="max-width: 300px;">
                                                            {{ $post->title }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($post->category)
                                                            <span class="badge bg-light text-dark">{{ $post->category->name }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($post->published_at && $post->published_at <= now())
                                                            <span class="badge bg-success">Published</span>
                                                        @else
                                                            <span class="badge bg-warning">Draft</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $post->created_at->format('M d, Y') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('admin.posts.edit', $post) }}" 
                                                               class="btn btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            @if($post->published_at && $post->published_at <= now())
                                                                <a href="{{ route('posts.show', $post->slug) }}" 
                                                                   class="btn btn-outline-success" target="_blank">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No posts yet</h6>
                                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                                        Create Your First Post
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Comments -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                        <div class="p-6">
                            <h5 class="mb-4">Quick Actions</h5>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> New Post
                                </a>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-folder me-2"></i> New Category
                                </a>
                                <a href="{{ route('admin.tags.create') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-tag me-2"></i> New Tag
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-outline-success" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i> View Site
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Comments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">Pending Comments</h5>
                                <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    View All
                                </a>
                            </div>

                            @php
                                $pendingComments = \App\Models\Comment::with(['post', 'user'])
                                    ->where('is_approved', false)
                                    ->latest()
                                    ->take(3)
                                    ->get();
                            @endphp

                            @if($pendingComments->count() > 0)
                                @foreach($pendingComments as $comment)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="small text-muted mb-2">
                                            <strong>{{ $comment->user->name ?? $comment->author_name ?? 'Guest' }}</strong>
                                            on <a href="{{ route('posts.show', $comment->post->slug) }}" class="text-decoration-none" target="_blank">
                                                {{ Str::limit($comment->post->title, 30) }}
                                            </a>
                                        </div>
                                        <div class="small mb-2">
                                            {{ Str::limit($comment->body, 80) }}
                                        </div>
                                        <div class="d-flex gap-1">
                                            <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.comments.show', $comment) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <small class="text-muted d-block">No pending comments</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
