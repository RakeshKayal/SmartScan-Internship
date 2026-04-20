<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS System')</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="app-shell">
        @include('partials.sidebar')

        <div class="main-shell">
            @include('partials.navbar')

            <main class="page-shell">
                <div class="page-header-block">
                    <div class="page-heading-group">
                        <span class="page-badge">POS MANAGEMENT</span>
                        <h1>@yield('page-heading', 'Dashboard')</h1>
                        <p class="page-subtitle">Manage your products, customers, billing, and orders with a smarter POS workflow.</p>
                    </div>

                    <div class="page-header-meta">
                        <div class="mini-stat-pill">
                            <span class="mini-stat-dot"></span>
                            <span>Live Workspace</span>
                        </div>
                    </div>
                </div>

                <div class="content-shell">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>