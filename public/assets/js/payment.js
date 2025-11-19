// Form Navigation
const orderSection = document.getElementById('order-section');
const paymentSection = document.getElementById('payment-section');
const confirmSection = document.getElementById('confirm-section');

const progressSteps = document.querySelectorAll('.progress-step');
const progressFill = document.getElementById('progressFill');

// Navigation buttons
document.getElementById('nextToPaymentBtn').addEventListener('click', function() {
    orderSection.classList.remove('active');
    paymentSection.classList.add('active');

    // Update progress
    updateProgress(1);
});

document.getElementById('backToOrderBtn').addEventListener('click', function() {
    paymentSection.classList.remove('active');
    orderSection.classList.add('active');

    // Update progress
    updateProgress(0);
});

document.getElementById('payBtn').addEventListener('click', function(e) {
    e.preventDefault();

    // Validate card form
    const cardNumber = document.getElementById('card-number').value;
    const cardName = document.getElementById('card-name').value;
    const expiryDate = document.getElementById('expiry-date').value;
    const cvv = document.getElementById('cvv').value;

    if (!cardNumber || !cardName || !expiryDate || !cvv) {
        alert('Please fill in all required card details.');
        return;
    }

    if (cardNumber.replace(/\s/g, '').length !== 16) {
        alert('Please enter a valid 16-digit card number.');
        return;
    }

    // Simulate payment processing
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    this.disabled = true;

    setTimeout(() => {
        paymentSection.classList.remove('active');
        confirmSection.classList.add('active');

        // Set payment date
        const now = new Date();
        document.getElementById('payment-date').textContent = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();

        // Update progress
        updateProgress(2);
    }, 2000);
});

// Back button functionality
document.getElementById('backBtn').addEventListener('click', function() {
    if (confirm('Are you sure you want to go back? Your payment details will be lost.')) {
        window.history.back();
    }
});

// Go to dashboard
document.getElementById('goToDashboardBtn').addEventListener('click', function() {
    alert('Redirecting to dashboard...');
    // In a real application, you would redirect to the dashboard
    // window.location.href = 'dashboard.html';
});

// Print receipt
document.getElementById('printReceiptBtn').addEventListener('click', function() {
    window.print();
});

// Update progress function
function updateProgress(currentStep) {
    // Update progress bar
    const progress = (currentStep / 2) * 100;
    progressFill.style.width = `${progress}%`;

    // Update step indicators
    progressSteps.forEach((step, index) => {
        if (index < currentStep) {
            step.classList.add('completed');
            step.classList.remove('active');
        } else if (index === currentStep) {
            step.classList.add('active');
            step.classList.remove('completed');
        } else {
            step.classList.remove('active', 'completed');
        }
    });
}

// Input formatting for card number
document.getElementById('card-number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = '';

    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formattedValue += ' ';
        }
        formattedValue += value[i];
    }

    e.target.value = formattedValue;
});

// Input formatting for expiry date
document.getElementById('expiry-date').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');

    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }

    e.target.value = value;
});

// Initialize progress
updateProgress(0);