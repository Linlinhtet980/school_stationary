<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Supply - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/customer/views/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}?v={{ time() }}">
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
            <!-- Mobile-only Logo Header -->
            <div class="auth-mobile-header">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="auth-mobile-logo">
                <h2>CAMPUS SUPPLY</h2>
            </div>

            <h1>Welcome Back!</h1>
            <p>Please enter your details to sign in.</p>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" id="login-email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus autocomplete="off">
                    <span class="text-danger real-time-error" id="error-email" style="display: none; font-size: 0.82rem; margin-top: 0.3rem; font-weight: 600;"></span>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="login-password" placeholder="••••••••" required>
                        <i class="fa-regular fa-eye toggle-password-btn" id="toggle-password"></i>
                    </div>
                    <span class="text-danger real-time-error" id="error-password" style="display: none; font-size: 0.82rem; margin-top: 0.3rem; font-weight: 600;"></span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('login-email');
            const passwordInput = document.getElementById('login-password');
            const submitBtn = document.querySelector('.btn-auth');
            
            // Toggle Password Visibility
            const togglePasswordBtn = document.getElementById('toggle-password');
            togglePasswordBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    togglePasswordBtn.classList.remove('fa-eye');
                    togglePasswordBtn.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    togglePasswordBtn.classList.remove('fa-eye-slash');
                    togglePasswordBtn.classList.add('fa-eye');
                }
            });
            
            let isEmailValid = false;
            let isPasswordValid = false;

            function showError(input, errorEl, message) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
                input.style.borderColor = '#dc3545';
                input.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
            }

            function showSuccess(input, errorEl) {
                errorEl.textContent = '';
                errorEl.style.display = 'none';
                input.style.borderColor = '#28a745';
                input.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
            }
            
            function checkFormValidity() {
                if (isEmailValid && isPasswordValid) {
                    submitBtn.removeAttribute('disabled');
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                } else {
                    submitBtn.setAttribute('disabled', 'true');
                    submitBtn.style.opacity = '0.6';
                    submitBtn.style.cursor = 'not-allowed';
                }
            }

            checkFormValidity();

            // 1. Email Validation
            emailInput.addEventListener('input', function() {
                const val = emailInput.value.trim();
                const errorEl = document.getElementById('error-email');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (val.length === 0) {
                    showError(emailInput, errorEl, 'Email address is required.');
                    isEmailValid = false;
                } else if (!emailRegex.test(val)) {
                    showError(emailInput, errorEl, 'Please enter a valid email address.');
                    isEmailValid = false;
                } else {
                    showSuccess(emailInput, errorEl);
                    isEmailValid = true;
                }
                checkFormValidity();
            });

            // 2. Password Validation
            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;
                const errorEl = document.getElementById('error-password');
                
                if (val.length === 0) {
                    showError(passwordInput, errorEl, 'Password is required.');
                    isPasswordValid = false;
                } else {
                    showSuccess(passwordInput, errorEl);
                    isPasswordValid = true;
                }
                checkFormValidity();
            });
        });
    </script>
</body>
</html>
