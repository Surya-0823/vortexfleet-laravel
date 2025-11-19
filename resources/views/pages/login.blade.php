<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'Login - VortexFleet' }}</title>
    
    {{-- Fonts & Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Custom Login CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/pages/_login.css') }}">
</head>
<body>

    <div class="login-container">
        
        {{-- LEFT SIDE: Visual Content --}}
        <div class="login-visual">
            <div class="login-visual-content">
                <h1>Welcome Back to VortexFleet</h1>
                <p>Access your fleet management dashboard and continue optimizing your campus transportation system.</p>
                
                <div class="features-grid-mini">
                    <div class="feature-card-mini">
                        <div class="feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3>Live Tracking</h3>
                        <p>Monitor your entire fleet in real-time with precision GPS.</p>
                    </div>
                    <div class="feature-card-mini">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Advanced Analytics</h3>
                        <p>Gain actionable insights into fleet performance and fuel costs.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: Login Form --}}
        <div class="login-content">
            <div class="login-card">
                
                <div class="login-header">
                    <a href="{{ url('/') }}" class="login-logo">
                        Vortex<span>Fleet</span>
                    </a>
                    <h2>Welcome Back</h2>
                    <p class="login-subtitle">Login to access your dashboard</p>
                </div>

                {{-- Error Alerts --}}
                @if (session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ url('/login') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="admin@vortexfleet.com" required value="{{ old('email') }}" autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" class="toggle-password" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        {{-- Forgot Password link (Optional) --}}
                        {{-- <a href="#" class="forgot-password">Forgot Password?</a> --}}
                    </div>
                    
                    <button type="submit" class="btn">Login</button>
                </form>
                
                <div class="login-footer">
                    <p>Don't have an account? <a href="{{ url('/register') }}">Register here</a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Login JS --}}
    <script src="{{ asset('assets/js/login.js') }}"></script>

</body>
</html>