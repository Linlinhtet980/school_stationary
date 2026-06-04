<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Supply - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/layouts/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
</head>
<body>
    <div class="auth-image">
        <div class="auth-overlay">
            <img src="{{ asset('logo.png') }}" alt="Campus Supply Logo" class="auth-logo">
            <h2 class="auth-title">CAMPUS SUPPLY</h2>
            <p class="auth-desc">Your premium destination for school and office essentials. Login to track orders and save your wishlist.</p>
        </div>
    </div>
    <div class="auth-form-container">
        <div class="auth-box">
            <h1>Welcome Back!</h1>
            <p>Please enter your details to sign in.</p>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="auth-options">
                    <label class="auth-checkbox-label"><input type="checkbox" name="remember"> Remember me</label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="btn-auth">Sign In</button>
            </form>
            <div class="auth-links">
                Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
            </div>
        </div>
    </div>
</body>
</html>
