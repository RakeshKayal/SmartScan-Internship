@php
    $user = auth()->user();
    $firstLetter = $user ? strtoupper(substr($user->name, 0, 1)) : 'U';
@endphp

<header class="topbar">
    <div class="topbar-left">
        <div class="topbar-title-wrap">
            <span class="topbar-overline">Retail Operations</span>
            <h2 class="topbar-title">@yield('page-heading', 'Dashboard')</h2>
        </div>
    </div>

    <div class="topbar-right">
        <div class="topbar-user-card">
            <div class="user-avatar">{{ $firstLetter }}</div>

            <div class="user-meta">
                <span class="user-name">{{ $user->name ?? 'User' }}</span>
                <span class="user-role">{{ ucfirst($user->role ?? 'staff') }}</span>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</header>