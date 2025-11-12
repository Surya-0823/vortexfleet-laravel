<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Payment - VortexFleet</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/pages/auth.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card payment-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
                    </svg>
                    <h1>Complete Payment</h1>
                </div>
                <p class="auth-subtitle">Secure payment to activate your account</p>
            </div>

            <?php
            // Secure session access
            $userId = $_GET['user_id'] ?? null;
            $paymentAmount = 0;
            $subscriptionType = 'monthly';
            
            if (session_status() === PHP_SESSION_ACTIVE) {
                $userId = $userId ?? ($_SESSION['registering_user_id'] ?? null);
                $paymentAmount = $_SESSION['payment_amount'] ?? 0;
                $subscriptionType = $_SESSION['subscription_type'] ?? 'monthly';
            }
            
            if (!$userId) {
                // Redirect to register if no user ID
                if (!headers_sent()) {
                    header('Location: /register');
                    exit;
                }
            }
            ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <div class="payment-summary">
                <h3>Payment Summary</h3>
                <div class="summary-item">
                    <span>Subscription Type:</span>
                    <span><?php echo ucfirst($subscriptionType); ?></span>
                </div>
                <div class="summary-item total">
                    <span>Total Amount:</span>
                    <span>₹<?php echo number_format($paymentAmount, 2); ?></span>
                </div>
            </div>

            <form action="/payment/process" method="POST" class="auth-form" id="paymentForm">
                <?php 
                // Secure CSRF token access
                $csrfToken = '';
                if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['csrf_token'])) {
                    $csrfToken = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');
                }
                ?>
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                <input type="hidden" name="amount" value="<?php echo $paymentAmount; ?>">
                <input type="hidden" name="subscription_type" value="<?php echo $subscriptionType; ?>">

                <div class="form-group">
                    <label>Payment Method</label>
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="card" checked>
                            <div class="payment-method-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                                <span>Credit/Debit Card</span>
                            </div>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="upi">
                            <div class="payment-method-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <span>UPI</span>
                            </div>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="netbanking">
                            <div class="payment-method-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                                <span>Net Banking</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="cardDetails" class="payment-details">
                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry">Expiry Date</label>
                            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="card_name">Cardholder Name</label>
                        <input type="text" id="card_name" name="card_name" placeholder="John Doe">
                    </div>
                </div>

                <div class="security-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                    </svg>
                    <span>Your payment is secure and encrypted</span>
                </div>

                <button type="submit" class="btn-auth">Pay ₹<?php echo number_format($paymentAmount, 2); ?></button>

                <p class="auth-footer">
                    <a href="/register">← Back to Registration</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const cardDetails = document.getElementById('cardDetails');
                if (this.value === 'card') {
                    cardDetails.style.display = 'block';
                } else {
                    cardDetails.style.display = 'none';
                }
            });
        });

        // Format card number
        document.getElementById('card_number')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Format expiry date
        document.getElementById('expiry')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>