<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VortexFleet - Make School and College Buses Easy and Safe</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/pages/landing.css') }}">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

    {{-- === PUTHU CURSOR ELEMENTS === --}}
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    {{-- === PUTHU PARTICLE BACKGROUND (Hero kulla) === --}}
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
                <a href="#pricing" class="nav-link">Pricing</a>
                <a href="#trust" class="nav-link">Support</a>
                <a href="#faq" class="nav-link">FAQ</a>
                <div class="nav-buttons">
                    <a href="{{ url('/dashboard') }}" class="btn btn-secondary" style="border-width: 2px;">Login</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary">Sign Up</a>
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
        {{-- Particle div ippo body la global ah iruku --}}
        <div class="hero-background">
            <i class="fas fa-bus floating-bus"></i>
            <i class="fas fa-map-marked-alt floating-map"></i>
            <i class="fas fa-location-arrow floating-location"></i>
            <i class="fas fa-location-arrow floating-location1"></i>
            <i class="fas fa-map-marked-alt floating-map1"></i>
        </div>
        <div class="hero-content">
            <h1 class="hero-title">VortexFleet – Make School and College Buses <span>Easy and Safe</span></h1>
            <p class="hero-subtitle">
                VortexFleet shows the live location of every bus, so admins, drivers, parents, and students do not have to worry or guess.
            </p>
            <p style="margin-bottom: 2rem;">
                Every day, people ask the same questions about buses: Is the bus late? Has it started? Has the child reached safely? VortexFleet gives one clear answer on the screen.
            </p>
            
            {{-- Puthu CSS la irunthu .hero-bullets style --}}
            <div class="hero-bullets">
                <p><i class="fas fa-check"></i>Parents can see the bus on their phone instead of calling again and again.</p>
                <p><i class="fas fa-check"></i>Admins can see all buses on one screen instead of checking many calls and messages.</p>
                <p><i class="fas fa-check"></i>Drivers can drive safely because they get fewer calls while driving.</p>
            </div>

            <div class="hero-buttons">
                <a href="{{ url('/register') }}" class="btn btn-primary">Start Free Trial</a>
                <a href="mailto:sales@vortexfleet.com" class="btn btn-secondary">See Live Demo</a>
            </div>
            <p style="margin-top: 1rem; opacity: 0.8;">
                No big setup. Works on normal phones. You can start with just a few buses.
            </p>
        </div>
    </section>

    <section id="problem" class="text-section">
        <div class="container">
            <h2>What problems happen with <span>buses today?</span></h2>
            <p>
                Many schools and colleges still manage buses using phone calls, WhatsApp groups, and handwritten lists. Parents keep calling to ask, ‘Where is the bus?’ or ‘Has my child reached?’ Admins lose time answering these calls instead of planning. Drivers get phone calls while driving, which is not safe. Because no one can see the real‑time location, everyone feels stressed and tired every day.
            </p>
        </div>
    </section>

    <section id="vision" class="text-section darker">
        <div class="container">
            <h2>Imagine calm and clear <span>bus mornings</span></h2>
            <p>
                Imagine a morning where parents open an app, see the bus coming towards the stop, and leave home at the right time. Admins open one dashboard and see all buses moving on the map with simple green and red status. Drivers follow their route on the app and do not need to explain where they are. There is no panic, no guessing, and no shouting—only calm, clear information for everyone.
            </p>
        </div>
    </section>
    
    <section id="solution" class="text-section">
        <div class="container">
            <h2>What <span>VortexFleet gives you</span></h2>
            <p>
                VortexFleet is a simple online system made for school and college buses. Admins get a web dashboard to add buses, drivers, routes, and students, and to watch every trip live. Drivers get an easy mobile app that shows their route and sends GPS location by itself. Parents and students get their own app to see the live bus location, time to reach, and alerts when the bus starts, is near, or reaches school.
            </p>
        </div>
    </section>

    <section id="time-saving" class="text-section darker">
        <div class="container">
            <h2>Save many small minutes that <span>become big hours</span></h2>
            <p>
                Without VortexFleet, you lose time in many small ways—one phone call here, one WhatsApp message there, one extra check with the driver. These small things add up to many hours each month. With VortexFleet, parents find answers in the app, not on the phone. Admins change routes, stops, or drivers in a few clicks. Over time, your team gets back many hours that they can use for better planning and safety work.
            </p>
        </div>
    </section>

    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <h2>How VortexFleet works in <span>3 simple steps</span></h2>
            <div class="steps-container">
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>Set up your buses</h3>
                    <p>You add your buses, driver details, routes, and stops into the VortexFleet dashboard. This becomes your main, correct list.</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Add students and parents</h3>
                    <p>You add students and connect them to their parents. You choose which bus and which stop each student will use.</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                    <h3>Start live trips</h3>
                    <p>Drivers open the app and start the trip. VortexFleet shows the bus moving live on the map and sends alerts to parents and admins.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <h2>What is <span>inside VortexFleet?</span></h2>
            <div class="features-grid">
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Real‑time bus tracking</h3>
                    <p>You can see every bus on a live map with its current location and time to reach each stop.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Parent and student app</h3>
                    <p>Parents and students can open the app to see where the bus is and when it will reach. They get alerts when the bus starts or is close.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Driver app</h3>
                    <p>Drivers see their route, stops, and timing in a simple app. The app sends GPS data automatically, so drivers can focus on driving.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3>Admin dashboard</h3>
                    <p>Admins see all buses, drivers, and routes on one dashboard. They can quickly see if a bus is late or off route.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3>Alerts and notifications</h3>
                    <p>VortexFleet sends automatic alerts for important events like trip start, approaching stop, or arrival at school.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Reports and history</h3>
                    <p>VortexFleet keeps a record of every trip. Later, you can see which buses are often late and where you can save time and fuel.</p>
                </div>
                
            </div>
        </div>
    </section>

    <section id="social-proof" class="text-section">
        <div class="container">
            <h2>Made for <span>real schools</span> and colleges like yours</h2>
            <p>
                VortexFleet is built for real campuses that deal with traffic, late students, and busy mornings. The system is easy to learn, even for people who are not very strong in technology. It works with normal smartphones and usual internet. This means you can start quickly without big changes in your hardware or staff.
            </p>
        </div>
    </section>

    <section id="pricing" class="text-section darker">
        <div class="container">
            <h2>Start small, then grow <span>when you see the value</span></h2>
            <p>
                With VortexFleet, you do not have to change everything on day one. You can begin with just a few buses as a small test. Once you see that parents are calmer and admins are saving time, you can slowly add more buses and routes. Plans are clear and simple, so you pay based on how many buses and students you use. This makes it safe to start as a small pilot and then grow into a full campus solution.
            </p>
        </div>
    </section>
    
    <section id="trust" class="text-section">
        <div class="container">
            <h2>We help you from <span>the first day</span></h2>
            <p>
                Transport is about real children and real safety, so you need a partner, not just a tool. VortexFleet comes with support to help you set up your data, train your staff, and solve problems fast. The system uses safe methods to protect data and control who can see what. As your routes and rules change, VortexFleet can change with you.
            </p>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Ready to make your bus system <span>calm and smart?</span></h2>
            <p>If you feel your bus system is using too much time and energy, this is the right moment to improve it.</p>
            <div class="cta-buttons">
                <a href="{{ url('/register') }}" class="btn btn-primary">Start a Free Trial</a>
                <a href="mailto:sales@vortexfleet.com" class="btn btn-secondary">Book a Short Demo</a>
            </div>
        </div>
    </section>

    <section id="faq" class="faq-section">
        <div class="container">
            <h2>Frequently Asked <span>Questions</span></h2>
            <div class="faq-grid">
                
                <div class="faq-item">
                    <h3>FAQ 1 – What is VortexFleet?</h3>
                    <p>VortexFleet is an online system that helps schools and colleges manage their buses. It shows where each bus is on a map, sends alerts to parents, and gives admins a clear view of all trips.</p>
                </div>
                
                <div class="faq-item">
                    <h3>FAQ 2 – Who can use VortexFleet?</h3>
                    <p>Admins, transport in‑charge staff, drivers, parents, and students can use VortexFleet. Each person has their own view and app, so they see only what they need.</p>
                </div>
                
                <div class="faq-item">
                    <h3>FAQ 3 – Do we need special devices?</h3>
                    <p>No. You can use normal smartphones and normal internet. Drivers use a phone with GPS, admins use a computer or laptop, and parents use their own phones.</p>
                </div>
                
                <div class="faq-item">
                    <h3>FAQ 4 – Is it difficult to set up?</h3>
                    <p>Setup is simple. You add your buses, routes, stops, drivers, and students into the system once. Our team can guide you step by step so you do not feel lost.</p>
                </div>

                <div class="faq-item">
                    <h3>FAQ 5 – Is it safe for student data?</h3>
                    <p>Yes. Student and parent details are kept safely. Only people with the right permission, like admins and transport staff, can see sensitive information.</p>
                </div>
                
                <div class="faq-item">
                    <h3>FAQ 6 – What if the internet is slow or goes off?</h3>
                    <p>If the internet is slow, the app will still try to update when it can. When the connection comes back, the system refreshes the location. The system is made to handle normal network problems.</p>
                </div>

                <div class="faq-item">
                    <h3>FAQ 7 – Can we start with a few buses only?</h3>
                    <p>Yes. You can start with just a few buses and some routes. This helps you test VortexFleet with a small group first. Later, you can add more buses when you are happy with the results.</p>
                </div>

                <div class="faq-item">
                    <h3>FAQ 8 – How does VortexFleet help parents?</h3>
                    <p>Parents do not need to call the school to ask about the bus. They can open the app, see the bus on the map, and get alerts when it is near their stop or when their child’s bus reaches school.</p>
                </div>

                <div class="faq-item">
                    <h3>FAQ 9 – How does VortexFleet help admins?</h3>
                    <p>Admins and transport staff get one dashboard to see all buses at once. They can quickly spot late buses, route changes, or problems. They make fewer phone calls and can spend more time improving the system.</p>
                </div>

                <div class="faq-item">
                    <h3>FAQ 10 – What should we do if we want to try VortexFleet?</h3>
                    <p>You can contact the VortexFleet team, start a free trial, or ask for a demo. They will show you how the system works and help you set up a small pilot.</p>
                </div>

            </div>
        </div>
    </section>

    <footer class="footer">
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
                        <a href="#pricing">Pricing</a>
                        <a href="#how-it-works">How it Works</a>
                        <a href="#faq">FAQ</a>
                    </div>
                    <div class="footer-column">
                        <h4>Company</h4>
                        <a href="#">About Us</a>
                        <a href="#trust">Support</a>
                        <a href="{{ url('/dashboard') }}">Login</a>
                    </div>
                    <div class="footer-column">
                        <h4>Legal</h4>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Security</a>
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