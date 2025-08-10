<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @endif
    
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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(30, 58, 138, 0.15);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        
        .auth-brand {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .auth-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }
        
        .auth-brand::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(220, 38, 38, 0.2) 0%, transparent 50%);
            animation: float 8s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .brand-tagline {
            font-size: 1.1rem;
            opacity: 0.95;
            position: relative;
            z-index: 2;
            text-shadow: 0 1px 5px rgba(0,0,0,0.1);
        }
        
        .auth-form {
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        
        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .form-subtitle {
            color: #64748b;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(248, 250, 252, 0.5);
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(var(--secondary-color-rgb), 0.25);
            background: white;
        }
        
        .form-control.is-invalid:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(var(--accent-color-rgb), 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(var(--primary-color-rgb), 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.4);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }
        
        .auth-links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }
        
        .auth-links a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .auth-links a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }
        
        .remember-me input[type="checkbox"] {
            margin-right: 0.5rem;
            transform: scale(1.2);
            accent-color: var(--secondary-color);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border-left: 4px solid #22c55e;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            color: #991b1b;
            border-left: 4px solid var(--accent-color);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1e40af;
            border-left: 4px solid var(--secondary-color);
        }
        
        .invalid-feedback {
            color: var(--accent-color);
            font-weight: 500;
        }
        
        .back-to-site {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            z-index: 10;
            background: rgba(255,255,255,0.1);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }
        
        .back-to-site:hover {
            color: var(--warning-color);
            background: rgba(255,255,255,0.2);
            transform: translateX(-3px);
        }
        
        /* Stats Cards in Brand Area */
        .stats-card {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .stats-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--warning-color);
            text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .auth-card {
                margin: 1rem;
                border-radius: 15px;
            }
            
            .auth-brand {
                padding: 2rem 1rem;
            }
            
            .brand-logo {
                font-size: 2rem;
            }
            
            .auth-form {
                padding: 2rem 1rem;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
            
            .back-to-site {
                position: static;
                display: inline-block;
                margin-bottom: 1rem;
            }
        }
        
        /* Animation for form elements */
        .form-floating {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .form-floating > .form-control {
            padding: 1rem 0.75rem 0.25rem 0.75rem;
        }
        
        .form-floating > label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1rem 0.75rem;
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
            color: #6b7280;
        }
        
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            opacity: 0.8;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: var(--secondary-color);
        }
        
        /* Gradient text for icons */
        .gradient-icon {
            background: linear-gradient(135deg, var(--secondary-color), var(--warning-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <a href="{{ route('home') }}" class="back-to-site">
            <i class="fas fa-arrow-left me-2"></i> Back to Site
        </a>
        
        <div class="auth-card">
            <div class="row g-0">
                <!-- Brand Side -->
                <div class="col-lg-5">
                    <div class="auth-brand h-100 d-flex flex-column justify-content-center">
                        <div class="brand-logo">
                            <i class="fas fa-blog me-3"></i>{{ blog_title() }}
                        </div>
                        <p class="brand-tagline">
                            {{ blog_description() }}
                        </p>
                        <div class="mt-4">
                            <div class="d-flex justify-content-center">
                                <div class="me-4 text-center stats-card">
                                    <div class="stats-number">{{ \App\Models\Post::published()->count() }}+</div>
                                    <small>Articles</small>
                                </div>
                                <div class="me-4 text-center stats-card">
                                    <div class="stats-number">{{ \App\Models\Category::count() }}+</div>
                                    <small>Categories</small>
                                </div>
                                <div class="text-center stats-card">
                                    <div class="stats-number">{{ \App\Models\Comment::where('is_approved', true)->count() }}+</div>
                                    <small>Comments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Side -->
                <div class="col-lg-7">
                    <div class="auth-form">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
