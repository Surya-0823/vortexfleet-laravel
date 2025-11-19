<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VortexFleet - Secure Payment</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/pages/payment.css') }}">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">Vortex<span>Fleet</span></div>
            <div class="nav-buttons">
                <a href="login.html" class="btn btn-outline">Login</a>
                <a href="index.html" class="btn">Home</a>
            </div>
        </div>
    </header>

    <div class="payment-container">
        <div class="payment-visual">
            <div class="visual-content">
                <h1 class="visual-title">Secure Payment</h1>
                <p class="visual-subtitle">Complete your registration with our secure payment gateway</p>

                <ul class="features-list">
                    <li><i class="fas fa-shield-alt"></i> 256-bit SSL encryption</li>
                    <li><i class="fas fa-check-circle"></i> PCI DSS compliant</li>
                    <li><i class="fas fa-lock"></i> No card details stored</li>
                    <li><i class="fas fa-bolt"></i> Instant payment confirmation</li>
                    <li><i class="fas fa-credit-card"></i> Multiple payment options</li>
                </ul>
            </div>
        </div>

        <div class="payment-content">
            <div class="payment-card">
                <div class="payment-header">
                    <div class="payment-logo">Complete Payment</div>
                    <p class="payment-subtitle">Review your order and complete payment details</p>
                </div>

                <div class="progress-indicator">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <div class="progress-step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Order</div>
                    </div>
                    <div class="progress-step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Payment</div>
                    </div>
                    <div class="progress-step" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-label">Confirm</div>
                    </div>
                </div>

                <div class="form-section active" id="order-section">
                    <h3 class="section-title"><i class="fas fa-receipt"></i> Order Summary</h3>

                    <div class="order-summary">
                        <ul class="order-items">
                            <li class="order-item">
                                <span class="order-item-label">Plan</span>
                                <span class="order-item-value">Professional (Monthly)</span>
                            </li>
                            <li class="order-item">
                                <span class="order-item-label">Student Count</span>
                                <span class="order-item-value">500 students</span>
                            </li>
                            <li class="order-item">
                                <span class="order-item-label">Monthly Fee</span>
                                <span class="order-item-value">¥14,999</span>
                            </li>
                            <li class="order-item">
                                <span class="order-item-label">Setup Fee</span>
                                <span class="order-item-value">¥5,000</span>
                            </li>
                            <li class="order-total">
                                <span>Total Amount</span>
                                <span>¥19,999</span>
                            </li>
                        </ul>
                    </div>

                    <div class="form-actions">
                        <div class="form-buttons">
                            <button class="btn btn-secondary" id="backBtn">Back</button>
                            <button class="btn" id="nextToPaymentBtn">Continue to Payment</button>
                        </div>
                    </div>
                </div>

                <div class="form-section" id="payment-section">
                    <h3 class="section-title"><i class="fas fa-credit-card"></i> Payment Details</h3>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="card-number">Card Number *</label>
                            <div class="input-with-icon">
                                <i class="fas fa-credit-card input-icon"></i>
                                <input type="text" id="card-number" class="form-control" placeholder="1234 5678 9012 3456" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="card-name">Cardholder Name *</label>
                            <input type="text" id="card-name" class="form-control" placeholder="Name on card" required>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label for="expiry-date">Expiry Date *</label>
                                <input type="text" id="expiry-date" class="form-control" placeholder="MM/YY" required>
                            </div>

                            <div class="form-group">
                                <label for="cvv">CVV *</label>
                                <input type="text" id="cvv" class="form-control" placeholder="123" required>
                            </div>
                        </div>
                    </div>

                    <div class="secure-note">
                        <i class="fas fa-shield-alt"></i>
                        Your card details are encrypted and secure. We never store your card information.
                    </div>

                    <div class="form-actions">
                        <div class="form-buttons">
                            <button class="btn btn-secondary" id="backToOrderBtn">Back</button>
                            <button class="btn glowing" id="payBtn">Pay ¥19,999</button>
                        </div>
                    </div>
                </div>

                <div class="form-section" id="confirm-section">
                    <h3 class="section-title"><i class="fas fa-check-circle"></i> Payment Confirmation</h3>

                    <div class="order-summary">
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--accent); margin-bottom: 1rem;"></i>
                            <h3 style="color: var(--accent-light); margin-bottom: 0.5rem;">Payment Successful!</h3>
                            <p>Your payment has been processed successfully. Your account will be activated shortly.</p>
                        </div>

                        <ul class="order-items">
                            <li class="order-item">
                                <span class="order-item-label">Transaction ID</span>
                                <span class="order-item-value">TX-789456123</span>
                            </li>
                            <li class="order-item">
                                <span class="order-item-label">Payment Date</span>
                                <span class="order-item-value" id="payment-date"></span>
                            </li>
                            <li class="order-item">
                                <span class="order-item-label">Payment Method</span>
                                <span class="order-item-value">Credit Card</span>
                            </li>
                            <li class="order-total">
                                <span>Amount Paid</span>
                                <span>¥19,999</span>
                            </li>
                        </ul>
                    </div>

                    <div class="form-actions">
                        <div class="form-buttons">
                            <button class="btn btn-secondary" id="printReceiptBtn">Print Receipt</button>
                            <button class="btn" id="goToDashboardBtn">Go to Dashboard</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/payment.js') }}"></script>
</body>
</html>