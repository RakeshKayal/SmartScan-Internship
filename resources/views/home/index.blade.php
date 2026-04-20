<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System | Smart Retail Billing Solution</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <div class="landing-page">
        <div class="bg-glow bg-glow-1"></div>
        <div class="bg-glow bg-glow-2"></div>

        {{-- NAVBAR --}}
        <header class="landing-navbar">
            <a href="{{ route('home') }}" class="landing-brand">
                <div class="landing-brand-icon">P</div>
                <div class="landing-brand-text">
                    <h2>POS System</h2>
                    <p>Smart Retail Billing</p>
                </div>
            </a>

            <nav class="landing-nav-actions">
                <a href="{{ route('login.form') }}" class="nav-login-btn">Sign In</a>
            </nav>
        </header>

        {{-- HERO SECTION --}}
        <section class="hero-section">
            <div class="hero-left">
                <span class="hero-badge">MODERN POINT OF SALE PLATFORM</span>
                <h1>
                    Smarter Billing.<br>
                    Faster Checkout.<br>
                    
                </h1>
                <p>
                    Welcome to <strong>POS System</strong> made by <strong>Rakesh</strong> —
                    a modern retail billing and management solution built for products,
                    customers, orders, and smooth daily shop counter operations.
                </p>

                <div class="hero-cta-group">

                    <a href="{{ route('login.form') }}" class="hero-secondary-btn">Sign In</a>
                </div>

                {{-- <div class="hero-mini-points">
                    <div class="mini-point">
                        <span class="mini-point-dot"></span>
                        <span>Custom role-based access</span>
                    </div>
                    <div class="mini-point">
                        <span class="mini-point-dot"></span>
                        <span>Smart billing with customer flow</span>
                    </div>
                    <div class="mini-point">
                        <span class="mini-point-dot"></span>
                        <span>Modern POS-ready UI experience</span>
                    </div>
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-visual-card">
                    <div class="hero-visual-top">
                        <span class="hero-visual-badge">LIVE PREVIEW</span>
                        <span class="hero-visual-status">Retail Active</span>
                    </div>

                    <div class="hero-image-frame">
                        {{-- IMPORTANT:
                             Place your custom hero image in:
                             public/images/pos-hero.png
                        --}}
                        <img src="{{ asset('images/pos-hero.png') }}"
                             alt="POS System Hero Preview"
                             class="hero-main-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                        <div class="hero-image-fallback" style="display:none;">
                            <div class="fallback-card-grid">
                                <div class="fallback-card large-card">
                                    <div class="fallback-card-title">Billing Console</div>
                                    <div class="fallback-lines">
                                        <span></span><span></span><span></span>
                                    </div>
                                </div>
                                <div class="fallback-card small-card">
                                    <div class="fallback-chip">Orders</div>
                                </div>
                                <div class="fallback-card small-card">
                                    <div class="fallback-chip">Customers</div>
                                </div>
                                <div class="fallback-card large-card">
                                    <div class="fallback-card-title">Product Flow</div>
                                    <div class="fallback-lines">
                                        <span></span><span></span><span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hero-floating-stat stat-1">
                        <span class="stat-label">Billing</span>
                        <strong>Fast Checkout</strong>
                    </div>

                    <div class="hero-floating-stat stat-2">
                        <span class="stat-label">Orders</span>
                        <strong>Track Every Sale</strong>
                    </div>
                </div>
            {{-- </div> --}} --}}
        </section>

        {{-- STATS STRIP --}}
        {{-- <section class="stats-strip">
            <div class="stat-pill">
                <h3>Products</h3>
                <p>Manage stock-ready product records for billing.</p>
            </div>
            <div class="stat-pill">
                <h3>Customers</h3>
                <p>Save registered and walk-in customer details smartly.</p>
            </div>
            <div class="stat-pill">
                <h3>Billing</h3>
                <p>Generate bills faster with a cleaner sales workflow.</p>
            </div>
            <div class="stat-pill">
                <h3>Orders</h3>
                <p>Maintain complete order history and future payment flow.</p>
            </div>
        </section>

        {{-- FEATURES SECTION --}}
        {{-- <section class="feature-section">
            <div class="section-heading">
                <span class="section-tag">WHY THIS POS</span>
                <h2>Built for Real Retail Counter Operations</h2>
                <p>
                    A practical POS system designed for day-to-day shop billing, customer handling,
                    product management, and organized order workflows.
                </p>
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">01</div>
                    <h3>Product Management</h3>
                    <p>Add, update, view, and manage all products with barcode support for faster billing operations.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">02</div>
                    <h3>Customer Smart Capture</h3>
                    <p>Handle registered customers and walk-in customers with name and phone number during billing.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">03</div>
                    <h3>Billing Workflow</h3>
                    <p>Scan products, build cart, preview the bill, and move toward a payment-ready checkout process.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">04</div>
                    <h3>Order History</h3>
                    <p>Maintain a professional sales record system for tracking orders and future payment status.</p>
                </div>
            </div>
        </section> --}}

        {{-- WORKFLOW SECTION --}}
        {{-- <section class="workflow-section">
            <div class="section-heading">
                <span class="section-tag">HOW IT WORKS</span>
                <h2>Simple, Fast & Practical POS Flow</h2>
            </div>

            <div class="workflow-grid">
                <div class="workflow-step">
                    <div class="workflow-step-no">1</div>
                    <h3>Add Products</h3>
                    <p>Create product records with barcode, pricing, and ready-to-bill inventory data.</p>
                </div>

                <div class="workflow-step">
                    <div class="workflow-step-no">2</div>
                    <h3>Select or Capture Customer</h3>
                    <p>Choose registered customers or capture walk-in customer name and phone during billing.</p>
                </div>

                <div class="workflow-step">
                    <div class="workflow-step-no">3</div>
                    <h3>Scan & Build Cart</h3>
                    <p>Scan product barcodes, auto-build the billing cart, adjust quantity, and review totals.</p>
                </div>

                <div class="workflow-step">
                    <div class="workflow-step-no">4</div>
                    <h3>Preview & Checkout</h3>
                    <p>Open bill preview, confirm order details, and move toward payment and receipt flow.</p>
                </div>
            </div>
        </section> --}}

        {{-- FINAL CTA --}}
        {{-- <section class="final-cta-section">
            <div class="final-cta-card">
                <div class="cta-content">
                    <span class="section-tag">START NOW</span>
                    <h2>Build a More Professional Billing Experience</h2>
                    <p>
                        Access your POS workspace and manage retail operations with a cleaner,
                        smarter, and more modern system experience.
                    </p>
                </div>

                <div class="cta-actions">
    
                    <a href="{{ route('login.form') }}" class="hero-secondary-btn">Login Now</a>
                </div>
            </div>
        </section> --}} --}}
    </div>
</body>
</html>