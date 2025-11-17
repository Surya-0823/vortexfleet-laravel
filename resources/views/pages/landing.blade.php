<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VortexFleet - The Operating System for Campus Mobility</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/pages/landing.css') }}">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

    {{-- Puthu Cursor Elements --}}
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    {{-- Puthu Particle Background --}}
    <div class="particles"></div>


    <nav class="navbar">
        <div class="nav-container">
            <a href="#home" class="nav-logo">
                <i class="fas fa-bus"></i>
                <span>VortexFleet</span>
            </a>
            <div class="nav-menu">
                <a href="#home" class="nav-link">Home</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#how-it-works" class="nav-link">How it Works</a>
                <a href="#faq" class="nav-link">FAQ</a>
                <div class="nav-buttons">
                    <a href="{{ url('/dashboard') }}" class="btn btn-secondary">Login</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary">Get Started</a>
                </div>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="hero-content fade-in-section">
            <h1 class="hero-title">The Operating System for Campus Mobility.</h1>
            <p class="hero-subtitle">
                VortexFleet moves your campus transport from chaotic spreadsheets and phone calls to a single, predictive, real-time platform.
            </p>
            
            <div class="hero-buttons">
                <a href="{{ url('/register') }}" class="btn btn-primary">Get Started Now</a>
                <a href="mailto:sales@vortexfleet.com" class="btn btn-secondary">Schedule a Demo</a>
            </div>

            {{-- PUTHU BENTO GRID --}}
            <div class="bento-grid" style="margin-top: 4rem;">
                <div class="bento-item stagger-item">
                    <h3><i class="fas fa-map-location-dot" style="margin-right: 8px;"></i> Unified Live Map</h3>
                    <p>See every bus, route, and stop in one real-time view.</p>
                </div>
                <div class="bento-item stagger-item">
                    <h3><i class="fas fa-mobile-screen-button" style="margin-right: 8px;"></i> Parent Peace of Mind</h3>
                    <p>A dedicated app to end 'Where is the bus?' calls forever.</p>
                </div>
                <div class="bento-item stagger-item">
                    <h3><i class="fas fa-user-shield" style="margin-right: 8px;"></i> Focused Drivers</h3>
                    <p>Optimized routes and no more distracting calls.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="modern-split-section fade-in-section">
        <div class="modern-split-grid container">
            <div class="modern-split-image">
                (Parent App Mockup)
            </div>
            <div class="modern-split-content">
                <h2>Eliminate Parent Anxiety.</h2>
                <p>
                    Stop the flood of 'Where is the bus?' calls. VortexFleet provides parents with a simple, reliable app to see live bus locations, receive accurate ETAs, and get instant notifications for delays or arrivals. It's calm, it's clear, and it's self-service.
                </p>
            </div>
        </div>
    </section>

    <section class="modern-split-section darker fade-in-section">
        <div class="modern-split-grid reverse container">
            <div class="modern-split-image">
                (Admin Dashboard Mockup)
            </div>
            <div class="modern-split-content">
                <h2>Move from Reactive to Predictive.</h2>
                <p>
                    Your admin team is too valuable to spend all morning answering calls. Our central dashboard gives you a 'mission control' view of your entire fleet. See every bus, route, and student in real-time. Manage routes, assign drivers, and handle exceptions in seconds, not hours.
                </p>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="how-it-works fade-in-section">
        <div class="container">
            <h2>How VortexFleet works in 3 simple steps</h2>
            <div class="steps-container">
                <div class="step stagger-item">
                    <div class="step-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>1. Set up your fleet</h3>
                    <p>Add your buses, driver details, routes, and stops into the VortexFleet dashboard.</p>
                </div>
                <div class="step stagger-item">
                    <div class="step-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>2. Add students & parents</h3>
                    <p>Enroll students, connect parent accounts, and assign each student to the right bus and stop.</p>
                </div>
                <div class="step stagger-item">
                    <div class="step-icon">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                    <h3>3. Go live</h3>
                    <p>Drivers start trips from the mobile app, and VortexFleet begins broadcasting live GPS updates.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="faq-section fade-in-section">
        <div class="container">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-grid">
                
                <details class="faq-item stagger-item">
                    <summary class="faq-question">What is VortexFleet?</summary>
                    <p class="faq-answer">VortexFleet is an online system that helps schools and colleges manage their buses. It shows where each bus is on a map, sends alerts to parents, and gives admins a clear view of all trips.</p>
                </details>
                
                <details class="faq-item stagger-item">
                    <summary class="faq-question">Who is this system for?</summary>
                    <p class="faq-answer">Admins, transport in‑charge staff, drivers, parents, and students can use VortexFleet. Each person has their own view and app, so they see only what they need.</p>
                </details>
                
                <details class="faq-item stagger-item">
                    <summary class="faq-question">Do we need to buy special GPS hardware?</summary>
                    <p class="faq-answer">No. You can use normal smartphones and normal internet. Drivers use a phone with GPS, admins use a computer or laptop, and parents use their own phones.</p>
                </details>
                
                <details class="faq-item stagger-item">
                    <summary class="faq-question">Is the setup process difficult?</summary>
                    <p class="faq-answer">Setup is simple. You add your buses, routes, stops, drivers, and students into the system once. Our team can guide you step by step so you do not feel lost.</p>
                </details>

                <details class="faq-item stagger-item">
                    <summary class="faq-question">How does VortexFleet help parents?</summary>
                    <p class="faq-answer">Parents do not need to call the school to ask about the bus. They can open the app, see the bus on the map, and get alerts when it is near their stop or when their child’s bus reaches school.</p>
                </details>

                <details class="faq-item stagger-item">
                    <summary class="faq-question">How can we start a trial?</summary>
                    <p class="faq-answer">You can contact the VortexFleet team, start a free trial, or ask for a demo. They will show you how the system works and help you set up a small pilot.</p>
                </details>

            </div>
        </div>
    </section>

    <section class="cta fade-in-section">
        <div class="container">
            <h2>Ready to See It in Action?</h2>
            <p>Schedule a 15-minute demo to see how VortexFleet can unify your campus transport.</p>
            <div class="cta-buttons">
                <a href="{{ url('/register') }}" class="btn btn-primary">Get Started Free</a>
                <a href="mailto:sales@vortexfleet.com" class="btn btn-secondary">Schedule Demo</a>
            </div>
        </div>
    </section>

    <footer class="footer fade-in-section">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3><i class="fas fa-bus"></i> VortexFleet</h3>
                    <p>VortexFleet is a smart bus management system for schools and colleges. It helps you track buses in real time, keep parents informed, and run transport smoothly every day.</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Product</h4>
                        <a href="#features">Features</a>
                        <a href="#how-it-works">How it Works</a>
                        <a href="#faq">FAQ</a>
                    </div>
                    <div class="footer-column">
                        <h4>Company</h4>
                        <a href="#">About Us</a>
                        <a href="#">Support</a>
                        <a href="{{ url('/dashboard') }}">Login</a>
                    </div>
                    <div class="footer-column">
                        <h4>Legal</h4>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 VortexFleet. All rights reserved.</p>
                <p>Made with ❤️ By Dhana Surya @ 2025</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/landing.js') }}"></script>

</body>
</html>