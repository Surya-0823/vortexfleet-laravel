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
                <a href="#pricing" class="nav-link">Pricing</a>
                <a href="#contact" class="nav-link">Contact</a>
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

    {{-- ======================================= --}}
    {{-- FEATURES SECTION - UPGRADE PANNAPATTATHU --}}
    {{-- ======================================= --}}
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

    {{-- PUTHU FEATURE 1 --}}
    <section class="modern-split-section fade-in-section">
        <div class="modern-split-grid container">
            <div class="modern-split-image">
                (Route Optimization Mockup)
            </div>
            <div class="modern-split-content">
                <h2>Intelligent Route Optimization.</h2>
                <p>
                    Stop wasting fuel and time on inefficient routes. VortexFleet's AI-powered optimizer analyzes your stops, student locations, and traffic patterns to build the fastest, safest, and most cost-effective routes for your entire fleet. Reduce fuel costs and complaints in one click.
                </p>
            </div>
        </div>
    </section>

    {{-- PUTHU FEATURE 2 --}}
    <section class="modern-split-section darker fade-in-section">
        <div class="modern-split-grid reverse container">
            <div class="modern-split-image">
                (Driver App Attendance Mockup)
            </div>
            <div class="modern-split-content">
                <h2>Digital Attendance & Security.</h2>
                <p>
                    Ensure every student is accounted for. Drivers can mark attendance directly from their app as students board the bus. This sends an instant 'Boarded' notification to parents and logs the data for admins, giving you a complete digital record for safety and compliance.
                </p>
            </div>
        </div>
    </section>
    {{-- FEATURES SECTION MUDINTHATHU --}}


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

    <section id="pricing" class="pricing-section fade-in-section">
        <div class="container">
            <h2>Simple, Transparent Pricing</h2>
            <div class="pricing-grid">
                
                <div class="pricing-card stagger-item">
                    <h3>Standard</h3>
                    <div class="price">₹7,999</div>
                    <div class="price-per">/ per month / per campus</div>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Up to 10 Buses</li>
                        <li><i class="fas fa-check-circle"></i> Admin Dashboard</li>
                        <li><i class="fas fa-check-circle"></i> Parent App (Android & iOS)</li>
                        <li><i class="fas fa-check-circle"></i> Driver App</li>
                        <li><i class="fas fa-check-circle"></i> Email Support</li>
                    </ul>
                    <a href="{{ url('/register') }}" class="btn btn-secondary">Get Started</a>
                </div>
                
                <div class="pricing-card popular stagger-item">
                    <h3>Professional</h3>
                    <div class="price">₹14,999</div>
                    <div class="price-per">/ per month / per campus</div>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Up to 25 Buses</li>
                        <li><i class="fas fa-check-circle"></i> Everything in Standard</li>
                        <li><i class="fas fa-check-circle"></i> Route Optimization</li>
                        <li><i class="fas fa-check-circle"></i> Student Attendance</li>
                        <li><i class="fas fa-check-circle"></i> Priority Phone Support</li>
                    </ul>
                    <a href="{{ url('/register') }}" class="btn btn-primary">Choose Professional</a>
                </div>

                <div class="pricing-card stagger-item">
                    <h3>Enterprise</h3>
                    <div class="price">Custom</div>
                    <div class="price-per">For large institutions</div>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Unlimited Buses</li>
                        <li><i class="fas fa-check-circle"></i> Everything in Professional</li>
                        <li><i class="fas fa-check-circle"></i> Dedicated Account Manager</li>
                        <li><i class="fas fa-check-circle"></i> On-site Setup</li>
                        <li><i class="fas fa-check-circle"></i> Custom Integrations</li>
                    </ul>
                    <a href="#contact" class="btn btn-secondary">Contact Sales</a>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================================= --}}
    {{-- CONTACT SECTION (Upgraded V2) --}}
    {{-- ======================================= --}}
    <section id="contact" class="contact-section fade-in-section">
        <div class="container">
            <h2>Get in Touch</h2>
            <div class="contact-grid">
                
                {{-- Left Side: Info --}}
                <div class="contact-info stagger-item">
                    <h3>Let's talk about your fleet.</h3>
                    <p>
                        Fill out the form or email us directly. We'll get back to you within 24 hours
                        to schedule a free, no-obligation demo.
                    </p>
                    {{-- PUTHU BOX STYLE intha UL la apply aagum --}}
                    <ul class="contact-details">
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>sales@vortexfleet.com</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>+91 98765 43210</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Chennai, Tamil Nadu, India</span>
                        </li>
                    </ul>
                </div>

                {{-- Right Side: Form --}}
                <div class="contact-form stagger-item">
                    <form action="mailto:sales@vortexfleet.com" method="post" enctype="text/plain">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="institution">Institution Name</label>
                            <input type="text" id="institution" name="institution" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================================= --}}
    {{-- FAQ SECTION - UPGRADE PANNAPATTATHU --}}
    {{-- ======================================= --}}
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

                {{-- PUTHU FAQ 1 --}}
                <details class="faq-item stagger-item">
                    <summary class="faq-question">Is my data safe and secure?</summary>
                    <p class="faq-answer">Yes. We use industry-standard encryption for all data. Parent and student information is kept private and secure, accessible only by authorized admin accounts you control.</p>
                </details>

                {{-- PUTHU FAQ 2 --}}
                <details class="faq-item stagger-item">
                    <summary class="faq-question">What happens if a bus breaks down?</summary>
                    <p class="faq-answer">Admins will see the bus stop moving on the live map. You can immediately send a bulk notification to all parents on that route and dispatch a replacement bus to the location.</p>
                </details>

                {{-- PUTHU FAQ 3 --}}
                <details class="faq-item stagger-item">
                    <summary class="faq-question">Can this work in areas with poor internet?</summary>
                    <p class="faq-answer">Yes. The driver's app is designed to work offline. It stores the GPS data locally and syncs it to the server as soon as it reconnects to the internet. You won't lose tracking data.</p>
                </details>

                {{-- PUTHU FAQ 4 --}}
                <details class="faq-item stagger-item">
                    <summary class="faq-question">How is pricing calculated?</summary>
                    <p class="faq-answer">Our pricing is simple. We charge a flat monthly fee based on the number of buses you operate. Please see our pricing section or contact us for a custom quote for large fleets.</p>
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
                        <a href="#pricing">Pricing</a>
                        <a href="#contact">Contact</a>
                        <a href="#faq">FAQ</a>
                    </div>
                    <div class="footer-column">
                        <h4>Company</h4>
                        <a href="#">About Us</a>
                        <a href="#">Support</a>
                        <a href="#contact">Contact</a>
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