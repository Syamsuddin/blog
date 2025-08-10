@extends('layouts.magazine')

@section('title', 'Contact Us - ' . blog_title())
@section('meta_description', 'Get in touch with ' . blog_title() . '. We would love to hear from you!')

@section('content')
<!-- Page Header -->
<div class="page-header text-center py-5 mb-5" style="background: linear-gradient(135deg, var(--secondary-color), var(--accent-color)); color: white; border-radius: 15px;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h1 class="display-4 mb-3" style="font-family: 'Playfair Display', serif;">
          <i class="fas fa-envelope me-3"></i>Contact Us
        </h1>
        <p class="lead mb-0 opacity-90">
          We'd love to hear from you. Send us a message and we'll respond as soon as possible.
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active">Contact</li>
  </ol>
</nav>

<!-- Contact Content -->
<div class="contact-content">
  <div class="row">
    <!-- Contact Form -->
    <div class="col-lg-8">
      <div class="contact-form-section">
        <h2 class="h3 mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
          <i class="fas fa-paper-plane text-secondary me-2"></i> Send us a Message
        </h2>

        @if(session('status'))
          <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name" class="form-label">
                <i class="fas fa-user text-primary me-1"></i> Full Name *
              </label>
              <input type="text" 
                     class="form-control @error('name') is-invalid @enderror" 
                     id="name" 
                     name="name" 
                     value="{{ old('name') }}" 
                     required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">
                <i class="fas fa-envelope text-primary me-1"></i> Email Address *
              </label>
              <input type="email" 
                     class="form-control @error('email') is-invalid @enderror" 
                     id="email" 
                     name="email" 
                     value="{{ old('email') }}" 
                     required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="mb-3">
            <label for="subject" class="form-label">
              <i class="fas fa-tag text-primary me-1"></i> Subject *
            </label>
            <input type="text" 
                   class="form-control @error('subject') is-invalid @enderror" 
                   id="subject" 
                   name="subject" 
                   value="{{ old('subject') }}" 
                   required>
            @error('subject')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-4">
            <label for="message" class="form-label">
              <i class="fas fa-comment text-primary me-1"></i> Message *
            </label>
            <textarea class="form-control @error('message') is-invalid @enderror" 
                      id="message" 
                      name="message" 
                      rows="6" 
                      placeholder="Tell us how we can help you..."
                      required>{{ old('message') }}</textarea>
            @error('message')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
              <i class="fas fa-info-circle me-1"></i> 
              Fields marked with * are required
            </small>
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fas fa-paper-plane me-2"></i> Send Message
            </button>
          </div>
        </form>
      </div>

      <!-- FAQ Section -->
      <section class="faq-section mt-5 pt-5 border-top">
        <h3 class="h4 mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
          <i class="fas fa-question-circle text-secondary me-2"></i> Frequently Asked Questions
        </h3>
        
        <div class="accordion" id="faqAccordion">
          <div class="accordion-item mb-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                How quickly do you respond to messages?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                We typically respond to all messages within 24-48 hours during business days. For urgent matters, please mention it in your subject line.
              </div>
            </div>
          </div>
          
          <div class="accordion-item mb-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                Can I contribute content to your blog?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Yes! We welcome guest contributors. Please send us your article ideas or completed pieces along with a brief bio. We'll review and get back to you.
              </div>
            </div>
          </div>
          
          <div class="accordion-item mb-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                Do you offer advertising opportunities?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                We do offer various advertising and sponsorship opportunities. Contact us with details about your business and we'll send you our media kit.
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Contact Info Sidebar -->
    <div class="col-lg-4">
      <!-- Contact Information -->
      <div class="sidebar-widget">
        <h6><i class="fas fa-info-circle"></i> Contact Information</h6>
        <div class="contact-info">
          <div class="contact-item mb-3">
            <div class="d-flex align-items-start">
              <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
              <div>
                <strong>Address</strong><br>
                <small class="text-muted">
                  123 Blog Street<br>
                  Digital City, DC 12345<br>
                  United States
                </small>
              </div>
            </div>
          </div>
          
          <div class="contact-item mb-3">
            <div class="d-flex align-items-center">
              <i class="fas fa-envelope text-primary me-3"></i>
              <div>
                <strong>Email</strong><br>
                <a href="mailto:hello@{{ str_replace(['http://', 'https://'], '', config('app.url')) }}" class="text-decoration-none">
                  hello@{{ str_replace(['http://', 'https://'], '', config('app.url')) }}
                </a>
              </div>
            </div>
          </div>
          
          <div class="contact-item mb-3">
            <div class="d-flex align-items-center">
              <i class="fas fa-phone text-primary me-3"></i>
              <div>
                <strong>Phone</strong><br>
                <a href="tel:+1234567890" class="text-decoration-none">
                  +1 (234) 567-890
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Business Hours -->
      <div class="sidebar-widget">
        <h6><i class="fas fa-clock"></i> Response Hours</h6>
        <div class="business-hours">
          <div class="d-flex justify-content-between mb-2">
            <span>Monday - Friday:</span>
            <span class="text-muted">9:00 AM - 6:00 PM</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Saturday:</span>
            <span class="text-muted">10:00 AM - 4:00 PM</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Sunday:</span>
            <span class="text-muted">Closed</span>
          </div>
          <hr>
          <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            All times are in EST. We respond to messages during business hours.
          </small>
        </div>
      </div>

      <!-- Social Media -->
      <div class="sidebar-widget">
        <h6><i class="fas fa-share-alt"></i> Follow Us</h6>
        <p class="small text-muted mb-3">
          Connect with us on social media for updates and news.
        </p>
        <div class="social-links">
          <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
            <i class="fab fa-facebook-f"></i> Facebook
          </a>
          <a href="#" class="btn btn-outline-info btn-sm me-2 mb-2">
            <i class="fab fa-twitter"></i> Twitter
          </a>
          <a href="#" class="btn btn-outline-danger btn-sm me-2 mb-2">
            <i class="fab fa-instagram"></i> Instagram
          </a>
          <a href="#" class="btn btn-outline-dark btn-sm me-2 mb-2">
            <i class="fab fa-linkedin"></i> LinkedIn
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="sidebar-widget">
        <h6><i class="fas fa-link"></i> Quick Links</h6>
        <div class="quick-links">
          <a href="{{ route('posts.index') }}" class="d-block text-decoration-none mb-2">
            <i class="fas fa-newspaper me-2"></i> Latest Articles
          </a>
          <a href="{{ route('about') }}" class="d-block text-decoration-none mb-2">
            <i class="fas fa-info-circle me-2"></i> About Us
          </a>
          @if(\App\Models\Category::count() > 0)
            <a href="{{ route('posts.index') }}" class="d-block text-decoration-none mb-2">
              <i class="fas fa-folder me-2"></i> Browse Categories
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.contact-form .form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
}

.contact-item {
  padding: 1rem 0;
  border-bottom: 1px solid #eee;
}

.contact-item:last-child {
  border-bottom: none;
}

.accordion-button:not(.collapsed) {
  background-color: var(--light-bg);
  color: var(--primary-color);
}

.social-links .btn:hover {
  transform: translateY(-2px);
  transition: all 0.3s ease;
}
</style>
@endpush
