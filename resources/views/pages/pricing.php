<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Pricing - VortexFleet'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?php echo $page_css; ?>">
    <?php endif; ?>
</head>
<body class="pricing-page">
    <nav class="landing-nav">
        <div class="nav-container">
            <div class="nav-brand">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
                </svg>
                <span class="brand-text">VortexFleet</span>
            </div>
            <div class="nav-links">
                <a href="/" class="nav-link">Home</a>
                <a href="/#features" class="nav-link">Features</a>
                <a href="/#contact" class="nav-link">Contact</a>
                <a href="/login" class="nav-link btn-primary">Launch Dashboard</a>
            </div>
        </div>
    </nav>

    <section class="pricing-hero">
        <div class="container">
            <h1 class="pricing-title">Simple, Transparent Pricing</h1>
            <p class="pricing-subtitle">Choose the perfect plan for your institution</p>
        </div>
    </section>

    <section class="pricing-section">
        <div class="container">
            <div class="pricing-grid">
                <!-- Basic Plan -->
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="plan-name">Basic Plan</h3>
                        <p class="plan-description">Perfect for small schools</p>
                    </div>
                    <div class="pricing-amount">
                        <span class="currency">₹</span>
                        <span class="amount" id="basic-monthly">1.75</span>
                        <span class="period">/day per student</span>
                    </div>
                    <div class="pricing-details">
                        <p class="calculation-info">40 seat bus = ₹70/day × 30 days</p>
                        <p class="monthly-total">Monthly: ₹<span id="basic-monthly-total">2,100</span></p>
                    </div>
                    <ul class="plan-features">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Up to 5 buses</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Up to 200 students</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Basic route management</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Real-time tracking</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Email support</span>
                        </li>
                    </ul>
                    <a href="/register?plan=basic" class="btn-pricing">Get Started</a>
                </div>

                <!-- Yearly Plan (Most Popular) -->
                <div class="pricing-card featured">
                    <div class="popular-badge">Most Popular</div>
                    <div class="pricing-header">
                        <h3 class="plan-name">Yearly Plan</h3>
                        <p class="plan-description">Save 1 month - Best Value</p>
                    </div>
                    <div class="pricing-amount">
                        <span class="currency">₹</span>
                        <span class="amount" id="yearly-total">23,100</span>
                        <span class="period">/year</span>
                    </div>
                    <div class="pricing-details">
                        <p class="calculation-info">11 months (1 month FREE)</p>
                        <p class="monthly-total">Save: ₹<span id="yearly-savings">2,100</span></p>
                    </div>
                    <ul class="plan-features">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Unlimited buses</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Unlimited students</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>AI-powered route optimization</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Real-time tracking & notifications</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Priority support</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>AI Analytics & Reports</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span>Mobile apps access</span>
                        </li>
                    </ul>
                    <a href="/register?plan=yearly" class="btn-pricing btn-pricing-featured">Get Started</a>
                </div>
            </div>
            
            <div class="pricing-footer">
                <p>All plans include OpenStreetMap integration, real-time tracking, and mobile apps.</p>
                <p>Need a custom plan? <a href="/#contact">Contact us</a></p>
            </div>
        </div>
    </section>

    <script>
        // Calculate pricing based on student count
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const students = urlParams.get('students') || 0;
            
            if (students > 0) {
                const dailyRate = students * 1.75;
                const monthlyTotal = dailyRate * 30;
                const yearlyTotal = monthlyTotal * 11; // 11 months (1 free)
                const yearlySavings = monthlyTotal;
                
                document.getElementById('basic-monthly-total').textContent = monthlyTotal.toLocaleString('en-IN');
                document.getElementById('yearly-total').textContent = yearlyTotal.toLocaleString('en-IN');
                document.getElementById('yearly-savings').textContent = yearlySavings.toLocaleString('en-IN');
            }
        });
    </script>
</body>
</html>

