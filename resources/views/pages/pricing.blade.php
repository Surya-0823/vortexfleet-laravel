<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'Pricing - VortexFleet' }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @if (isset($page_css))
        <link rel="stylesheet" href="{{ asset($page_css) }}">
    @endif
</head>
<body class="pricing-page">
    <header class="landing-header">
        <div class="container">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bus">
                    <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
                </svg>
                <span>VortexFleet</span>
            </div>
            <nav class="landing-nav">
                <a href="{{ url('/landing') }}#hero">Home</a>
                <a href="{{ url('/landing') }}#features">Features</a>
                <a href="{{ url('/pricing') }}">Pricing</a>
            </nav>
            <div class="landing-auth">
                <a href="{{ url('/login') }}" class="btn btn-ghost-light">Login</a>
                <a href="{{ url('/register') }}" class="btn btn-primary-light">Register</a>
            </div>
        </div>
    </header>

    <main>
        <section class="pricing-hero">
            <div class="container">
                <h1 class="pricing-title">Simple, Transparent Pricing</h1>
                <p class="pricing-subtitle">Choose the plan that's right for your institution. No hidden fees.</p>
            </div>
        </section>

        <section class="pricing-table-section">
            <div class="container">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h2>School/College Plan</h2>
                        <p>All features included. Pricing based on usage.</p>
                    </div>
                    <div class="pricing-rate">
                        <span class="price">₹1.75</span>
                        <span class="per">/day /per student</span>
                    </div>
                    <div class="pricing-billing">
                        <p>Billed monthly or yearly (Get 1 month free with yearly billing).</p>
                    </div>
                    <ul class="pricing-features">
                        <li>Real-time Bus Tracking</li>
                        <li>Driver App Access</li>
                        <li>Student App Access</li>
                        <li>Unlimited Drivers</li>
                        <li>Unlimited Buses</li>
                        <li>Route Management</li>
                        <li>Student Management</li>
                        <li>OTP Verification</li>
                        <li>Email & Chat Support</li>
                    </ul>
                    <div class="pricing-cta">
                        <a href="{{ url('/register') }}" class="btn btn-primary btn-lg btn-full">Get Started</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="landing-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} VortexFleet. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>