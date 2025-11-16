// Mobile Navigation Toggle
document.querySelector('.hamburger').addEventListener('click', function() {
    document.querySelector('.nav-menu').classList.toggle('active');
});

// Feature Card Animation
function resetFeatureAnimation() {
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.classList.remove('slide-in-left', 'slide-in-right');
        card.style.opacity = '0';
    });
}

function runFeatureAnimation() {
    const featureCards = document.querySelectorAll('.feature-card');
    resetFeatureAnimation();

    featureCards.forEach((card, index) => {
        setTimeout(() => {
            if (index % 2 === 0) {
                card.classList.add('slide-in-left');
            } else {
                card.classList.add('slide-in-right');
            }
        }, 10);
    });
}

function initFeatureScrollObserver() {
    const featuresSection = document.getElementById('features');
    if (!featuresSection) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                runFeatureAnimation();
            } else {
                resetFeatureAnimation();
            }
        });
    }, { threshold: 0.2 });

    observer.observe(featuresSection);
}

function initFeatureLinkClick() {
    const featuresNavLink = document.querySelector('a[href="#features"]');
    featuresNavLink?.addEventListener('click', () => {
        setTimeout(() => {
            runFeatureAnimation();
        }, 500);
    });
}

// Contact Form Submission
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Thank you for your message! We will get back to you within 24 hours.');
    this.reset();
});

// Initialize all scripts on page load
document.addEventListener('DOMContentLoaded', function() {
    initFeatureScrollObserver();
    initFeatureLinkClick();
    // initDemoButton(); // -> Ithu remove panniyachu
});