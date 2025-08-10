<x-guest-layout>
    <div class="text-center mb-4">
        <h1 class="form-title">Join Our Community!</h1>
        <p class="form-subtitle">Create your account and start sharing your stories with the world</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-floating">
            <input id="name" 
                   class="form-control @error('name') is-invalid @enderror" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   placeholder="Enter your full name"
                   required autofocus autocomplete="name">
            <label for="name">
                <i class="fas fa-user me-2"></i>Full Name
            </label>
            @error('name')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-floating">
            <input id="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   placeholder="Enter your email"
                   required autocomplete="username">
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
                   placeholder="Create a password"
                   required autocomplete="new-password">
            <label for="password">
                <i class="fas fa-lock me-2"></i>Password
            </label>
            @error('password')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-floating">
            <input id="password_confirmation" 
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   type="password"
                   name="password_confirmation" 
                   placeholder="Confirm your password"
                   required autocomplete="new-password">
            <label for="password_confirmation">
                <i class="fas fa-lock me-2"></i>Confirm Password
            </label>
            @error('password_confirmation')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Terms Agreement -->
        <div class="remember-me">
            <input id="terms" 
                   type="checkbox" 
                   required
                   class="form-check-input">
            <label for="terms" class="form-check-label">
                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
            </label>
        </div>

        <!-- Register Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </div>

        <!-- Features Info -->
        <div class="alert" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); border: none; color: #1565c0;">
            <div class="d-flex align-items-start">
                <i class="fas fa-star me-2 mt-1"></i>
                <div>
                    <strong>What you'll get:</strong><br>
                    <small>
                        • Access to premium content<br>
                        • Ability to comment and engage<br>
                        • Personalized reading experience<br>
                        • Newsletter and updates
                    </small>
                </div>
            </div>
        </div>

        <!-- Auth Links -->
        <div class="auth-links">
            <div class="mb-3">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="ms-1">
                    <i class="fas fa-sign-in-alt me-1"></i>Sign In
                </a>
            </div>
            
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
    </form>
</x-guest-layout>
