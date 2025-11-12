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
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="btn-auth">Login</button>

                <p class="auth-footer">
                    Don't have an account? <a href="{{ url('/register') }}">Register here</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>