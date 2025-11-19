<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - VortexFleet</title>
    
    {{-- Fonts & Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Register CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/pages/_register.css') }}">
</head>
<body>

    <div class="register-container">
        <div class="register-static-side">
            <div class="back-home">
                <a href="{{ url('/') }}">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
            <div class="static-content">
                <div class="static-logo">Vortex<span>Fleet</span></div>
                <h1 class="static-title">Join Our Platform</h1>
                <p class="static-subtitle">Create your account and start managing your campus fleet efficiently</p>
                
                <ul class="features-list">
                    <li><i class="fas fa-satellite-dish"></i> Real-time GPS tracking</li>
                    <li><i class="fas fa-route"></i> Intelligent route optimization</li>
                    <li><i class="fas fa-mobile-alt"></i> Parent & driver mobile apps</li>
                    <li><i class="fas fa-chart-line"></i> Advanced analytics dashboard</li>
                    <li><i class="fas fa-shield-alt"></i> Secure and reliable platform</li>
                </ul>
            </div>
        </div>
        
        <div class="register-scroll-side">
            <div class="register-content">
                <div class="register-card">
                    <div class="register-header">
                        <h2 class="register-title">Create Account</h2>
                        <p class="register-subtitle">Complete all sections to register your institution</p>
                    </div>
                    
                    <div class="progress-indicator">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                        <div class="progress-step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Admin</div>
                        </div>
                        <div class="progress-step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Institution</div>
                        </div>
                        <div class="progress-step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Location</div>
                        </div>
                        <div class="progress-step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Plan</div>
                        </div>
                    </div>
                    
                    {{-- Error Display --}}
                    @if ($errors->any())
                        <div style="background: rgba(255, 99, 99, 0.1); border: 1px solid rgba(255, 99, 99, 0.3); color: #ff6b6b; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                            <ul style="list-style: none;">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('/register') }}" method="POST" id="registerForm">
                        @csrf

                        <div class="form-section">
                            <h3 class="section-title"><i class="fas fa-user-cog"></i> Admin Details</h3>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required value="{{ old('name') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="admin@institution.com" required value="{{ old('email') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number *</label>
                                    <div class="phone-input">
                                        <select class="country-code" name="country_code" id="countryCode">
                                            <option value="+91" selected>+91 IN</option>
                                            <option value="+1">+1 US</option>
                                            <option value="+44">+44 UK</option>
                                        </select>
                                        <input type="tel" id="phone" name="phone" class="form-control phone-number" placeholder="9876543210" required value="{{ old('phone') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password *</label>
                                    <div class="password-wrapper input-with-icon">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Create a strong password" required>
                                        <button type="button" class="toggle-password" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="section-title"><i class="fas fa-university"></i> Institution Details</h3>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="institution_name">Institution Name *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-school input-icon"></i>
                                        <input type="text" id="institution_name" name="institution_name" class="form-control" placeholder="Your school/organization name" required value="{{ old('institution_name') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="college_type">Institution Type *</label>
                                    <select id="college_type" name="college_type" class="form-control" required>
                                        <option value="">Select type</option>
                                        <option value="school" {{ old('college_type') == 'school' ? 'selected' : '' }}>School</option>
                                        <option value="college" {{ old('college_type') == 'college' ? 'selected' : '' }}>College</option>
                                        <option value="university" {{ old('college_type') == 'university' ? 'selected' : '' }}>University</option>
                                        <option value="training" {{ old('college_type') == 'training' ? 'selected' : '' }}>Training Institute</option>
                                        <option value="other" {{ old('college_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                
                                <div class="form-group full-width">
                                    <label for="student_count">Estimated Student Count *</label>
                                    <input type="number" id="student_count" name="student_count" class="form-control" placeholder="Approximate number of students" min="1" required value="{{ old('student_count') }}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="max_buses">Required Buses *</label>
                                    <input type="number" id="max_buses" name="max_buses" class="form-control" placeholder="Number of buses needed" min="1" required value="{{ old('max_buses') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Location Details</h3>
                            
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label for="address">Street Address *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-map input-icon"></i>
                                        <input type="text" id="address" name="address" class="form-control" placeholder="Enter complete street address" required value="{{ old('address') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="city">City *</label>
                                    <input type="text" id="city" name="city" class="form-control" placeholder="Enter city" required value="{{ old('city') }}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="state">State *</label>
                                    <input type="text" id="state" name="state" class="form-control" placeholder="Enter state" required value="{{ old('state') }}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="pincode">ZIP/Pincode *</label>
                                    <input type="text" id="pincode" name="pincode" class="form-control" placeholder="Enter ZIP code" required value="{{ old('pincode') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="section-title"><i class="fas fa-credit-card"></i> Subscription Plan</h3>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="subscription_plan">Plan Type *</label>
                                    <select id="subscription_plan" name="subscription_plan" class="form-control" required>
                                        <option value="">Select plan</option>
                                        <option value="standard" {{ old('subscription_plan') == 'standard' ? 'selected' : '' }}>Standard (¥7,999/month)</option>
                                        <option value="professional" {{ old('subscription_plan') == 'professional' ? 'selected' : '' }}>Professional (¥14,999/month)</option>
                                        <option value="enterprise" {{ old('subscription_plan') == 'enterprise' ? 'selected' : '' }}>Enterprise (Custom)</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="subscription_type">Billing Cycle</label>
                                    <select id="subscription_type" name="subscription_type" class="form-control">
                                        <option value="monthly" {{ old('subscription_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('subscription_type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('subscription_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <div class="form-buttons">
                                <button type="reset" class="btn btn-secondary">Clear Form</button>
                                <button type="submit" class="btn glowing">Create Account</button>
                            </div>
                        </div>
                        
                        <div class="login-link">
                            <p>Already have an account? <a href="{{ url('/login') }}">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/register.js') }}"></script>

</body>
</html>