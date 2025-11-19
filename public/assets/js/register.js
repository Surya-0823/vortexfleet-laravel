document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (icon) {
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });
    }

    // 2. Progress Indicator Update
    const progressSteps = document.querySelectorAll('.progress-step');
    const progressFill = document.getElementById('progressFill');
    
    function updateProgress() {
        const formSections = document.querySelectorAll('.form-section');
        let completedSections = 0;
        
        if (!progressFill) return;

        formSections.forEach(section => {
            const inputs = section.querySelectorAll('input, select');
            let sectionCompleted = true;
            
            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value) {
                    sectionCompleted = false;
                }
            });
            
            if (sectionCompleted) {
                completedSections++;
            }
        });
        
        // Update progress bar
        const progress = (completedSections / formSections.length) * 100;
        progressFill.style.width = `${progress}%`;
        
        // Update step indicators
        progressSteps.forEach((step, index) => {
            if (index < completedSections) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (index === completedSections) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
    }
    
    // Add event listeners to all form inputs
    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });
    
    // Initialize progress
    updateProgress();

    // 3. Input focus effects
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            if(this.parentElement.classList.contains('input-with-icon') || this.parentElement.classList.contains('phone-input')) {
               // Optional: Add class to parent if needed
            }
        });
    });

    // 4. Auto-advance progress visual style on section completion
    document.querySelectorAll('.form-section').forEach(section => {
        const inputs = section.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                const allFilled = Array.from(inputs).every(input => {
                    return !input.hasAttribute('required') || input.value;
                });
                
                if (allFilled) {
                    section.style.borderColor = 'var(--accent)';
                    section.style.transition = 'border-color 0.5s ease';
                }
            });
        });
    });
});