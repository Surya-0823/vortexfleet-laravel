<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VortexFleet - Campus Mobility Operating System</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/pages/landing.css') }}">
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">Vortex<span>Fleet</span></div>
            <nav>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="btn">Get Started</a>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>The Operating System for Campus Mobility</h1>
                <p>VortexFleet moves your campus transport from chaotic spreadsheets and phone calls to a single, predictive, real-time platform.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn">Get Started Now</a>
                    <a href="#contact" class="btn btn-outline">Schedule a Demo</a>
                </div>
            </div>
        </div>
    </section>

    <section id="features">
        <div class="container">
            <h2 class="text-center mb-4">Powerful Features for Campus Transport</h2>
            <p class="text-center">Everything you need to manage your fleet efficiently and effectively</p>
            
            <div class="features-grid">
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Unified Live Map</h3>
                    <p>See all buses, routes, and stops in a single real-time view. Monitor your entire fleet with our advanced GPS tracking.</p>
                </div>
                
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <h3>Intelligent Route Optimization</h3>
                    <p>Stop wasting fuel and time. Our AI-powered optimizer analyzes traffic patterns to build the fastest, safest routes.</p>
                </div>
                
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3>Digital Attendance & Security</h3>
                    <p>Ensure every student is accounted for. Drivers mark attendance from their app, sending instant notifications to parents.</p>
                </div>
                
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Parent Peace of Mind</h3>
                    <p>Eliminate parent anxiety with a simple app to see bus locations, accurate ETAs, and instant notifications.</p>
                </div>
                
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Predictive Operations</h3>
                    <p>Move from reactive to predictive. Our dashboard shows a mission control view of your entire fleet.</p>
                </div>
                
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Focus on Safety</h3>
                    <p>Operations teams spend less time on phone calls and more on strategic improvements and safety.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <h2 class="text-center mb-4">How VortexFleet Works in 3 Simple Steps</h2>
            
            <div class="steps-container">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="fas fa-bus"></i>
                    </div>
                    <h3>Set Up Your Fleet</h3>
                    <div class="step-content">
                        <p>Add your buses, driver details, routes, and stops into the VortexFleet dashboard. Our intuitive interface makes setup quick and easy.</p>
                    </div>
                </div>
                
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Add Students & Parents</h3>
                    <div class="step-content">
                        <p>Enroll students, connect parent accounts, and assign each student to the right bus and stop. Bulk import options available.</p>
                    </div>
                </div>
                
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="fas fa-satellite"></i>
                    </div>
                    <h3>Go Live</h3>
                    <div class="step-content">
                        <p>Drivers start trips from the mobile app, and VortexFleet begins broadcasting live GPS updates to parents and administrators.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="pricing">
        <div class="container">
            <h2 class="text-center mb-4">Simple, Transparent Pricing</h2>
            <p class="text-center">Choose the plan that works best for your institution</p>
            
            <div class="pricing-grid">
                <div class="pricing-card fade-in">
                    <div class="pricing-header">
                        <h3>Standard</h3>
                        <div class="price">¥7,999<span>/month/campus</span></div>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 10 buses</li>
                        <li>Live Tracking</li>
                        <li>Parent App (Android & iOS)</li>
                        <li>Driver App</li>
                        <li>Email Support</li>
                    </ul>
                    <div class="pricing-cta">
                        <a href="{{ route('register') }}" class="btn btn-outline">Get Started</a>
                    </div>
                </div>
                
                <div class="pricing-card featured fade-in">
                    <div class="pricing-header">
                        <h3>Professional</h3>
                        <div class="price">¥14,999<span>/month/campus</span></div>
                    </div>
                    <ul class="pricing-features">
                        <li>Up to 25 buses</li>
                        <li>Everything in Standard</li>
                        <li>Route Optimization</li>
                        <li>Student Attendance</li>
                        <li>Priority Phone Support</li>
                    </ul>
                    <div class="pricing-cta">
                        <a href="{{ route('register') }}" class="btn">Choose Professional</a>
                    </div>
                </div>
                
                <div class="pricing-card fade-in">
                    <div class="pricing-header">
                        <h3>Enterprise</h3>
                        <div class="price">Custom<span>/for large institutions</span></div>
                    </div>
                    <ul class="pricing-features">
                        <li>Unlimited buses</li>
                        <li>Everything in Professional</li>
                        <li>Dedicated Account Manager</li>
                        <li>On-site Training</li>
                        <li>Custom Integrations</li>
                    </ul>
                    <div class="pricing-cta">
                        <a href="#contact" class="btn btn-outline">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <h2 class="text-center mb-4">Get in Touch</h2>
            <p class="text-center">Let's talk about your fleet management needs</p>
            
            <div class="contact-container">
                <div class="contact-info">
                    <p>Fill out the form or email us directly. We'll get back to you within 24 hours to schedule a free, no-obligation demo.</p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>sales@vortexfleet.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+91 98765 43210</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Chennai, Tamil Nadu, India</span>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="institution">Institution Name</label>
                            <input type="text" id="institution" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" class="form-control" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="faq">
        <div class="container">
            <h2 class="text-center mb-4">Frequently Asked Questions</h2>
            
            <div class="faq-container">
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>What is VortexFleet?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>VortexFleet is a smart bus management system for schools and colleges. It helps you track buses in real time, keep parents informed, and run transport smoothly every day.</p>
                    </div>
                </div>
                
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>Who is this system for?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>VortexFleet is designed for educational institutions of all sizes - from small private schools to large university campuses that need to manage their transportation systems efficiently.</p>
                    </div>
                </div>
                
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>Do we need to buy special GPS hardware?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>No special hardware required! VortexFleet works with smartphones and tablets that drivers can use. The app utilizes the device's built-in GPS for accurate tracking.</p>
                    </div>
                </div>
                
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>Is the setup process difficult?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Not at all! Our setup process is designed to be simple and intuitive. Most institutions can get up and running in less than a week, and we provide dedicated support during implementation.</p>
                    </div>
                </div>
                
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>How does VortexFleet help parents?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Parents receive a dedicated app showing real-time bus locations, accurate arrival times, and instant notifications for delays or when their child boards the bus.</p>
                    </div>
                </div>
                
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>How can we start a pilot program?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>We offer a 30-day free trial for institutions interested in testing VortexFleet. Contact our sales team to set up a pilot program tailored to your needs.</p>
                    </div>
                </div>
                
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>Is my data safe and secure?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we take data security seriously. All data is encrypted in transit and at rest, and we comply with educational data privacy regulations.</p>
                    </div>
                </div>
                
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>What happens if the system breaks down?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>VortexFleet has a 99.9% uptime guarantee with redundant systems in place. In the rare event of an issue, our support team is available 24/7 to resolve problems quickly.</p>
                    </div>
                </div>
                
                <div class="faq-item fade-in">
                    <div class="faq-question">
                        <span>Can this work in areas with poor internet?</span>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes! VortexFleet is designed to work in low-connectivity environments. The mobile apps can store data locally and sync when connection is restored.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="text-center mb-4">Ready to See It in Action?</h2>
                <p class="text-center">Schedule a 15-minute demo to see how VortexFleet can unify your campus transport.</p>
                <div class="hero-buttons" style="justify-content: center;">
                    <a href="{{ route('register') }}" class="btn">Start Free Trial</a>
                    <a href="#contact" class="btn btn-outline">Schedule Demo</a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>VortexFleet</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">The operating system for campus mobility. Streamline your transport operations with our intelligent platform.</p>
                </div>
                
                <div class="footer-column">
                    <h3>Product</h3>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#faq">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Company</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Legal</h3>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2025 VortexFleet. All rights reserved. Made with ❤️ by Dhana Surya.R</p>
            </div>
        </div>
    </footer>

    {{-- Custom JS --}}
    <script src="{{ asset('assets/js/landing.js') }}"></script>
</body>
</html>