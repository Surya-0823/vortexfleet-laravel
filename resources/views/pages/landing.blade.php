<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'VortexFleet - Bus Management' }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @if (isset($page_css))
        <link rel="stylesheet" href="{{ asset($page_css) }}">
    @endif
</head>
<body class="landing-page">
    <header class="landing-header">
        <div class="container landing-header__content">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bus">
                    <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
                </svg>
                <span>VortexFleet</span>
            </div>
            <nav class="landing-nav">
                <a href="#hero">Home</a>
                <a href="#features">Features</a>
                <a href="#stats">Stats</a>
                <a href="{{ url('/pricing') }}">Pricing</a>
            </nav>
            <div class="landing-auth">
                <a href="{{ url('/login') }}" class="btn btn-ghost-light">Login</a>
                <a href="{{ url('/register') }}" class="btn btn-primary-light">Register</a>
            </div>
        </div>
    </header>

    <main>
        <section id="hero" class="hero-section" style="background-image: url('{{ asset('assets/images/bg.jpg') }}');">
            <div class="container hero-content">
                <h1 class="hero-title">Smarter Bus Management is Here</h1>
                <p class="hero-subtitle">Real-time tracking, student management, and route optimization in one simple platform.</p>
                <div class="hero-cta">
                    <a href="{{ url('/register') }}" class="btn btn-primary-light btn-lg">Get Started for Free</a>
                </div>
            </div>
        </section>

        <section id="features" class="features-section">
            <div class="container">
                <h2 class="section-title">Everything you need. Nothing you don't.</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <h3>Real-time Tracking</h3>
                        <p>Monitor all your buses live on a single map. Parents and students can track their assigned bus in real-time.</p>
                    </div>
                    <div class="feature-card">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20v2H6.5a2.5 2.5 0 0 1 0-5H20v2H6.5a2.5 2.5 0 0 1 0-5H20v2H6.5A2.5 2.5 0 0 1 4 9.5V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1.5"/></svg>
                        <h3>Student Management</h3>
                        <p>Securely manage student profiles, assign them to routes, and handle verification via OTP for app access.</p>
                    </div>
                    <div class="feature-card">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
                        <h3>Driver Profiles</h3>
                        <p>Manage driver details, assign them to buses, and allow them to update their location via a dedicated driver app.</p>
                    </div>
                </div>
            </div>
        </section>

        @if (isset($stats))
        <section id="stats" class="stats-section">
            <div class="container stats-grid">
                <div class="stat-card">
                    <span class="stat-value">{{ $stats['drivers'] ?? 0 }}</span>
                    <span class="stat-label">Drivers Managed</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">{{ $stats['buses'] ?? 0 }}</span>
                    <span class="stat-label">Buses Tracked</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">{{ $stats['students'] ?? 0 }}</span>
                    <span class="stat-label">Students Secured</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">{{ $stats['routes'] ?? 0 }}</span>
                    <span class="stat-label">Routes Optimized</span>
                </div>
            </div>
        </section>
        @endif
    </main>

    <footer class="landing-footer">
        <div class="container footer-content">
            <p>&copy; {{ date('Y') }} VortexFleet. All rights reserved.</p>
        </div>
    </footer>
    
    @if (isset($page_js))
        <script src="{{ asset($page_js) }}"></script>
    @endif
</body>
</html>