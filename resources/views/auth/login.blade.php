<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | POS System</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-bg-orb auth-orb-1"></div>
        <div class="auth-bg-orb auth-orb-2"></div>
        <div class="auth-grid">
            {{-- LEFT PANEL --}}
            <div class="auth-showcase">
                <div class="auth-brand-badge">POS SYSTEM</div>
                <h1>Smarter Retail Billing Starts Here.</h1>
                <p>
                    Access your POS workspace to manage products, customers, billing,
                    and orders with a modern, efficient retail experience.
                </p>

                <div class="auth-feature-list">
                    <div class="auth-feature-item">
                        <span class="feature-dot"></span>
                        <span>Fast product & customer management</span>
                    </div>
                    <div class="auth-feature-item">
                        <span class="feature-dot"></span>
                        <span>Smart billing workflow with order tracking</span>
                    </div>
                    <div class="auth-feature-item">
                        <span class="feature-dot"></span>
                        <span>Designed for real shop counter operations</span>
                    </div>
                </div>

                <div class="auth-showcase-card">
                    <div class="showcase-card-top">
                        <span class="mini-badge">LIVE MODULES</span>
                        <span class="mini-status">Active</span>
                    </div>
                    <div class="showcase-stats">
                        <div class="showcase-stat">
                            <h3>Products</h3>
                            <p>Inventory ready for quick billing.</p>
                        </div>
                        <div class="showcase-stat">
                            <h3>Customers</h3>
                            <p>Saved profiles for faster checkout.</p>
                        </div>
                        <div class="showcase-stat">
                            <h3>Orders</h3>
                            <p>Track sales records with confidence.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL --}}
            <div class="auth-card-wrap">
                <div class="auth-card">
                    <div class="auth-card-header">
                        <span class="auth-card-overline">WELCOME BACK</span>
                        <h2>Sign In to Your POS Panel</h2>
                        <p>Enter your credentials to continue managing your retail system.</p>
                    </div>

                    @if(session('success'))
                        <div class="auth-alert success-alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="auth-alert error-alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="auth-alert error-alert">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.submit') }}" method="POST" class="auth-form">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        </div>

                        <button type="submit" class="auth-btn">Sign In</button>
                    </form>

                    {{-- <div class="auth-footer-text">
                        Don’t have an account?
                        <a href="{{ route('register.form') }}">Create one now</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</body>
</html>