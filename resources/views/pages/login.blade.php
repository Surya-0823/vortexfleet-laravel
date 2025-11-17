<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'Login - VortexFleet' }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @if (isset($page_css))
        <link rel="stylesheet" href="{{ asset($page_css) }}">
    @endif
</head>
<body class="auth-page">

    {{-- PUTHU CURSOR ELEMENTS (Landing Page la irunthu) --}}
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    {{-- PUTHU PARTICLE BACKGROUND (Landing Page la irunthu) --}}
    <div class="particles"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    {{-- Logo maathidalam --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <h1>Welcome Back</h1>
                </div>
                <p class="auth-subtitle">Login to access your dashboard</p>
            </div>

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            <form action="{{ url('/login') }}" method="POST" class="auth-form">
            
            @csrf
            
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" required placeholder="Enter your password" autocomplete="current-password">
                        <button type="button" class="password-toggle-btn" data-password-toggle aria-label="Show password" aria-pressed="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-eye">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-eye-off">
                                <path d="M3 3l18 18"/>
                                <path d="M10.58 10.58a2 2 0 0 0 2.83 2.83"/>
                                <path d="M16.24 16.24A10 10 0 0 1 12 19c-7 0-10-7-10-7a17.73 17.73 0 0 1 5.06-5.94"/>
                                <path d="M17.94 17.94A17.73 17.73 0 0 0 22 12s-3-7-10-7a9.59 9.59 0 0 0-3.24.56"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-auth">Login</button>

                <p class="auth-footer">
                    Don't have an account? <a href="{{ url('/register') }}">Register here</a>
                </p>
            </form>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.querySelector('[data-password-toggle]');
        const passwordInput = document.getElementById('password');

        if (!toggleBtn || !passwordInput) {
            return;
        }

        toggleBtn.addEventListener('click', function () {
            const isCurrentlyVisible = passwordInput.type === 'text';
            passwordInput.type = isCurrentlyVisible ? 'password' : 'text';
            toggleBtn.classList.toggle('is-visible', !isCurrentlyVisible);
            toggleBtn.setAttribute('aria-pressed', (!isCurrentlyVisible).toString());
            toggleBtn.setAttribute('aria-label', isCurrentlyVisible ? 'Show password' : 'Hide password');
        });
    });
</script>
{{-- PUTHU SCRIPT (Landing Page la irunthu) --}}
<script src="{{ asset('assets/js/landing.js') }}"></script>
</body>
</html>