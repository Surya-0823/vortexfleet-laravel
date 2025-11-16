<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'Register - VortexFleet' }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @if (isset($page_css))
        <link rel="stylesheet" href="{{ asset($page_css) }}">
    @endif
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

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ url('/register') }}" method="POST" class="auth-form" id="registerForm">
                
                @csrf

                <h3 class="form-section-title">Admin Details</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required placeholder="Enter your full name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required placeholder="+91 98765 43210">
                    </div>
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required placeholder="Create a strong password" minlength="6">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="institution_name">Institution Name *</label>
                        <input type="text" id="institution_name" name="institution_name" required placeholder="Your school/college name">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirm Password *</label>
                        <input type="password" id="password_confirm" name="password_confirm" required placeholder="Confirm your password">
                    </div>
                </div>

                <h3 class="form-section-title">Institution Details</h3>
                
                <div class="form-group">
                    <label>College Type *</label>
                    <div class="simple-radio-group">
                        <label>
                            <input type="radio" name="college_type" value="engineering" required> Engineering
                        </label>
                        <label>
                            <input type="radio" name="college_type" value="arts"> Arts & Science
                        </label>
                        <label>
                            <input type="radio" name="college_type" value="medical"> Medical
                        </label>
                        <label>
                            <input type="radio" name="college_type" value="other"> Other
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Street Address *</label>
                    <input type="text" id="address" name="address" required placeholder="Enter street address">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City *</Label>
                        <input type="text" id="city" name="city" required placeholder="Enter city">
                    </div>
                    <div class="form-group">
                        <label for="state">State *</Label>
                        <input type="text" id="state" name="state" required placeholder="Enter state">
                    </div>
                </div>

                <div class="form-group">
                    <label for="pincode">Pincode *</Label>
                    <input type="text" id="pincode" name="pincode" required placeholder="Enter pincode" inputmode="numeric">
                </div>

                <h3 class="form-section-title">Plan Details</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_count_display">Number of Students (Default)</label>
                        <input type="text" id="student_count_display" value="40" disabled>
                        <input type="hidden" id="student_count" name="student_count" value="40">
                    </div>
                    <div class="form-group">
                        <label for="buses">Number of Buses * (Max 50)</label>
                        <input type="number" id="buses" name="buses" min="1" max="50" value="1" required placeholder="e.g., 5" oninput="calculatePrice()">
                    </div>
                </div>

                <div class="form-group">
                    <label>Subscription Plan *</label>
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
                        <span>Student Total (Monthly):</span>
                        <span id="summary-student">₹0</span>
                    </div>
                    <div class="price-item">
                        <span>Bus Total (Monthly at ₹2,000/bus):</span>
                        <span id="summary-bus">₹0</span>
                    </div>
                    <div class="price-item total">
                        <span>Total Amount:</span>
                        <span id="summary-total">₹0</span>
                    </div>
                </div>

                <input type="hidden" name="subscription_plan" value="basic">

                <button type="submit" class="btn-auth">Continue to Payment</button>

                <p class="auth-footer">
                    Already have an account? <a href="{{ url('/login') }}">Login here</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        function calculatePrice() {
            const students = parseInt(document.getElementById('student_count').value) || 0;
            
            // Get bus count
            let buses = parseInt(document.getElementById('buses').value) || 0;
            
            // Check max limit (NEW)
            const maxBuses = 50;
            if (buses > maxBuses) {
                buses = maxBuses;
                document.getElementById('buses').value = maxBuses; // Auto-correct user input
            }

            const subscriptionTypeInput = document.querySelector('input[name="subscription_type"]:checked');
            if (!subscriptionTypeInput) {
                return; 
            }
            const subscriptionType = subscriptionTypeInput.value;
            
            const studentDailyRate = students * 1.75;
            const studentMonthlyCost = studentDailyRate * 30;
            
            const busMonthlyCost = buses * 2000; 
            
            const monthlyTotal = studentMonthlyCost + busMonthlyCost;
            
            let totalAmount = monthlyTotal;
            if (subscriptionType === 'yearly') {
                totalAmount = monthlyTotal * 11; 
            }
            
            document.getElementById('monthly-price').textContent = '₹' + monthlyTotal.toLocaleString('en-IN') + '/month';
            document.getElementById('yearly-price').textContent = '₹' + (monthlyTotal * 11).toLocaleString('en-IN') + '/year';
            document.getElementById('summary-student').textContent = '₹' + studentMonthlyCost.toLocaleString('en-IN');
            document.getElementById('summary-bus').textContent = '₹' + busMonthlyCost.toLocaleString('en-IN');
            document.getElementById('summary-total').textContent = '₹' + totalAmount.toLocaleString('en-IN');
        }

        document.addEventListener('DOMContentLoaded', function() {
            
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const passwordConfirm = document.getElementById('password_confirm').value;
                
                if (password !== passwordConfirm) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }
            });

            // Call it once on load
            calculatePrice();
        });
    </script>
</body>
</html>