@extends('layouts.magazine')

@section('title', 'About Us - ' . blog_title())
@section('meta_description', 'Learn more about ' . blog_title() . ' and our mission. ' . blog_description())

@section('content')
<!-- Page Header -->
<div class="page-header text-center py-5 mb-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-radius: 15px;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h1 class="display-4 mb-3" style="font-family: 'Playfair Display', serif;">
          <i class="fas fa-info-circle me-3"></i>About Us
        </h1>
        <p class="lead mb-0 opacity-90">
          Discover our story, mission, and the passion behind our content
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active">About</li>
  </ol>
</nav>

<!-- About Content -->
<div class="about-content">
  <div class="row">
    <div class="col-lg-8">
      <!-- Our Story -->
      <section class="mb-5">
        <h2 class="h3 mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
          <i class="fas fa-book-open text-secondary me-2"></i> Our Story
        </h2>
        <div class="story-content" style="font-size: 1.1rem; line-height: 1.8; color: var(--text-color);">
          <p class="lead">
            Welcome to <strong>{{ blog_title() }}</strong>, a place where stories come to life and ideas find their voice.
          </p>
          <p>
            Founded with a passion for sharing knowledge and connecting people through meaningful content, our blog has grown into a vibrant community of readers, writers, and thinkers from around the world.
          </p>
          <p>
            We believe that every story matters, every perspective has value, and every reader deserves content that informs, inspires, and entertains. Whether you're here to learn something new, find inspiration, or simply enjoy a good read, we're committed to delivering quality content that enriches your day.
          </p>
          <p>
            Our team of dedicated writers and contributors brings diverse experiences and expertise to create content that spans various topics, from technology and lifestyle to culture and personal development.
          </p>
        </div>
      </section>

      <!-- Our Mission -->
      <section class="mb-5">
        <h2 class="h3 mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
          <i class="fas fa-bullseye text-secondary me-2"></i> Our Mission
        </h2>
        <div class="mission-content p-4 bg-light rounded">
          <p class="mb-3" style="font-size: 1.1rem; line-height: 1.7;">
            <strong>To create a platform where quality content meets passionate readers.</strong>
          </p>
          <p class="mb-0">
            We strive to publish well-researched, engaging, and authentic content that adds value to our readers' lives. Our commitment is to maintain high editorial standards while fostering an inclusive community where diverse voices and perspectives are celebrated.
          </p>
        </div>
      </section>

      <!-- Our Values -->
      <section class="mb-5">
        <h2 class="h3 mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
          <i class="fas fa-heart text-secondary me-2"></i> Our Values
        </h2>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="value-card h-100 p-4 border rounded">
              <div class="value-icon mb-3">
                <i class="fas fa-check-circle fa-2x text-success"></i>
              </div>
              <h5 class="value-title mb-2">Quality First</h5>
              <p class="value-description mb-0">
                We prioritize quality over quantity, ensuring every piece of content meets our high standards.
              </p>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="value-card h-100 p-4 border rounded">
              <div class="value-icon mb-3">
                <i class="fas fa-users fa-2x text-primary"></i>
              </div>
              <h5 class="value-title mb-2">Community</h5>
              <p class="value-description mb-0">
                Building a supportive community where readers and writers can connect and grow together.
              </p>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="value-card h-100 p-4 border rounded">
              <div class="value-icon mb-3">
                <i class="fas fa-lightbulb fa-2x text-warning"></i>
              </div>
              <h5 class="value-title mb-2">Innovation</h5>
              <p class="value-description mb-0">
                Embracing new ideas and technologies to enhance the reading and writing experience.
              </p>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="value-card h-100 p-4 border rounded">
              <div class="value-icon mb-3">
                <i class="fas fa-globe fa-2x text-info"></i>
              </div>
              <h5 class="value-title mb-2">Accessibility</h5>
              <p class="value-description mb-0">
                Making quality content accessible to everyone, regardless of background or location.
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- What We Cover -->
      <section class="mb-5">
        <h2 class="h3 mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
          <i class="fas fa-list text-secondary me-2"></i> What We Cover
        </h2>
        <div class="topics-grid">
          @php
            $categories = \App\Models\Category::withCount('posts')->orderBy('posts_count', 'desc')->take(6)->get();
          @endphp
          
          @if($categories->count() > 0)
            <div class="row">
              @foreach($categories as $category)
                <div class="col-md-4 mb-3">
                  <a href="{{ route('posts.category', $category->slug) }}" class="text-decoration-none">
                    <div class="topic-card p-3 border rounded h-100 text-center hover-shadow">
                      <div class="topic-icon mb-2">
                        <i class="fas fa-folder fa-2x text-primary"></i>
                      </div>
                      <h6 class="topic-title mb-1 text-dark">{{ $category->name }}</h6>
                      <small class="text-muted">{{ $category->posts_count }} articles</small>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-muted">Categories will be displayed here as content is added.</p>
          @endif
        </div>
      </section>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
      <!-- Quick Stats -->
      <div class="sidebar-widget">
        <h6><i class="fas fa-chart-bar"></i> Quick Stats</h6>
        <div class="stats-list">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span>Total Articles:</span>
            <strong class="text-primary">{{ \App\Models\Post::published()->count() }}</strong>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span>Categories:</span>
            <strong class="text-primary">{{ \App\Models\Category::count() }}</strong>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span>Total Comments:</span>
            <strong class="text-primary">{{ \App\Models\Comment::where('is_approved', true)->count() }}</strong>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span>Active Since:</span>
            <strong class="text-primary">{{ now()->year }}</strong>
          </div>
        </div>
      </div>

      <!-- Contact CTA -->
      <div class="sidebar-widget">
        <h6><i class="fas fa-envelope"></i> Get In Touch</h6>
        <p class="small text-muted mb-3">
          Have questions or want to collaborate? We'd love to hear from you!
        </p>
        <a href="{{ route('contact') }}" class="btn btn-primary w-100">
          <i class="fas fa-paper-plane me-2"></i> Contact Us
        </a>
      </div>

      <!-- Featured Content -->
      <div class="sidebar-widget">
        <h6><i class="fas fa-star"></i> Featured Content</h6>
        @php
          $featuredPosts = \App\Models\Post::published()->latest()->take(3)->get();
        @endphp
        
        @forelse($featuredPosts as $post)
          <div class="mb-3">
            <h6 class="mb-1" style="font-size: 0.9rem;">
              <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                {{ Str::limit($post->title, 50) }}
              </a>
            </h6>
            <small class="text-muted">{{ $post->published_at?->format('M d, Y') }}</small>
          </div>
        @empty
          <p class="small text-muted">Featured content will appear here.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.hover-shadow:hover {
  box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
  transform: translateY(-2px);
  transition: all 0.3s ease;
}

.value-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
}

.topic-card:hover {
  border-color: var(--primary-color);
  background-color: #f8f9fa;
}
</style>
@endpush
