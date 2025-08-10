@extends('layouts.magazine')

@section('title', $tag->name . ' - Tagged Articles - ' . blog_title())
@section('meta_description', 'Browse all articles tagged with ' . $tag->name . ' on ' . blog_title())

@section('content')
<!-- Tag Header -->
<div class="tag-header text-center py-5 mb-5" style="background: linear-gradient(135deg, var(--secondary-color), var(--accent-color)); color: white; border-radius: 15px;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h1 class="display-4 mb-3" style="font-family: 'Playfair Display', serif;">
          <i class="fas fa-tag me-3"></i>#{{ $tag->name }}
        </h1>
        @if($tag->description)
          <p class="lead mb-4 opacity-90">{{ $tag->description }}</p>
        @endif
        <div class="tag-stats">
          <span class="badge bg-white text-dark fs-6 me-3">
            <i class="fas fa-newspaper me-1"></i> {{ $posts->total() }} Articles
          </span>
          <span class="badge bg-white text-dark fs-6">
            <i class="fas fa-calendar me-1"></i> Updated {{ $posts->first()?->published_at?->format('M Y') ?? 'Recently' }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-decoration-none">Blog</a></li>
    <li class="breadcrumb-item active">#{{ $tag->name }}</li>
  </ol>
</nav>

<!-- Search & Filter -->
<div class="search-filter-section mb-5">
  <div class="row align-items-center">
    <div class="col-lg-6">
      <form method="GET" class="search-form">
        <input type="hidden" name="tag" value="{{ $tag->slug }}">
        <div class="input-group">
          <input type="text" name="search" class="form-control" 
                 placeholder="Search in #{{ $tag->name }} articles..." 
                 value="{{ request('search') }}">
          <button type="submit" class="btn btn-secondary">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </form>
    </div>
    <div class="col-lg-6">
      <div class="d-flex justify-content-lg-end align-items-center mt-3 mt-lg-0">
        <span class="text-muted me-3">Sort by:</span>
        <form method="GET" class="d-flex">
          <input type="hidden" name="tag" value="{{ $tag->slug }}">
          <input type="hidden" name="search" value="{{ request('search') }}">
          <select name="sort" class="form-select form-select-sm me-2" onchange="this.form.submit()" style="width: auto;">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
          </select>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Results Info -->
@if(request('search'))
  <div class="alert alert-info mb-4">
    <i class="fas fa-search me-2"></i>
    Showing {{ $posts->total() }} result(s) for "<strong>{{ request('search') }}</strong>" in <strong>#{{ $tag->name }}</strong>
    <a href="{{ route('posts.tag', $tag->slug) }}" class="ms-2 text-decoration-none">
      <i class="fas fa-times"></i> Clear search
    </a>
  </div>
@endif

<!-- Articles List -->
<div class="articles-list">
  @if($posts->count() > 0)
    @foreach($posts as $post)
      <article class="article-card-horizontal mb-4 border rounded p-4 shadow-sm" style="transition: transform 0.3s ease;">
        <div class="row align-items-center">
          @if($post->featured_image)
            <div class="col-lg-3">
              <div class="article-image">
                <img src="{{ Storage::url($post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="img-fluid rounded"
                     style="height: 150px; width: 100%; object-fit: cover;">
              </div>
            </div>
          @endif
          
          <div class="col-lg-{{ $post->featured_image ? '9' : '12' }}">
            <div class="article-content {{ $post->featured_image ? 'ps-lg-4' : '' }}">
              <!-- Meta Information -->
              <div class="article-meta mb-2">
                <small class="text-muted">
                  <i class="fas fa-calendar me-1"></i> {{ $post->published_at?->format('M d, Y') }}
                  <span class="mx-2">•</span>
                  <i class="fas fa-user me-1"></i> {{ $post->user->name }}
                  @if($post->category)
                    <span class="mx-2">•</span>
                    <i class="fas fa-folder me-1"></i> 
                    <a href="{{ route('posts.category', $post->category->slug) }}" class="text-decoration-none">
                      {{ $post->category->name }}
                    </a>
                  @endif
                  <span class="mx-2">•</span>
                  <i class="fas fa-clock me-1"></i> {{ ceil(str_word_count(strip_tags($post->body)) / 200) }} min read
                </small>
              </div>
              
              <!-- Title -->
              <h4 class="article-title mb-3">
                <a href="{{ route('posts.show', $post->slug) }}" 
                   class="text-decoration-none text-dark"
                   style="font-family: 'Playfair Display', serif; line-height: 1.3;">
                  {{ $post->title }}
                </a>
              </h4>
              
              <!-- Excerpt -->
              @if($post->excerpt)
                <p class="article-excerpt text-muted mb-3" style="font-size: 0.95rem;">
                  {{ Str::limit($post->excerpt, 150) }}
                </p>
              @endif
              
              <!-- Tags -->
              <div class="article-tags mb-3">
                @foreach($post->tags->take(4) as $postTag)
                  <a href="{{ route('posts.tag', $postTag->slug) }}" 
                     class="badge {{ $postTag->id == $tag->id ? 'bg-primary' : 'bg-light text-dark' }} me-1 mb-1 text-decoration-none">
                    #{{ $postTag->name }}
                  </a>
                @endforeach
              </div>
              
              <!-- Footer -->
              <div class="article-footer d-flex justify-content-between align-items-center">
                <div class="article-stats">
                  <small class="text-muted me-3">
                    <i class="fas fa-comments me-1"></i> {{ $post->comments->where('is_approved', true)->count() }}
                  </small>
                  <small class="text-muted">
                    <i class="fas fa-eye me-1"></i> {{ rand(50, 500) }} views
                  </small>
                </div>
                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                  Read more <i class="fas fa-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </article>
    @endforeach
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
      {{ $posts->links('pagination::bootstrap-4') }}
    </div>
  @else
    <!-- No Posts Found -->
    <div class="text-center py-5">
      <div class="empty-state">
        <i class="fas fa-tag fa-4x text-muted mb-4"></i>
        <h3 class="text-muted mb-3">No Articles Found</h3>
        @if(request('search'))
          <p class="text-muted mb-4">No articles match your search criteria for this tag.</p>
          <a href="{{ route('posts.tag', $tag->slug) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> View All #{{ $tag->name }} Articles
          </a>
        @else
          <p class="text-muted mb-4">No articles have been tagged with #{{ $tag->name }} yet.</p>
          <a href="{{ route('posts.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i> Browse All Articles
          </a>
        @endif
      </div>
    </div>
  @endif
</div>

<!-- Related Tags -->
@if(\App\Models\Tag::where('id', '!=', $tag->id)->exists())
  <section class="related-tags mt-5 pt-5 border-top">
    <h4 class="mb-4" style="font-family: 'Playfair Display', serif;">
      <i class="fas fa-tags text-secondary me-2"></i> Explore Other Tags
    </h4>
    <div class="tag-cloud">
      @foreach(\App\Models\Tag::where('id', '!=', $tag->id)->withCount('posts')->take(20)->get() as $otherTag)
        <a href="{{ route('posts.tag', $otherTag->slug) }}" 
           class="badge bg-light text-dark me-2 mb-2 text-decoration-none p-2"
           style="font-size: {{ 0.8 + ($otherTag->posts_count * 0.1) }}rem; transition: all 0.3s ease;">
          #{{ $otherTag->name }}
          <span class="text-muted">({{ $otherTag->posts_count }})</span>
        </a>
      @endforeach
    </div>
  </section>
@endif
@endsection

@section('sidebar')
<div class="sidebar-widget">
  <h6><i class="fas fa-info-circle"></i> About #{{ $tag->name }}</h6>
  <p class="small text-muted">
    {{ $tag->description ?? 'Explore articles and insights related to ' . $tag->name . ' tag.' }}
  </p>
  <div class="tag-stats small">
    <div class="d-flex justify-content-between mb-1">
      <span>Total Articles:</span>
      <strong>{{ $posts->total() }}</strong>
    </div>
    <div class="d-flex justify-content-between mb-1">
      <span>Last Updated:</span>
      <strong>{{ $posts->first()?->published_at?->format('M d, Y') ?? 'N/A' }}</strong>
    </div>
  </div>
</div>

<!-- Popular Tags -->
<div class="sidebar-widget">
  <h6><i class="fas fa-fire"></i> Popular Tags</h6>
  <div class="popular-tags">
    @foreach(\App\Models\Tag::withCount('posts')->orderBy('posts_count', 'desc')->take(10)->get() as $popularTag)
      <a href="{{ route('posts.tag', $popularTag->slug) }}" 
         class="badge {{ $popularTag->id == $tag->id ? 'bg-primary' : 'bg-light text-dark' }} me-1 mb-2 text-decoration-none">
        #{{ $popularTag->name }}
        <span class="text-muted">({{ $popularTag->posts_count }})</span>
      </a>
    @endforeach
  </div>
</div>

<!-- Tag Archive -->
<div class="sidebar-widget">
  <h6><i class="fas fa-archive"></i> #{{ $tag->name }} Archive</h6>
  @php
    $monthlyPosts = \App\Models\Post::published()
      ->whereHas('tags', function($q) use ($tag) {
        $q->where('tags.id', $tag->id);
      })
      ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as count')
      ->groupBy('year', 'month')
      ->orderBy('year', 'desc')
      ->orderBy('month', 'desc')
      ->take(6)
      ->get();
  @endphp
  
  @forelse($monthlyPosts as $archive)
    <div class="d-flex justify-content-between align-items-center mb-2">
      <a href="{{ route('posts.index', ['tag' => $tag->slug, 'month' => $archive->month, 'year' => $archive->year]) }}" 
         class="text-decoration-none small">
        {{ DateTime::createFromFormat('!m', $archive->month)->format('F') }} {{ $archive->year }}
      </a>
      <span class="badge bg-light text-dark">{{ $archive->count }}</span>
    </div>
  @empty
    <p class="small text-muted">No archive data available.</p>
  @endforelse
</div>

@include('layouts.partials.default-sidebar')
@endsection

@push('styles')
<style>
.article-card-horizontal:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.tag-cloud .badge:hover {
  background-color: var(--primary-color) !important;
  color: white !important;
  transform: scale(1.05);
}

.search-form .form-control:focus {
  border-color: var(--secondary-color);
  box-shadow: 0 0 0 0.2rem rgba(var(--secondary-color-rgb), 0.25);
}

.popular-tags .badge:hover {
  background-color: var(--secondary-color) !important;
  color: white !important;
}
</style>
@endpush
