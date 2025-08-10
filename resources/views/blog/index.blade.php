@extends('layouts.magazine')

@section('title', 'Blog - ' . blog_title())
@section('meta_description', 'Browse all articles and posts on ' . blog_title() . '. ' . blog_description())

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h2 mb-1" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
      <i class="fas fa-newspaper"></i> All Articles
    </h1>
    @if($q)
      <p class="text-muted mb-0">Search results for "<strong>{{ $q }}</strong>"</p>
    @else
      <p class="text-muted mb-0">Discover our latest stories and insights</p>
    @endif
  </div>
  <div class="text-end">
    <small class="text-muted">{{ $posts->total() }} articles found</small>
  </div>
</div>

<!-- Advanced Search & Filters -->
<div class="card mb-4">
  <div class="card-body">
    <form method="get" class="row g-3">
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Search articles...">
        </div>
      </div>
      <div class="col-md-3">
        <select name="category" class="form-select">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <div class="d-grid">
          <button class="btn btn-primary" type="submit">
            <i class="fas fa-filter"></i> Filter
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Articles Grid -->
@forelse($posts as $post)
  <article class="article-card">
    <div class="row g-0">
      <div class="col-md-4">
        @if($post->featured_image)
          <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 200px;">
        @else
          <div class="bg-light d-flex align-items-center justify-content-center h-100" style="min-height: 200px;">
            <i class="fas fa-image fa-3x text-muted"></i>
          </div>
        @endif
      </div>
      <div class="col-md-8">
        <div class="card-body h-100 d-flex flex-column">
          <div class="article-meta">
            <i class="fas fa-calendar"></i> {{ optional($post->published_at)->format('M d, Y') }}
            <span class="mx-2">•</span>
            <i class="fas fa-user"></i> {{ $post->user->name }}
            @if($post->category)
              <span class="mx-2">•</span>
              <i class="fas fa-folder"></i> 
              <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="text-decoration-none">
                {{ $post->category->name }}
              </a>
            @endif
            <span class="mx-2">•</span>
            <i class="fas fa-comments"></i> {{ $post->comments->where('is_approved', true)->count() }} comments
          </div>
          
          <h3 class="article-title mt-2">
            <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
          </h3>
          
          <div class="flex-grow-1">
            @if($post->excerpt)
              <p class="article-excerpt">{{ Str::limit($post->excerpt, 200) }}</p>
            @else
              <p class="article-excerpt">{{ Str::limit(strip_tags($post->body), 200) }}</p>
            @endif
          </div>
          
          <div class="d-flex justify-content-between align-items-center mt-auto pt-3">
            <a href="{{ route('posts.show', $post->slug) }}" class="btn-read-more">
              <i class="fas fa-arrow-right"></i> Read Full Article
            </a>
            @if($post->tags->count() > 0)
              <div class="article-tags">
                @foreach($post->tags->take(3) as $tag)
                  <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="badge bg-light text-dark text-decoration-none">
                    {{ $tag->name }}
                  </a>
                @endforeach
                @if($post->tags->count() > 3)
                  <span class="text-muted small">+{{ $post->tags->count() - 3 }} more</span>
                @endif
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </article>
@empty
  <div class="text-center py-5">
    <i class="fas fa-search fa-4x text-muted mb-3"></i>
    <h4 class="text-muted">No articles found</h4>
    @if($q)
      <p class="text-muted">Try adjusting your search terms or <a href="{{ route('posts.index') }}">browse all articles</a>.</p>
    @else
      <p class="text-muted">Check back soon for amazing content!</p>
    @endif
  </div>
@endforelse

<!-- Pagination -->
@if($posts->hasPages())
  <div class="d-flex justify-content-center mt-5">
    {{ $posts->links('pagination::bootstrap-4') }}
  </div>
@endif
@endsection

@section('sidebar')
<!-- Search Tips -->
@if($q)
<div class="sidebar-widget">
    <h5><i class="fas fa-lightbulb"></i> Search Tips</h5>
    <ul style="font-size: 0.9rem;">
        <li>Use quotes for exact phrases</li>
        <li>Try different keywords</li>
        <li>Check spelling and try simpler terms</li>
        <li>Browse categories below</li>
    </ul>
    <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-times"></i> Clear Search
    </a>
</div>
@endif

<!-- Trending Articles -->
<div class="sidebar-widget">
    <h5><i class="fas fa-fire"></i> Trending This Week</h5>
    @php
        $trendingPosts = \App\Models\Post::published()
            ->withCount(['comments' => function($q) { $q->where('is_approved', true); }])
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();
    @endphp
    @foreach($trendingPosts as $index => $post)
        <div class="d-flex mb-3 align-items-start">
            <span class="badge bg-primary me-2 fs-6" style="min-width: 30px;">{{ $index + 1 }}</span>
            <div>
                <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                    <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                        {{ Str::limit($post->title, 50) }}
                    </a>
                </h6>
                <small class="text-muted">
                    <i class="fas fa-comments"></i> {{ $post->comments_count }} comments
                </small>
            </div>
        </div>
    @endforeach
</div>

@include('layouts.partials.default-sidebar')
@endsection
