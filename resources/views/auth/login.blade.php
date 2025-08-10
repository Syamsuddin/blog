<x-guest-layout>
    <div class="text-center mb-4">
        <h1 class="form-title">Welcome Back!</h1>
        <p class="form-subtitle">Sign in to access your dashboard and manage your content</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-floating">
            <input id="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   placeholder="Enter your email"
                   required autofocus autocomplete="username">
            <label for="email">
                <i class="fas fa-envelope me-2"></i>Email Address
            </label>
            @error('email')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-floating">
            <input id="password" 
                   class="form-control @error('password') is-invalid @enderror"
                   type="password"
                   name="password"
                   placeholder="Enter your password"
                   required autocomplete="current-password">
            <label for="password">
                <i class="fas fa-lock me-2"></i>Password
            </label>
            @error('password')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="remember-me">
            <input id="remember_me" 
                   type="checkbox" 
                   name="remember"
                   class="form-check-input">
            <label for="remember_me" class="form-check-label">
                Remember me for 30 days
            </label>
        </div>

        <!-- Login Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </div>

        <!-- Demo Credentials Info -->
        <div class="alert alert-info" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-info-circle me-2 mt-1"></i>
                <div>
                    <strong>Demo Credentials:</strong><br>
                    <small>
                        Email: <code>admin@example.com</code><br>
                        Password: <code>password</code>
                    </small>
                </div>
            </div>
        </div>

        <!-- Auth Links -->
        <div class="auth-links">
            @if (Route::has('password.request'))
                <div class="mb-3">
                    <a href="{{ route('password.request') }}">
                        <i class="fas fa-key me-1"></i>Forgot your password?
                    </a>
                </div>
            @endif
            
            @if (Route::has('register'))
                <div class="mb-3">
                    <span class="text-muted">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="ms-1">
                        <i class="fas fa-user-plus me-1"></i>Create Account
                    </a>
                </div>
            @endif
            
            <div>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home me-1"></i>Back to Homepage
                </a>
                <span class="mx-2">•</span>
                <a href="{{ route('posts.index') }}">
                    <i class="fas fa-newspaper me-1"></i>Browse Blog
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
