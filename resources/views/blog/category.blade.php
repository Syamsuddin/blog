@extends('layouts.magazine')

@section('title', $category->name . ' - Articles - ' . blog_title())
@section('meta_description', 'Browse all articles in ' . $category->name . ' category on ' . blog_title())

@section('content')
<!-- Category Header -->
<div class="category-header text-center py-5 mb-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-radius: 15px;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h1 class="display-4 mb-3" style="font-family: 'Playfair Display', serif;">
          <i class="fas fa-folder-open me-3"></i>{{ $category->name }}
        </h1>
        @if($category->description)
          <p class="lead mb-4 opacity-90">{{ $category->description }}</p>
        @endif
        <div class="category-stats">
          <span class="badge bg-white text-primary fs-6 me-3">
            <i class="fas fa-newspaper me-1"></i> {{ $posts->total() }} Articles
          </span>
          <span class="badge bg-white text-primary fs-6">
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
    <li class="breadcrumb-item active">{{ $category->name }}</li>
  </ol>
</nav>

<!-- Search & Filter -->
<div class="search-filter-section mb-5">
  <div class="row align-items-center">
    <div class="col-lg-6">
      <form method="GET" class="search-form">
        <input type="hidden" name="category" value="{{ $category->slug }}">
        <div class="input-group">
          <input type="text" name="search" class="form-control" 
                 placeholder="Search in {{ $category->name }}..." 
                 value="{{ request('search') }}">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </form>
    </div>
    <div class="col-lg-6">
      <div class="d-flex justify-content-lg-end align-items-center mt-3 mt-lg-0">
        <span class="text-muted me-3">Sort by:</span>
        <form method="GET" class="d-flex">
          <input type="hidden" name="category" value="{{ $category->slug }}">
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
    Showing {{ $posts->total() }} result(s) for "<strong>{{ request('search') }}</strong>" in <strong>{{ $category->name }}</strong>
    <a href="{{ route('posts.category', $category->slug) }}" class="ms-2 text-decoration-none">
      <i class="fas fa-times"></i> Clear search
    </a>
  </div>
@endif

<!-- Articles Grid -->
<div class="articles-grid">
  @if($posts->count() > 0)
    <div class="row">
      @foreach($posts as $post)
        <div class="col-lg-6 mb-4">
          <article class="article-card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden; transition: transform 0.3s ease;">
            @if($post->featured_image)
              <div class="article-image position-relative">
                <img src="{{ Storage::url($post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="card-img-top" 
                     style="height: 200px; object-fit: cover;">
                <div class="article-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end" 
                     style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                  <div class="p-3">
                    @if($post->tags->count() > 0)
                      @foreach($post->tags->take(2) as $tag)
                        <span class="badge bg-white text-dark me-1 mb-1" style="font-size: 0.7rem;">
                          {{ $tag->name }}
                        </span>
                      @endforeach
                    @endif
                  </div>
                </div>
              </div>
            @endif
            
            <div class="card-body d-flex flex-column p-4">
              <div class="article-meta mb-2">
                <small class="text-muted">
                  <i class="fas fa-calendar me-1"></i> {{ $post->published_at?->format('M d, Y') }}
                  <span class="mx-2">•</span>
                  <i class="fas fa-user me-1"></i> {{ $post->user->name }}
                  <span class="mx-2">•</span>
                  <i class="fas fa-clock me-1"></i> {{ ceil(str_word_count(strip_tags($post->body)) / 200) }} min
                </small>
              </div>
              
              <h5 class="article-title mb-3">
                <a href="{{ route('posts.show', $post->slug) }}" 
                   class="text-decoration-none text-dark stretched-link"
                   style="font-family: 'Playfair Display', serif; line-height: 1.3;">
                  {{ $post->title }}
                </a>
              </h5>
              
              @if($post->excerpt)
                <p class="article-excerpt text-muted mb-3 flex-grow-1" style="font-size: 0.95rem;">
                  {{ Str::limit($post->excerpt, 120) }}
                </p>
              @endif
              
              <div class="article-footer d-flex justify-content-between align-items-center">
                <small class="text-muted">
                  <i class="fas fa-comments me-1"></i> {{ $post->comments->where('is_approved', true)->count() }}
                </small>
                <small class="text-primary">
                  Read more <i class="fas fa-arrow-right ms-1"></i>
                </small>
              </div>
            </div>
          </article>
        </div>
      @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
      {{ $posts->links('pagination::bootstrap-4') }}
    </div>
  @else
    <!-- No Posts Found -->
    <div class="text-center py-5">
      <div class="empty-state">
        <i class="fas fa-search fa-4x text-muted mb-4"></i>
        <h3 class="text-muted mb-3">No Articles Found</h3>
        @if(request('search'))
          <p class="text-muted mb-4">No articles match your search criteria in this category.</p>
          <a href="{{ route('posts.category', $category->slug) }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i> View All {{ $category->name }} Articles
          </a>
        @else
          <p class="text-muted mb-4">No articles have been published in this category yet.</p>
          <a href="{{ route('posts.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i> Browse All Articles
          </a>
        @endif
      </div>
    </div>
  @endif
</div>

<!-- Related Categories -->
@if(\App\Models\Category::where('id', '!=', $category->id)->exists())
  <section class="related-categories mt-5 pt-5 border-top">
    <h4 class="mb-4" style="font-family: 'Playfair Display', serif;">
      <i class="fas fa-th-large text-primary me-2"></i> Explore Other Categories
    </h4>
    <div class="row">
      @foreach(\App\Models\Category::where('id', '!=', $category->id)->withCount('posts')->take(6)->get() as $otherCategory)
        <div class="col-md-4 col-lg-2 mb-3">
          <a href="{{ route('posts.category', $otherCategory->slug) }}" 
             class="d-block text-decoration-none p-3 text-center border rounded hover-shadow"
             style="transition: all 0.3s ease;">
            <div class="category-icon mb-2">
              <i class="fas fa-folder fa-2x text-primary"></i>
            </div>
            <h6 class="mb-1 text-dark">{{ $otherCategory->name }}</h6>
            <small class="text-muted">{{ $otherCategory->posts_count }} articles</small>
          </a>
        </div>
      @endforeach
    </div>
  </section>
@endif
@endsection

@section('sidebar')
<div class="sidebar-widget">
  <h6><i class="fas fa-info-circle"></i> About {{ $category->name }}</h6>
  <p class="small text-muted">
    {{ $category->description ?? 'Discover insightful articles and latest updates in ' . $category->name . ' category.' }}
  </p>
  <div class="category-stats small">
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

<!-- Category Archive -->
<div class="sidebar-widget">
  <h6><i class="fas fa-archive"></i> {{ $category->name }} Archive</h6>
  @php
    $monthlyPosts = \App\Models\Post::published()
      ->where('category_id', $category->id)
      ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as count')
      ->groupBy('year', 'month')
      ->orderBy('year', 'desc')
      ->orderBy('month', 'desc')
      ->take(6)
      ->get();
  @endphp
  
  @forelse($monthlyPosts as $archive)
    <div class="d-flex justify-content-between align-items-center mb-2">
      <a href="{{ route('posts.index', ['category' => $category->slug, 'month' => $archive->month, 'year' => $archive->year]) }}" 
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
.article-card:hover {
  transform: translateY(-5px);
}

.hover-shadow:hover {
  box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
  transform: translateY(-2px);
}

.search-form .form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
}
</style>
@endpush
