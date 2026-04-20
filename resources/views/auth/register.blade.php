<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | POS System</title>
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
                <h1>Create Your POS Access Account.</h1>
                <p>
                    Register as an Admin or Sales user to access the POS workspace
                    and manage products, customers, billing, and orders professionally.
                </p>

                <div class="auth-feature-list">
                    <div class="auth-feature-item">
                        <span class="feature-dot"></span>
                        <span>Admin and Sales role based access</span>
                    </div>
                    <div class="auth-feature-item">
                        <span class="feature-dot"></span>
                        <span>Secure login with your custom authentication flow</span>
                    </div>
                    <div class="auth-feature-item">
                        <span class="feature-dot"></span>
                        <span>Modern POS experience built for daily shop operations</span>
                    </div>
                </div>

                <div class="auth-showcase-card">
                    <div class="showcase-card-top">
                        <span class="mini-badge">SMART ACCESS</span>
                        <span class="mini-status">Ready</span>
                    </div>
                    <div class="showcase-stats">
                        <div class="showcase-stat">
                            <h3>Admin</h3>
                            <p>Manage complete POS operations.</p>
                        </div>
                        <div class="showcase-stat">
                            <h3>Sales</h3>
                            <p>Handle customer billing efficiently.</p>
                        </div>
                        <div class="showcase-stat">
                            <h3>Secure</h3>
                            <p>Clean custom authentication system.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL --}}
            <div class="auth-card-wrap">
                <div class="auth-card">
                    <div class="auth-card-header">
                        <span class="auth-card-overline">CREATE ACCOUNT</span>
                        <h2>Register for POS Access</h2>
                        <p>Fill in your details to create a new Admin or Sales account.</p>
                    </div>

                    @if($errors->any())
                        <div class="auth-alert error-alert">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register.submit') }}" method="POST" class="auth-form">
                        @csrf

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name" placeholder="Enter your full name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Select Role</label>
                            <select name="role" id="role" required>
                                <option value="">-- Select Role --</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="sales" {{ old('role') === 'sales' ? 'selected' : '' }}>Sales</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" placeholder="Enter password" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" required>
                        </div>

                        <button type="submit" class="auth-btn">Create Account</button>
                    </form>

                    <div class="auth-footer-text">
                        Already have an account?
                        <a href="{{ route('login.form') }}">Sign in here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>