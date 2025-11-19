document.addEventListener('DOMContentLoaded', function() {
    // FAQ Toggle
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const item = question.parentNode;
            item.classList.toggle('active');
        });
    });

    // Interactive Steps
    const steps = document.querySelectorAll('.step');
    
    steps.forEach(step => {
        step.addEventListener('click', () => {
            // Remove active class from all steps
            steps.forEach(s => s.classList.remove('active'));
            
            // Add active class to clicked step
            step.classList.add('active');
        });
    });

    // Contact Form Submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you within 24 hours.');
            this.reset();
        });
    }

    // Scroll animations
    const fadeElements = document.querySelectorAll('.fade-in');
    
    const fadeInOnScroll = () => {
        fadeElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('visible');
            }
        });
    };
    
    window.addEventListener('scroll', fadeInOnScroll);
    window.addEventListener('load', fadeInOnScroll);
    
    // Initial check on page load
    fadeInOnScroll();
    
    // 3D Card Effect
    document.querySelectorAll('.feature-card, .pricing-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const cardRect = card.getBoundingClientRect();
            const x = e.clientX - cardRect.left;
            const y = e.clientY - cardRect.top;
            
            const centerX = cardRect.width / 2;
            const centerY = cardRect.height / 2;
            
            const angleY = (x - centerX) / 20;
            const angleX = (centerY - y) / 20;
            
            card.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg) scale(1.03)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });
});