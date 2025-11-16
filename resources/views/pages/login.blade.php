
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
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
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
</body>
</html>