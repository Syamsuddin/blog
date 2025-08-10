<x-guest-layout>
    <div class="text-center mb-4">
        <h1 class="form-title">Reset Password</h1>
        <p class="form-subtitle">
            Forgot your password? No problem. Just enter your email address and we'll send you a password reset link.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-floating">
            <input id="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   placeholder="Enter your email"
                   required autofocus>
            <label for="email">
                <i class="fas fa-envelope me-2"></i>Email Address
            </label>
            @error('email')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Reset Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane me-2"></i>Send Reset Link
            </button>
        </div>

        <!-- Help Info -->
        <div class="alert alert-info" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-info-circle me-2 mt-1"></i>
                <div>
                    <strong>Need help?</strong><br>
                    <small>
                        If you don't receive the email within a few minutes, please check your spam folder. 
                        Make sure you're using the same email address you registered with.
                    </small>
                </div>
            </div>
        </div>

        <!-- Auth Links -->
        <div class="auth-links">
            <div class="mb-3">
                <span class="text-muted">Remember your password?</span>
                <a href="{{ route('login') }}" class="ms-1">
                    <i class="fas fa-sign-in-alt me-1"></i>Back to Login
                </a>
            </div>
            
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
