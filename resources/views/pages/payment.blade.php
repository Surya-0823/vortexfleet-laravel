<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'Payment - VortexFleet' }}</title>
    
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
                    <h1>Complete Payment</h1>
                </div>
                <p class="auth-subtitle">Final step to activate your account</p>
            </div>

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            @if (isset($user))
                <form action="{{ url('/payment/process') }}" method="POST" class="auth-form" id="paymentForm">
                    @csrf
                    
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="amount" value="{{ $user->payment_amount }}">
                    <input type="hidden" name="subscription_type" value="{{ $user->subscription_type }}">

                    <div class="payment-summary">
                        <div class="price-item">
                            <span>Billed To:</span>
                            <span class="text-right">{{ $user->name }}<br>{{ $user->email }}</span>
                        </div>
                        <div class="price-item">
                            <span>Plan:</span>
                            <span>{{ ucfirst($user->subscription_type) }}</span>
                        </div>
                        <div class="price-item total">
                            <span>Amount Due:</span>
                            <span>₹{{ number_format($user->payment_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Payment Method (Simulation)</label>
                        <div class="plan-selector">
                            <label class="plan-option">
                                <input type="radio" name="payment_method" value="upi" checked>
                                <div class="plan-option-content">
                                    <span class="plan-option-title">UPI / QR Code</span>
                                </div>
                            </label>
                            <label class="plan-option">
                                <input type="radio" name="payment_method" value="card">
                                <div class="plan-option-content">
                                    <span class="plan-option-title">Credit/Debit Card</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">Simulate Payment (Click to Pay)</button>
                    
                    <p class="auth-footer">
                        <a href="{{ url('/login') }}">Login to a different account</a>
                    </p>
                </form>
            @else
                <div class="alert alert-error">
                    User details not found. Please <a href="{{ url('/register') }}">register</a> again.
                </div>
            @endif
        </div>
    </div>
</body>
</html>