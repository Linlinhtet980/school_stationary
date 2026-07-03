<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Supply - Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/customer/views/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}?v={{ time() }}">
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
            <!-- Mobile-only Logo Header -->
            <div class="auth-mobile-header">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="auth-mobile-logo">
                <h2>CAMPUS SUPPLY</h2>
            </div>

            <h1>Create Account</h1>
            <p>Join Campus Supply today.</p>
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" id="reg-name" value="{{ old('name') }}" placeholder="John Doe" required autofocus autocomplete="off">
                    <span class="text-danger real-time-error" id="error-name" style="display: none; font-size: 0.82rem; margin-top: 0.3rem; font-weight: 600;"></span>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Phone Number (e.g, 09xxxxxxxxx)</label>
                    <input type="tel" name="phone" id="reg-phone" value="{{ old('phone') }}" placeholder="09xxxxxxxxx" required autocomplete="off">
                    <span class="text-danger real-time-error" id="error-phone" style="display: none; font-size: 0.82rem; margin-top: 0.3rem; font-weight: 600;"></span>
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email Address (e.g, john@example.com)</label>
                    <input type="email" name="email" id="reg-email" value="{{ old('email') }}" placeholder="john@example.com" required autocomplete="off">
                    <span class="text-danger real-time-error" id="error-email" style="display: none; font-size: 0.82rem; margin-top: 0.3rem; font-weight: 600;"></span>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password (Min 8 characters)</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="reg-password" placeholder="••••••••" minlength="8" required>
                        <i class="fa-regular fa-eye toggle-password-btn" id="toggle-password"></i>
                    </div>
                    <span class="text-danger real-time-error" id="error-password" style="display: none; font-size: 0.82rem; margin-top: 0.3rem; font-weight: 600;"></span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('reg-name');
            const phoneInput = document.getElementById('reg-phone');
            const emailInput = document.getElementById('reg-email');
            const passwordInput = document.getElementById('reg-password');
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
            
            let isNameValid = false;
            let isPhoneValid = false;
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
                if (isNameValid && isPhoneValid && isEmailValid && isPasswordValid) {
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

            // 1. Full Name Validation
            nameInput.addEventListener('input', function() {
                const val = nameInput.value.trim();
                const errorEl = document.getElementById('error-name');
                
                if (val.length === 0) {
                    showError(nameInput, errorEl, 'Full name is required.');
                    isNameValid = false;
                } else if (/[0-9]/.test(val)) {
                    showError(nameInput, errorEl, 'Name cannot contain numbers.');
                    isNameValid = false;
                } else if (/[!@#$%^&*(),.?":{}|<>]/.test(val)) {
                    showError(nameInput, errorEl, 'Name cannot contain special characters.');
                    isNameValid = false;
                } else {
                    showSuccess(nameInput, errorEl);
                    isNameValid = true;
                }
                checkFormValidity();
            });

            // 2. Phone Number Validation
            phoneInput.addEventListener('input', function() {
                const val = phoneInput.value.trim();
                const errorEl = document.getElementById('error-phone');
                
                if (val.length === 0) {
                    showError(phoneInput, errorEl, 'Phone number is required.');
                    isPhoneValid = false;
                } else if (!val.startsWith('09')) {
                    showError(phoneInput, errorEl, 'Phone number must start with 09.');
                    isPhoneValid = false;
                } else if (!/^09[0-9]*$/.test(val)) {
                    showError(phoneInput, errorEl, 'Phone number can only contain digits.');
                    isPhoneValid = false;
                } else if (val.length < 9 || val.length > 11) {
                    showError(phoneInput, errorEl, 'Phone number must be between 9 and 11 digits.');
                    isPhoneValid = false;
                } else {
                    showSuccess(phoneInput, errorEl);
                    isPhoneValid = true;
                }
                checkFormValidity();
            });

            // 3. Email Validation
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
                } else if (!val.toLowerCase().endsWith('@gmail.com')) {
                    showError(emailInput, errorEl, 'Email address must be a Gmail account (@gmail.com).');
                    isEmailValid = false;
                } else {
                    showSuccess(emailInput, errorEl);
                    isEmailValid = true;
                }
                checkFormValidity();
            });

            // 4. Password Validation
            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;
                const errorEl = document.getElementById('error-password');
                
                if (val.length < 8) {
                    showError(passwordInput, errorEl, 'Password must be at least 8 characters long (current: ' + val.length + ').');
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
