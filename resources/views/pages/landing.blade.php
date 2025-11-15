<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VortexFleet - Smart Bus Management</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/pages/landing.css') }}">
    
    </head>
<body class="landing-page">

    <nav class="landing-nav">
        <div class="container nav-container">
            <div class="nav-left">
                <a href="#hero" class="logo-link">
                    <img src="{{ asset('assets/icons/bus.svg') }}" alt="Bus" class="logo-icon" />
                    <h1 class="logo-text">VortexFleet</h1>
                </a>
            </div>

            <input type="checkbox" id="mobile-menu-toggle" class="mobile-menu-checkbox">
            <label for="mobile-menu-toggle" class="mobile-menu-button">
                <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="6" x2="20" y2="6"></line><line x1="4" y1="18" x2="20" y2="18"></line></svg>
                <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </label>

            <div class="desktop-nav">
                <a href="#hero" class="nav-link">Home</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#pricing" class="nav-link">Pricing</a>
                <a href="#contact" class="nav-link">Contact</a>
                <div class="nav-buttons">
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline">Login</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary btn-glow">Sign Up</a>
                </div>
            </div>

            <div class="mobile-nav">
                <a href="#hero" class="nav-link-mobile">Home</a>
                <a href="#features" class="nav-link-mobile">Features</a>
                <a href="#pricing" class="nav-link-mobile">Pricing</a>
                <a href="#contact" class="nav-link-mobile">Contact</a>
                <div class="nav-buttons-mobile">
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline btn-full">Login</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary btn-glow btn-full">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <section id="hero" class="hero-section">
        <div class="container hero-content">
            <h1 class="hero-title">
                Smart Bus Management
                <span class="hero-subtitle">
                    Made Simple
                </span>
            </h1>
            <p class="hero-description">
                Complete school bus tracking and management system with real-time location updates, 
                route optimization, and secure student verification powered by OpenStreetMap
            </p>
            <div class="hero-buttons">
                <a href="{{ url('/dashboard') }}" class="btn btn-lg btn-primary btn-glow">
                    Get Started
                </a>
                <a href="{{ url('/dashboard') }}" class="btn btn-lg btn-outline">
                    View Demo
                </a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-visual-blur"></div>
            <div class="card card-glow hero-stats-card">
                <div class="card-content">
                    <div class="hero-stats-grid">
                        <div class="stat-item">
                            <div class="stat-value stat-primary">45+</div>
                            <div class="stat-label">Active Buses</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value stat-secondary">120+</div>
                            <div class="stat-label">Drivers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value stat-primary">2.5K+</div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value stat-secondary">85+</div>
                            <div class="stat-label">Routes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Powerful Features</h2>
                <p class="section-subtitle">
                    Everything you need to manage your school bus fleet efficiently
                </p>
            </div>
            <div class="features-grid">
                <div class="card card-glow feature-card">
                    <div class="card-header">
                        <div class="feature-icon">
                            <img src="{{ asset('assets/icons/bus.svg') }}" alt="Bus" class="icon-8" />
                        </div>
                        <h3 class="card-title">Real-Time Bus Tracking</h3>
                        <p class="card-description">Live GPS tracking with instant updates. Students and parents can see the bus location on an interactive map. Reduces waiting time and eliminates guesswork.</p>
                    </div>
                </div>
                <div class="card card-glow feature-card">
                    <div class="card-header">
                        <div class="feature-icon">
                            <img src="{{ asset('assets/icons/users.svg') }}" alt="Users" class="icon-8" />
                        </div>
                        <h3 class="card-title">Student App</h3>
                        <p class="card-description">Real-time bus location, accurate ETA and route visibility, alerts for delays, arrivals, and changes. Secure login for students/parents.</p>
                    </div>
                </div>
                <div class="card card-glow feature-card">
                    <div class="card-header">
                        <div class="feature-icon">
                            <img src="{{ asset('assets/icons/map-pin.svg') }}" alt="MapPin" class="icon-8" />
                        </div>
                        <h3 class="card-title">Driver App</h3>
                        <p class="card-description">Easy-to-use interface, sends continuous GPS updates, route assignments and trip details. Admin-controlled login credentials.</p>
                    </div>
                </div>
                <div class="card card-glow feature-card">
                    <div class="card-header">
                        <div class="feature-icon">
                             <img src="{{ asset('assets/icons/shield.svg') }}" alt="Shield" class="icon-8" />
                        </div>
                        <h3 class="card-title">Admin Dashboard</h3>
                        <p class="card-description">A powerful central system with full control over driver management, student management, bus management, route management, and live map view.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="how-it-works-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">
                    Get started in three simple steps
                </p>
            </div>
            <div class="how-it-works-grid">
                <div class="how-it-works-item">
                    <div class="how-it-works-step">01</div>
                    <h3 class="how-it-works-title">Add Your Fleet</h3>
                    <p class="how-it-works-desc">Register buses, drivers, and create routes</p>
                </div>
                <div class="how-it-works-item-connector"></div>
                <div class="how-it-works-item">
                    <div class="how-it-works-step">02</div>
                    <h3 class="how-it-works-title">Enroll Students</h3>
                    <p class="how-it-works-desc">Secure OTP verification for student registration</p>
                </div>
                <div class="how-it-works-item-connector"></div>
                <div class="how-it-works-item">
                    <div class="how-it-works-step">03</div>
                    <h3 class="how-it-works-title">Track & Manage</h3>
                    <p class="how-it-works-desc">Monitor real-time locations and manage operations</p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="pricing-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Simple, Transparent Pricing</h2>
                <p class="section-subtitle">
                    Choose the perfect plan for your institution
                </p>
            </div>
            <div class="pricing-grid">
                <div class="card card-glow pricing-card">
                    <div class="card-header pricing-header">
                        <h3 class="card-title pricing-title">Starter</h3>
                        <p class="card-description">Perfect for small schools</p>
                        <div class="pricing-price">
                            <span class="price-value">₹4,999</span>
                            <span class="price-period">/month</span>
                        </div>
                    </div>
                    <div class="card-content">
                        <ul class="pricing-features">
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Up to 5 buses</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Up to 200 students</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Basic route management</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Real-time tracking</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Email support</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>OpenStreetMap integration</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Mobile app access</span>
                            </li>
                        </ul>
                        <a href="{{ url('/register') }}" class="btn btn-outline btn-full">Get Started</a>
                    </div>
                </div>

                <div class="card card-glow pricing-card popular">
                    <div class="popular-badge">Most Popular</div>
                    <div class="card-header pricing-header">
                        <h3 class="card-title pricing-title">Professional</h3>
                        <p class="card-description">Ideal for medium colleges</p>
                        <div class="pricing-price">
                            <span class="price-value">₹9,999</span>
                            <span class="price-period">/month</span>
                        </div>
                    </div>
                    <div class="card-content">
                        <ul class="pricing-features">
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Up to 20 buses</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Up to 1,000 students</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Advanced route optimization</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Real-time tracking & notifications</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Priority support</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>OpenStreetMap integration</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Custom reports & analytics</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Parent mobile app</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Driver mobile app</span>
                            </li>
                        </ul>
                        <a href="{{ url('/register') }}" class="btn btn-primary btn-glow btn-full">Get Started</a>
                    </div>
                </div>

                <div class="card card-glow pricing-card">
                    <div class="card-header pricing-header">
                        <h3 class="card-title pricing-title">Enterprise</h3>
                        <p class="card-description">For large universities</p>
                        <div class="pricing-price">
                            <span class="price-value">Custom</span>
                        </div>
                    </div>
                    <div class="card-content">
                        <ul class="pricing-features">
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Unlimited buses</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Unlimited students</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>AI-powered route optimization</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Real-time tracking & notifications</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>24/7 dedicated support</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>White-label solution</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Custom integrations</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Advanced analytics & reporting</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>Multi-campus support</span>
                            </li>
                            <li class="feature-item">
                                <img src="{{ asset('assets/icons/check.svg') }}" alt="Check" class="icon-5" />
                                <span>API access</span>
                            </li>
                        </ul>
                        <a href="#contact" class="btn btn-outline btn-full">Contact Sales</a>
                    </div>
                </div>
            </div>
            <div class="pricing-footer">
                <p>
                    All plans include OpenStreetMap integration, real-time tracking, and mobile apps.
                    <br />
                    Need a custom plan? <a href="#contact" class="link-primary">Contact us</a>
                </p>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-subtitle">
                    Have questions? We'd love to hear from you
                </p>
            </div>
            <div class="contact-grid">
                <div class="card card-glow">
                    <div class="card-content contact-info-card">
                        <h3 class="contact-title">Contact Information</h3>
                        <div class="contact-info-group">
                            <div class="contact-info-item">
                                <img src="{{ asset('assets/icons/mail.svg') }}" alt="Email" class="icon-6" />
                                <div>
                                    <h4 class="contact-item-title">Email</h4>
                                    <p class="contact-item-text">support@bustrack.com</p>
                                    <p class="contact-item-text">sales@bustrack.com</p>
                                </div>
                            </div>
                            <div class="contact-info-item">
                                <img src="{{ asset('assets/icons/phone.svg') }}" alt="Phone" class="icon-6" />
                                <div>
                                    <h4 class="contact-item-title">Phone</h4>
                                    <p class="contact-item-text">+91 98765 43210</p>
                                    <p class="contact-item-text">+91 98765 43211</p>
                                </div>
                            </div>
                            <div class="contact-info-item">
                                <img src="{{ asset('assets/icons/map-pinned.svg') }}" alt="Office" class="icon-6" />
                                <div>
                                    <h4 class="contact-item-title">Office</h4>
                                    <p class="contact-item-text">
                                        123 Tech Park, Innovation Hub<br />
                                        Tamil Nadu, India - 600001
                                    </p>
                                </div>
                            </div>
                            <div class="contact-business-hours">
                                <h4 class="contact-item-title">Business Hours</h4>
                                <p class="contact-item-text">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                <p class="contact-item-text">Saturday: 9:00 AM - 2:00 PM</p>
                                <p class="contact-item-text">Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-glow">
                    <div class="card-content contact-form-card">
                        <h3 class="contact-title">Send us a Message</h3>
                        <form class="contact-form">
                            <div>
                                <label class="form-label">Name</label>
                                <input 
                                    type="text" 
                                    class="form-input"
                                    placeholder="Your name"
                                />
                            </div>
                            <div>
                                <label class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    class="form-input"
                                    placeholder="your.email@example.com"
                                />
                            </div>
                            <div>
                                <label class="form-label">Phone</label>
                                <input 
                                    type="tel" 
                                    class="form-input"
                                    placeholder="+91 98765 43210"
                                />
                            </div>
                            <div>
                                <label class="form-label">Institution Name</label>
                                <input 
                                    type="text" 
                                    class="form-input"
                                    placeholder="Your school/college name"
                                />
                            </div>
                            <div>
                                <label class="form-label">Message</label>
                                <textarea 
                                    rows="4"
                                    class="form-textarea"
                                    placeholder="Tell us about your requirements..."
                                ></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-glow btn-full">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="card card-glow cta-card">
                <div class="card-content cta-content">
                    <h2 class="cta-title">Ready to Transform Your Bus Management?</h2>
                    <p class="cta-description">
                        Join schools and colleges using BusTrack to provide safer, smarter transportation 
                        for their students with real-time tracking and automated management
                    </p>
                    <div class="cta-buttons">
                        <a href="#pricing" class="btn btn-lg btn-primary btn-glow">
                            View Pricing
                        </a>
                        <a href="{{ url('/dashboard') }}" class="btn btn-lg btn-outline">
                            Try Demo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo-link">
                        <img src="{{ asset('assets/icons/bus.svg') }}" alt="Bus" class="logo-icon-footer" />
                        <h3 class="logo-text-footer">BusTrack</h3>
                    </div>
                    <p class="footer-description">
                        Smart bus management solution for educational institutions with real-time tracking powered by OpenStreetMap.
                    </p>
                </div>

                <div class="footer-links">
                    <h4 class="footer-title">Product</h4>
                    <ul>
                        <li><a href="#features" class="footer-link">Features</a></li>
                        <li><a href="#pricing" class="footer-link">Pricing</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="footer-link">Dashboard</a></li>
                        <li><a href="#" class="footer-link">Mobile App</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4 class="footer-title">Company</h4>
                    <ul>
                        <li><a href="#" class="footer-link">About Us</a></li>
                        <li><a href="#" class="footer-link">Careers</a></li>
                        <li><a href="#" class="footer-link">Blog</a></li>
                        <li><a href="#contact" class="footer-link">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4 class="footer-title">Legal</h4>
                    <ul>
                        <li><a href="#" class="footer-link">Privacy Policy</a></li>
                        <li><a href="#" class="footer-link">Terms of Service</a></li>
                        <li><a href="#" class="footer-link">Security</a></li>
                        <li><a href="#" class="footer-link">GDPR</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 VortexFleet. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>