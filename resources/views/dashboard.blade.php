@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> New Post
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-folder-plus"></i> New Category
                </a>
                <a href="{{ route('admin.tags.create') }}" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-tag"></i> New Tag
                </a>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-cog"></i> Admin
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.posts.index') }}">
                        <i class="fas fa-newspaper"></i> Manage Posts
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-folder"></i> Manage Categories
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.tags.index') }}">
                        <i class="fas fa-tags"></i> Manage Tags
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.comments.index') }}">
                        <i class="fas fa-comments"></i> Manage Comments
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i> Settings
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

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
                            <h2 class="mb-0">{{ \App\Models\Post::where('published_at', '<=', now())->count() }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-0">Draft</h6>
                            <h2 class="mb-0">{{ \App\Models\Post::whereNull('published_at')->orWhere('published_at', '>', now())->count() }}</h2>
                        </div>
                        <i class="fas fa-edit fa-2x opacity-75"></i>
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
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Posts</h5>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
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

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ \App\Models\Tag::count() }}</h4>
                                <small class="text-muted">Tags</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ \App\Models\Comment::where('is_approved', true)->count() }}</h4>
                            <small class="text-muted">Comments</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-cog me-2"></i>Blog Settings
                        </a>
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-folder-plus me-1"></i>Category
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.tags.create') }}" class="btn btn-outline-warning btn-sm w-100">
                                    <i class="fas fa-tag me-1"></i>Tag
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Categories -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Categories</h5>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-success">
                            Manage
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $recentCategories = \App\Models\Category::withCount('posts')
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp

                    @if($recentCategories->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentCategories as $category)
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <div>
                                        <h6 class="mb-1">{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $category->posts_count }} posts</small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('posts.category', $category->slug) }}" 
                                           class="btn btn-outline-success btn-sm" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Add Category
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-folder fa-2x text-muted mb-2"></i>
                            <h6 class="text-muted mb-2">No categories yet</h6>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Create First Category
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pending Comments -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pending Comments</h5>
                        <a href="{{ route('admin.comments.index') }}" 
                           class="btn btn-sm btn-outline-warning">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
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
                                <p class="mb-2">{{ Str::limit($comment->content, 100) }}</p>
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.comments.reject', $comment) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i>
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

            <!-- Popular Tags -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Popular Tags</h5>
                        <a href="{{ route('admin.tags.index') }}" class="btn btn-sm btn-outline-warning">
                            Manage
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $popularTags = \App\Models\Tag::withCount('posts')
                            ->orderBy('posts_count', 'desc')
                            ->take(8)
                            ->get();
                    @endphp

                    @if($popularTags->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                                <span class="badge bg-light text-dark border d-flex align-items-center">
                                    <a href="{{ route('posts.tag', $tag->slug) }}" 
                                       class="text-decoration-none text-dark me-2" target="_blank">
                                        {{ $tag->name }}
                                        <small class="text-muted">({{ $tag->posts_count }})</small>
                                    </a>
                                    <a href="{{ route('admin.tags.edit', $tag) }}" 
                                       class="text-primary">
                                        <i class="fas fa-edit fa-xs"></i>
                                    </a>
                                </span>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.tags.create') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus"></i> Add Tag
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-tags fa-2x text-muted mb-2"></i>
                            <h6 class="text-muted mb-2">No tags yet</h6>
                            <a href="{{ route('admin.tags.create') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus"></i> Create First Tag
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
