<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Supply - Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/layouts/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
</head>
<body class="register-page">
    <div class="auth-image">
        <div class="auth-overlay">
            <img src="{{ asset('logo.png') }}" alt="Campus Supply Logo" class="auth-logo">
            <h2 class="auth-title">JOIN US</h2>
            <p class="auth-desc">Create an account to checkout faster, save multiple shipping addresses, and access your order history.</p>
        </div>
    </div>
    <div class="auth-form-container">
        <div class="auth-box">
            <h1>Create Account</h1>
            <p>Join Campus Supply today.</p>
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="09xxxxxxxxx" required>
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password (Min 8 characters)</label>
                    <input type="password" name="password" placeholder="••••••••" minlength="8" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn-auth">Sign Up</button>
            </form>
            <div class="auth-links">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>
