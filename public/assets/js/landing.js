/*
 * Puthu landing page JS (Advanced Animations)
 * v11 - Bento Grid Layout + Project Theme
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Custom Cursor ---
    const cursorDot = document.querySelector('.cursor-dot');
    const cursorOutline = document.querySelector('.cursor-outline');

    if (cursorDot && cursorOutline) {
        window.addEventListener('mousemove', function(e) {
            cursorDot.style.left = e.clientX + 'px';
            cursorDot.style.top = e.clientY + 'px';
            
            cursorOutline.style.left = e.clientX + 'px';
            cursorOutline.style.top = e.clientY + 'px';
        });

        // Add all clickable elements here
        document.querySelectorAll('a, .btn, .step, .bento-item, .faq-question, .hamburger, .form-input, .form-textarea').forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorDot.style.opacity = 0;
                cursorOutline.style.width = '60px';
                cursorOutline.style.height = '60px';
                cursorOutline.style.opacity = 0.3;
            });
            el.addEventListener('mouseleave', () => {
                cursorDot.style.opacity = 1;
                cursorOutline.style.width = '40px';
                cursorOutline.style.height = '40px';
                cursorOutline.style.opacity = 1;
            });
        });
    }

    // --- 2. Navbar Scroll Effect ---
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // --- 3. Mobile Hamburger Menu ---
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }

    // --- 4. Particle Background Generation ---
    const particlesContainer = document.querySelector('.particles');
    if (particlesContainer) {
        for (let i = 0; i < 20; i++) {
            let particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.width = `${Math.random() * 3 + 1}px`;
            particle.style.height = particle.style.width;
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.animationDuration = `${Math.random() * 10 + 10}s`;
            particle.style.animationDelay = `${Math.random() * 5}s`;
            particlesContainer.appendChild(particle);
        }
    }

    // --- 5. Scroll Fade/Slide-in Animations ---
    
    // General Fade-in for sections
    const sections = document.querySelectorAll('.fade-in-section');
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, { threshold: 0.1 });

    sections.forEach(sec => {
        sectionObserver.observe(sec);
    });

    // Staggered Items (for bento, steps, faq)
    const staggerItems = document.querySelectorAll('.stagger-item');
    const staggerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    staggerItems.forEach((item, index) => {
        item.style.transitionDelay = `${index * 100}ms`;
        staggerObserver.observe(item);
    });

});