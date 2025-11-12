<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Register - VortexFleet'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?php echo $page_css; ?>">
    <?php endif; ?>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
                    </svg>
                    <h1>Create Account</h1>
                </div>
                <p class="auth-subtitle">Choose your plan and get started</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="POST" class="auth-form" id="registerForm">
                <?php 
                // Secure CSRF token access
                $csrfToken = '';
                if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['csrf_token'])) {
                    $csrfToken = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');
                }
                ?>
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required placeholder="+91 98765 43210">
                </div>

                <div class="form-group">
                    <label for="institution_name">Institution Name</label>
                    <input type="text" id="institution_name" name="institution_name" required placeholder="Your school/college name">
                </div>

                <div class="form-group">
                    <label for="students">Number of Students</label>
                    <input type="number" id="students" name="students" min="1" required placeholder="Enter number of students" oninput="calculatePrice()">
                </div>

                <div class="form-group">
                    <label for="buses">Number of Buses</label>
                    <input type="number" id="buses" name="buses" min="1" value="1" required>
                </div>

                <div class="form-group">
                    <label>Subscription Plan</label>
                    <div class="plan-selector">
                        <label class="plan-option">
                            <input type="radio" name="subscription_type" value="monthly" checked onchange="calculatePrice()">
                            <div class="plan-option-content">
                                <span class="plan-option-title">Monthly</span>
                                <span class="plan-option-price" id="monthly-price">₹0/month</span>
                            </div>
                        </label>
                        <label class="plan-option">
                            <input type="radio" name="subscription_type" value="yearly" onchange="calculatePrice()">
                            <div class="plan-option-content">
                                <span class="plan-option-title">Yearly <span class="badge">Save 1 Month</span></span>
                                <span class="plan-option-price" id="yearly-price">₹0/year</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="price-summary">
                    <div class="price-item">
                        <span>Daily Rate (per student):</span>
                        <span>₹1.75</span>
                    </div>
                    <div class="price-item">
                        <span>Monthly Total:</span>
                        <span id="summary-monthly">₹0</span>
                    </div>
                    <div class="price-item total">
                        <span>Total Amount:</span>
                        <span id="summary-total">₹0</span>
                    </div>
                </div>

                <input type="hidden" name="subscription_plan" value="basic">

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Create a strong password" minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" required placeholder="Confirm your password">
                </div>

                <button type="submit" class="btn-auth">Continue to Payment</button>

                <p class="auth-footer">
                    Already have an account? <a href="/login">Login here</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        function calculatePrice() {
            const students = parseInt(document.getElementById('students').value) || 0;
            const subscriptionType = document.querySelector('input[name="subscription_type"]:checked').value;
            
            // ₹1.75 per day per student
            const dailyRate = students * 1.75;
            const monthlyTotal = dailyRate * 30;
            
            let totalAmount = monthlyTotal;
            if (subscriptionType === 'yearly') {
                totalAmount = monthlyTotal * 11; // 11 months (1 month free)
            }
            
            document.getElementById('monthly-price').textContent = '₹' + monthlyTotal.toLocaleString('en-IN') + '/month';
            document.getElementById('yearly-price').textContent = '₹' + (monthlyTotal * 11).toLocaleString('en-IN') + '/year';
            document.getElementById('summary-monthly').textContent = '₹' + monthlyTotal.toLocaleString('en-IN');
            document.getElementById('summary-total').textContent = '₹' + totalAmount.toLocaleString('en-IN');
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
    </script>
</body>
</html>

