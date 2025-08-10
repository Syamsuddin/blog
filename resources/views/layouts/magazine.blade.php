<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', blog_title())</title>
    <meta name="description" content="@yield('meta_description', blog_description())">
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e3a8a;      /* Navy Blue */
            --secondary-color: #3b82f6;     /* Blue */
            --accent-color: #dc2626;        /* Red */
            --warning-color: #f59e0b;       /* Yellow/Amber */
            --text-color: #1e293b;          /* Dark Slate */
            --light-bg: #f8fafc;            /* Very light blue-gray */
            --border-color: #e2e8f0;        /* Light blue-gray */
            --primary-color-rgb: 30, 58, 138;
            --secondary-color-rgb: 59, 130, 246;
            --accent-color-rgb: 220, 38, 38;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .magazine-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .site-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-decoration: none;
            color: white;
        }
        
        .site-tagline {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .main-nav {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .main-nav .navbar-nav .nav-link {
            color: var(--text-color);
            font-weight: 500;
            padding: 1rem 1.5rem;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .main-nav .navbar-nav .nav-link:hover,
        .main-nav .navbar-nav .nav-link.active {
            color: var(--secondary-color);
            border-bottom-color: var(--secondary-color);
        }
        
        .sidebar {
            background: var(--light-bg);
            min-height: calc(100vh - 200px);
            padding: 2rem 1.5rem;
            border-right: 1px solid var(--border-color);
        }
        
        .sidebar-widget {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }
        
        .sidebar-widget h5 {
            font-family: 'Playfair Display', serif;
            color: var(--primary-color);
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .content-area {
            padding: 2rem;
            background: white;
        }
        
        .article-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .article-card .card-body {
            padding: 2rem !important;
        }
        
        .article-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .article-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .article-meta {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .article-meta i {
            margin-right: 0.5rem;
            color: var(--secondary-color);
        }
        
        .article-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .article-title a {
            color: var(--text-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .article-title a:hover {
            color: var(--secondary-color);
        }
        
        .article-excerpt {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .btn-read-more {
            background: var(--secondary-color);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-read-more:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }
        
        .article-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }
        
        .article-tags .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .article-tags .badge:hover {
            background: var(--secondary-color) !important;
            color: white !important;
            border-color: var(--secondary-color);
        }
        
        .magazine-footer {
            background: var(--primary-color);
            color: white;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }
        
        .footer-widget h6 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #ecf0f1;
        }
        
        .footer-widget ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-widget ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-widget ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-widget ul li a:hover {
            color: white;
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: var(--secondary-color);
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--warning-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .badge {
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .badge.bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .badge.bg-secondary {
            background-color: var(--secondary-color) !important;
        }
        
        .badge.bg-warning {
            background-color: var(--warning-color) !important;
            color: white !important;
        }
        
        .badge.bg-danger {
            background-color: var(--accent-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-secondary {
            color: var(--secondary-color) !important;
        }
        
        .text-warning {
            color: var(--warning-color) !important;
        }
        
        .text-danger {
            color: var(--accent-color) !important;
        }
        
        .copyright {
            border-top: 1px solid #34495e;
            padding-top: 2rem;
            margin-top: 2rem;
            text-align: center;
            color: #95a5a6;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .site-title {
                font-size: 1.8rem;
            }
            
            .sidebar {
                padding: 1rem;
            }
            
            .content-area {
                padding: 1rem;
            }
            
            .article-card img {
                height: 150px;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
    </style>
    
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/sass/app.scss','resources/js/app.js'])
    @endif
    @stack('head')
</head>
<body>
    <!-- Header -->
    <header class="magazine-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <a href="{{ url('/') }}" class="site-title">{{ blog_title() }}</a>
                    <p class="site-tagline">{{ blog_description() }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end">
                        @auth
                            <div class="dropdown">
                                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                    @can('admin')
                                        <li><a class="dropdown-item" href="{{ route('admin.posts.index') }}"><i class="fas fa-edit"></i> Manage Posts</a></li>
                                    @endcan
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="dropdown-item" type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light me-2">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-light">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg main-nav">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                            <i class="fas fa-newspaper"></i> Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <form class="d-flex" action="{{ route('posts.index') }}" method="GET">
                            <input class="form-control me-2" type="search" name="q" placeholder="Search articles..." value="{{ request('q') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-lg-3 sidebar">
                @yield('sidebar')
                @if(!View::hasSection('sidebar'))
                    @include('layouts.partials.default-sidebar')
                @endif
            </aside>

            <!-- Main Content Area -->
            <main class="col-lg-9 content-area">
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="magazine-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-widget">
                        <h6>About {{ blog_title() }}</h6>
                        <p>{{ blog_description() }}</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 mb-4">
                    <div class="footer-widget">
                        <h6>Quick Links</h6>
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('posts.index') }}">Blog</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 mb-4">
                    <div class="footer-widget">
                        <h6>Categories</h6>
                        <ul>
                            @php
                                $footerCategories = \App\Models\Category::withCount('posts')->orderBy('posts_count', 'desc')->take(5)->get();
                            @endphp
                            @foreach($footerCategories as $category)
                                <li><a href="{{ route('posts.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="footer-widget">
                        <h6>Newsletter</h6>
                        <p>Subscribe to get the latest posts delivered directly to your inbox.</p>
                        <form class="newsletter-form">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your email address">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; {{ date('Y') }} {{ blog_title() }}. All rights reserved. Built with ❤️ using Laravel & Bootstrap.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
