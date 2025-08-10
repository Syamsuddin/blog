@extends('layouts.magazine')

@section('title', $post->meta['meta_title'] ?? $post->title . ' - ' . blog_title())
@section('meta_description', $post->meta['meta_description'] ?? Str::limit(strip_tags($post->body), 160))

@section('content')
<article class="article-single">
  <!-- Article Header -->
  <header class="article-header mb-4">
    <div class="row">
      <div class="col-lg-8">
        <div class="article-meta mb-3">
          <i class="fas fa-calendar text-primary"></i> {{ optional($post->published_at)->format('F d, Y') }}
          <span class="mx-2">•</span>
          <i class="fas fa-user text-primary"></i> {{ $post->user->name }}
          @if($post->category)
            <span class="mx-2">•</span>
            <i class="fas fa-folder text-primary"></i> 
            <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="text-decoration-none">
              {{ $post->category->name }}
            </a>
          @endif
          <span class="mx-2">•</span>
          <i class="fas fa-comments text-primary"></i> {{ $post->comments->where('is_approved', true)->count() }} comments
          <span class="mx-2">•</span>
          <i class="fas fa-clock text-primary"></i> {{ ceil(str_word_count(strip_tags($post->body)) / 200) }} min read
        </div>
        
        <h1 class="article-title display-5 mb-3" style="font-family: 'Playfair Display', serif; color: var(--primary-color); line-height: 1.2;">
          {{ $post->title }}
        </h1>
        
        @if($post->excerpt)
          <p class="lead text-muted mb-4">{{ $post->excerpt }}</p>
        @endif
        
        @if($post->tags->count() > 0)
          <div class="article-tags mb-4">
            <i class="fas fa-tags text-muted me-2"></i>
            @foreach($post->tags as $tag)
              <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="badge bg-light text-dark me-2 mb-1 text-decoration-none">
                {{ $tag->name }}
              </a>
            @endforeach
          </div>
        @endif
      </div>
      <div class="col-lg-4">
        <div class="text-lg-end">
          <!-- Social Share Buttons -->
          <div class="social-share mb-3">
            <p class="small text-muted mb-2">Share this article:</p>
            <a href="#" class="btn btn-sm btn-outline-primary me-1" onclick="shareOnFacebook()">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="btn btn-sm btn-outline-info me-1" onclick="shareOnTwitter()">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="btn btn-sm btn-outline-success me-1" onclick="shareOnWhatsApp()">
              <i class="fab fa-whatsapp"></i>
            </a>
            <a href="#" class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard()">
              <i class="fas fa-link"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Featured Image -->
  @if($post->featured_image)
    <div class="article-featured-image mb-5">
      <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 500px; object-fit: cover;">
    </div>
  @endif

  <!-- Article Content -->
  <div class="article-content">
    <div class="row">
      <div class="col-lg-8">
        <div class="content-body" style="font-size: 1.1rem; line-height: 1.8; color: var(--text-color);">
          {!! $post->body !!}
        </div>
        
        <!-- Author Bio -->
        <div class="author-bio mt-5 p-4 bg-light rounded">
          <div class="row align-items-center">
            <div class="col-auto">
              <div class="author-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                {{ substr($post->user->name, 0, 1) }}
              </div>
            </div>
            <div class="col">
              <h5 class="mb-1">{{ $post->user->name }}</h5>
              <p class="text-muted mb-0">Article author and contributor to {{ blog_title() }}. Passionate about sharing knowledge and insights.</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Article Sidebar -->
      <div class="col-lg-4">
        <div class="article-sidebar position-sticky" style="top: 2rem;">
          <!-- Table of Contents (if needed) -->
          <div class="sidebar-widget">
            <h6><i class="fas fa-list"></i> Quick Actions</h6>
            <div class="d-grid gap-2">
              <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                <i class="fas fa-print"></i> Print Article
              </button>
              <button class="btn btn-outline-secondary btn-sm" onclick="toggleBookmark()">
                <i class="fas fa-bookmark"></i> Bookmark
              </button>
              <a href="{{ route('posts.index') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Blog
              </a>
            </div>
          </div>
          
          <!-- Related Articles -->
          @php
            $relatedPosts = \App\Models\Post::published()
              ->where('id', '!=', $post->id)
              ->where(function($q) use ($post) {
                $q->where('category_id', $post->category_id)
                  ->orWhereHas('tags', function($q2) use ($post) {
                    $q2->whereIn('tags.id', $post->tags->pluck('id'));
                  });
              })
              ->take(3)
              ->get();
          @endphp
          
          @if($relatedPosts->count() > 0)
            <div class="sidebar-widget">
              <h6><i class="fas fa-thumbs-up"></i> You Might Also Like</h6>
              @foreach($relatedPosts as $related)
                <div class="mb-3">
                  <h6 class="mb-1" style="font-size: 0.9rem;">
                    <a href="{{ route('posts.show', $related->slug) }}" class="text-decoration-none">
                      {{ Str::limit($related->title, 50) }}
                    </a>
                  </h6>
                  <small class="text-muted">{{ $related->published_at?->format('M d, Y') }}</small>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</article>

<!-- Comments Section -->
<section class="comments-section mt-5">
  <div class="row">
    <div class="col-lg-8">
      <div class="comments-header mb-4">
        <h3 class="h4" style="font-family: 'Playfair Display', serif;">
          <i class="fas fa-comments text-primary"></i> 
          Comments ({{ $post->comments->where('is_approved', true)->count() }})
        </h3>
      </div>

      <!-- Existing Comments -->
      @forelse($post->comments as $comment)
        <div class="comment-item border rounded p-4 mb-4 bg-light">
          <div class="comment-header d-flex align-items-center mb-3">
            <div class="comment-avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
              {{ substr($comment->user->name ?? $comment->author_name ?? 'Guest', 0, 1) }}
            </div>
            <div>
              <h6 class="mb-0">{{ $comment->user->name ?? $comment->author_name ?? 'Guest' }}</h6>
              <small class="text-muted">
                <i class="fas fa-clock"></i> {{ $comment->created_at->format('M d, Y \a\t H:i') }}
              </small>
            </div>
          </div>
          <div class="comment-content">
            {{ $comment->body }}
          </div>
        </div>
      @empty
        <div class="text-center py-4">
          <i class="fas fa-comments fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">No comments yet</h5>
          <p class="text-muted">Be the first to share your thoughts!</p>
        </div>
      @endforelse

      <!-- Comment Form -->
      <div class="comment-form mt-5">
        <h4 class="h5 mb-4" style="font-family: 'Playfair Display', serif;">
          <i class="fas fa-pencil-alt text-primary"></i> Leave a Comment
        </h4>
        
        @if(session('status'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form method="post" action="{{ route('comments.store', $post) }}" class="comment-form-fields">
          @csrf
          @guest
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Name *</label>
                <input type="text" name="author_name" class="form-control" value="{{ old('author_name') }}" required>
                @error('author_name')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="author_email" class="form-control" value="{{ old('author_email') }}">
                @error('author_email')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
            </div>
          @endguest
          
          <div class="mb-3">
            <label class="form-label">Your Comment *</label>
            <textarea name="body" rows="5" class="form-control" placeholder="Share your thoughts about this article..." required>{{ old('body') }}</textarea>
            @error('body')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>
          
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
              <i class="fas fa-info-circle"></i> Comments are moderated and will appear after approval.
            </small>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane"></i> Post Comment
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
function shareOnFacebook() {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const text = '{{ addslashes($post->title) }}';
    window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(text) + '&url=' + encodeURIComponent(window.location.href), '_blank', 'width=600,height=400');
}

function shareOnWhatsApp() {
    const text = '{{ addslashes($post->title) }} - ' + window.location.href;
    window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
}

function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Link copied to clipboard!');
    });
}

function toggleBookmark() {
    // Add bookmark functionality here
    alert('Bookmark functionality can be implemented with user accounts!');
}
</script>
@endpush
