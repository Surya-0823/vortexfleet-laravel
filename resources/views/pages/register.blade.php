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

    {{-- PUTHU CURSOR ELEMENTS --}}
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    {{-- PUTHU PARTICLE BACKGROUND --}}
    <div class="particles"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <h1>Create Account</h1>
                </div>
                <p class="auth-subtitle">Join VortexFleet to manage your campus mobility</p> {{-- Subtitle Maathiyachu --}}
            </div>

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ url('/register') }}" method="POST" class="auth-form" id="registerForm">
                
                @csrf

                {{-- 2-COLUMN GRID REMOVED. Ippo direct aa form items thaan. --}}

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

                {{-- ======================================= --}}
                {{-- PLAN DETAILS & PRICE SUMMARY REMOVED --}}
                {{-- ======================================= --}}


                {{-- Button text ah maathrom --}}
                <button type="submit" class="btn-auth">Create Account</button>

                <p class="auth-footer">
                    Already have an account? <a href="{{ url('/login') }}">Login here</a>
                </p>
            </form>
        </div>
    </div>

    {{-- PUTHU SCRIPT - calculatePrice() function remove panniyachu --}}
    <script>
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

            // calculatePrice(); <- Intha line remove panniyachu
        });
    </SCriPT>
    {{-- PUTHU SCRIPT (Landing Page la irunthu) --}}
    <script src="{{ asset('assets/js/landing.js') }}"></script>
</body>
</html>