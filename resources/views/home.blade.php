@extends('layouts.magazine')

@section('title', 'Home - ' . blog_title())
@section('meta_description', 'Welcome to ' . blog_title() . ' - ' . blog_description())

@section('content')
<div class="row mb-4">
  <div class="col-12">
    <div class="hero-section bg-primary text-white rounded-3 p-4 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
      <h1 class="display-5 fw-bold mb-3">Welcome to {{ blog_title() }}</h1>
      <p class="lead mb-4">{{ blog_description() }}</p>
      <a href="{{ route('posts.index') }}" class="btn btn-light btn-lg">
        <i class="fas fa-newspaper"></i> Explore Articles
      </a>
    </div>
  </div>
</div>

<div class="section-header mb-4">
  <h2 class="h3 mb-1" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
    <i class="fas fa-star text-warning"></i> Featured Articles
  </h2>
  <p class="text-muted">Our latest and most popular content</p>
</div>

<div class="row">
  @forelse($posts as $post)
    <div class="col-lg-6 mb-4">
      <article class="article-card">
        @if($post->featured_image)
          <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="card-img-top">
        @else
          <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <i class="fas fa-image fa-3x text-muted"></i>
          </div>
        @endif
        <div class="card-body">
          <div class="article-meta">
            <i class="fas fa-calendar"></i> {{ optional($post->published_at)->format('M d, Y') }}
            <span class="mx-2">•</span>
            <i class="fas fa-user"></i> {{ $post->user->name }}
            @if($post->category)
              <span class="mx-2">•</span>
              <i class="fas fa-folder"></i> {{ $post->category->name }}
            @endif
          </div>
          <h3 class="article-title">
            <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
          </h3>
          @if($post->excerpt)
            <p class="text-muted mb-3">{{ Str::limit($post->excerpt, 120) }}</p>
          @else
            <p class="text-muted mb-3">{{ Str::limit(strip_tags($post->body), 120) }}</p>
          @endif
          <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('posts.show', $post->slug) }}" class="btn-read-more">
              <i class="fas fa-arrow-right"></i> Read More
            </a>
            @if($post->tags->count() > 0)
              <div class="article-tags">
                @foreach($post->tags->take(3) as $tag)
                  <span class="badge bg-light text-dark me-1" style="font-size: 0.7rem;">{{ $tag->name }}</span>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </article>
    </div>
  @empty
    <div class="col-12">
      <div class="text-center py-5">
        <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">No posts yet</h4>
        <p class="text-muted">Check back soon for amazing content!</p>
        @auth
          @can('admin')
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
              <i class="fas fa-plus"></i> Create First Post
            </a>
          @endcan
        @endauth
      </div>
    </div>
  @endforelse
</div>

@if($posts->count() > 0)
  <div class="text-center mt-4">
    <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-lg">
      <i class="fas fa-th-large"></i> View All Articles
    </a>
  </div>
@endif
@endsection

@section('sidebar')
<!-- Featured Categories -->
<div class="sidebar-widget">
    <h5><i class="fas fa-star"></i> Featured Categories</h5>
    @php
        $featuredCategories = \App\Models\Category::withCount('posts')->orderBy('posts_count', 'desc')->take(5)->get();
    @endphp
    @foreach($featuredCategories as $category)
        <div class="mb-3 p-3 bg-light rounded">
            <h6 class="mb-1">
                <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    {{ $category->name }}
                </a>
            </h6>
            <small class="text-muted">{{ $category->posts_count }} articles</small>
        </div>
    @endforeach
</div>

<!-- Quick Stats -->
<div class="sidebar-widget">
    <h5><i class="fas fa-chart-bar"></i> Blog Stats</h5>
    <div class="row text-center">
        <div class="col-6 mb-3">
            <div class="bg-primary text-white rounded p-3">
                <h4 class="mb-0">{{ \App\Models\Post::published()->count() }}</h4>
                <small>Articles</small>
            </div>
        </div>
        <div class="col-6 mb-3">
            <div class="bg-success text-white rounded p-3">
                <h4 class="mb-0">{{ \App\Models\Category::count() }}</h4>
                <small>Categories</small>
            </div>
        </div>
        <div class="col-6">
            <div class="bg-info text-white rounded p-3">
                <h4 class="mb-0">{{ \App\Models\Tag::count() }}</h4>
                <small>Tags</small>
            </div>
        </div>
        <div class="col-6">
            <div class="bg-warning text-white rounded p-3">
                <h4 class="mb-0">{{ \App\Models\Comment::where('is_approved', true)->count() }}</h4>
                <small>Comments</small>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.default-sidebar')
@endsection
