<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VortexFleet - Smart College Bus Management</title>
    
    <link href="{{ asset('assets/css/pages/landing.css') }}" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    </head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <i class="fas fa-bus"></i>
                <span>VortexFleet</span>
            </div>
            <div class="nav-menu">
                <a href="#home" class="nav-link">Home</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#pricing" class="nav-link">Pricing</a>
                <a href="#contact" class="nav-link">Contact</a>
                
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="nav-link">Register</a>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="hero-background">
            <div class="floating-bus">
                <i class="fas fa-bus"></i>
            </div>
            <div class="floating-map">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="floating-location">
                <i class="fas fa-location-arrow"></i>
            </div>
            <div class="floating-location1">
                <i class="fas fa-location-arrow"></i>
            </div>
            <div class="floating-map1">
                <i class="fas fa-map-marked-alt"></i>
            </div>
        </div>
        <div class="hero-content">
            <h1 class="hero-title">Smart Campus Mobility<br><span>Made Simple</span></h1>
            <p class="hero-subtitle">Complete college bus tracking system with real-time location updates, route optimization, and secure student verification</p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
            </div>
        </div>
    </section>

    <section class="how-it-works">
        <div class="container">
            <h2>Get Started in Three Simple Steps</h2>
            <div class="steps-container">
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-bus"></i>
                    </div>
                    <h3>Fleet Onboarding & Assignment</h3>
                    <p>Configure your transportation fleet by registering buses, creating driver profiles, and assigning each vehicle to its designated route through the centralized admin console</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Student Enrollment & Route Mapping</h3>
                    <p>Enroll students securely, verify parent access, and map each student to the appropriate bus route for streamlined daily operation</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Real-Time Operational Tracking</h3>
                    <p>Enable drivers to broadcast live GPS updates, providing students and parents with accurate, real-time bus location visibility and arrival insights</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <h2>Complete Bus Management Solution</h2>
            <div class="features-grid">
                <div class="feature-card" id="tracking">
                    <div class="feature-icon">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                    <h3>Real-Time Bus Tracking</h3>
                    <p>Live GPS tracking with instant updates. Students and parents can see bus location on interactive maps</p>
                </div>
                <div class="feature-card" id="student-app">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Student & Parent App</h3>
                    <p>Real-time bus location, accurate ETA, route visibility, and instant alerts for delays and arrivals</p>
                </div>
                <div class="feature-card" id="driver-app">
                    <div class="feature-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Driver App</h3>
                    <p>Easy-to-use interface with route assignments, trip details, and continuous GPS updates</p>
                </div>
                <div class="feature-card" id="admin-dashboard">
                    <div class="feature-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3>Admin Dashboard</h3>
                    <p>Centralized control for driver management, student management, and live map monitoring</p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="pricing-container">
        <div class="container">
            <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 1rem; color: white;">Simple, Transparent Pricing</h2>
            <p style="text-align: center; color: white; margin-bottom: 3rem;">Choose the perfect plan for your educational institution</p>
            
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="plan-name">Starter</div>
                    <div class="plan-description">Perfect for small colleges</div>
                    <div class="plan-price">₹4,999<span style="font-size: 1rem; color: white;">/month</span></div>
                    <ul class="plan-features">
                        <li><i class="fas fa-check"></i> Up to 5 buses</li>
                        <li><i class="fas fa-check"></i> Up to 200 students</li>
                        <li><i class="fas fa-check"></i> Basic route management</li>
                        <li><i class="fas fa-check"></i> Real-time tracking</li>
                        <li><i class="fas fa-check"></i> Email support</li>
                        <li><i class="fas fa-check"></i> Mobile app access</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="width: 100%; text-align: center;">Get Started</a>
                </div>

                <div class="pricing-card popular">
                    <div class="plan-name">Campus</div>
                    <div class="plan-description">Ideal for medium colleges</div>
                    <div class="plan-price">₹9,999<span style="font-size: 1rem; color: white">/month</span></div>
                    <ul class="plan-features">
                        <li><i class="fas fa-check"></i> Up to 20 buses</li>
                        <li><i class="fas fa-check"></i> Up to 1,000 students</li>
                        <li><i class="fas fa-check"></i> Advanced route optimization</li>
                        <li><i class="fas fa-check"></i> Real-time tracking & notifications</li>
                        <li><i class="fas fa-check"></i> Priority support</li>
                        <li><i class="fas fa-check"></i> Custom reports & analytics</li>
                        <li><i class="fas fa-check"></i> Parent mobile app</li>
                        <li><i class="fas fa-check"></i> Driver mobile app</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="width: 100%; text-align: center;">Get Started</a>
                </div>

                <div class="pricing-card">
                    <div class="plan-name">Enterprise</div>
                    <div class="plan-description">For large universities</div>
                    <div class="plan-price">Custom</div>
                    <ul class="plan-features">
                        <li><i class="fas fa-check"></i> Unlimited buses</li>
                        <li><i class="fas fa-check"></i> Unlimited students</li>
                        <li><i class="fas fa-check"></i> AI-powered route optimization</li>
                        <li><i class="fas fa-check"></i> Real-time tracking & notifications</li>
                        <li><i class="fas fa-check"></i> 24/7 dedicated support</li>
                        <li><i class="fas fa-check"></i> White-label solution</li>
                        <li><i class="fas fa-check"></i> Custom integrations</li>
                        <li><i class="fas fa-check"></i> Multi-campus support</li>
                        <li><i class="fas fa-check"></i> API access</li>
                    </ul>
                    <a href="#contact" class="btn btn-secondary" style="width: 100%; text-align: center;">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-section">
        <div class="container">
            <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem; color: white;">Get In Touch</h2>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>support@vortexfleet.com</p>
                            <p>sales@vortexfleet.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Phone</h4>
                            <p>+91 98765 43210</p>
                            <p>+91 98765 43211</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Office</h4>
                            <p>123 Tech Park, Innovation Hub</p>
                            <p>Tamil Nadu, India - 600001</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Ready to Transform Your Campus Mobility?</h2>
            <p>Join hundreds of educational institutions using VortexFleet</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">Start Free Trial</a>
                <a href="#contact" class="btn btn-secondary">Contact Sales</a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3><i class="fas fa-bus"></i> VortexFleet</h3>
                    <p>Smart bus management solution for educational institutions with real-time tracking</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Product</h4>
                        <a href="#features">Features</a>
                        <a href="#pricing">Pricing</a>
                        <a href="#dashboard">Dashboard</a>
                        <a href="#mobile-app">Mobile App</a>
                    </div>
                    <div class="footer-column">
                        <h4>Company</h4>
                        <a href="#about">About Us</a>
                        <a href="#careers">Careers</a>
                        <a href="#blog">Blog</a>
                        <a href="#contact">Contact</a>
                    </div>
                    <div class="footer-column">
                        <h4>Legal</h4>
                        <a href="#privacy">Privacy Policy</a>
                        <a href="#terms">Terms of Service</a>
                        <a href="#security">Security</a>
                        <a href="#gdpr">GDPR</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 VortexFleet. All rights reserved.</p>
                <p>made with ❤️ Dhana Surya</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/landing.js') }}"></script>

    </body>
</html>