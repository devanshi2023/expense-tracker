<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Team Expense Tracker' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ auth()->check() ? 'app-body' : 'guest-body' }}">
    @php
        $user = auth()->user();
        $roleLabel = $user ? ucwords(str_replace('_', ' ', $user->role)) : null;
        $initials = $user
            ? collect(explode(' ', $user->name))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('')
            : '';

        $sidebarNav = [];

        if ($user) {
            $dashboardRoute = route('team.dashboard');
            $dashboardActive = request()->routeIs('team.dashboard');

            if ($user->isAdmin()) {
                $dashboardRoute = route('admin.dashboard');
                $dashboardActive = request()->routeIs('admin.dashboard');
            } elseif ($user->isManager()) {
                $dashboardRoute = route('manager.dashboard');
                $dashboardActive = request()->routeIs('manager.dashboard');
            }

            if ($user->isAdmin()) {
                $sidebarNav = [
                    ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
                    ['label' => 'Users', 'route' => route('admin.users.index'), 'active' => request()->routeIs('admin.users.*')],
                    ['label' => 'Categories', 'route' => route('admin.categories.index'), 'active' => request()->routeIs('admin.categories.*')],
                    ['label' => 'Claims', 'route' => route('admin.claims.index'), 'active' => request()->routeIs('admin.claims.*')],
                ];
            } elseif ($user->isManager()) {
                $sidebarNav = [
                    ['label' => 'Dashboard', 'route' => route('manager.dashboard'), 'active' => request()->routeIs('manager.dashboard')],
                    ['label' => 'My Claims', 'route' => route('team.claims.index'), 'active' => request()->routeIs('team.claims.*')],
                    ['label' => 'Budgets', 'route' => route('team.budgets.index'), 'active' => request()->routeIs('team.budgets.*')],
                ];
            } else {
                $sidebarNav = [
                    ['label' => 'Dashboard', 'route' => route('team.dashboard'), 'active' => request()->routeIs('team.dashboard')],
                    ['label' => 'My Claims', 'route' => route('team.claims.index'), 'active' => request()->routeIs('team.claims.*')],
                    ['label' => 'Budgets', 'route' => route('team.budgets.index'), 'active' => request()->routeIs('team.budgets.*')],
                ];
            }
        }
    @endphp

    @auth
        <div class="app-shell">
            <aside class="sidebar">
                <a class="brand-mark" href="{{ $dashboardRoute }}">
                    <span class="brand-badge">ET</span>
                    <span>
                        <strong>Expense Tracker</strong>
                        <small>Smart team finance hub</small>
                    </span>
                </a>

                <section class="profile-card">
                    <div class="avatar">{{ $initials }}</div>
                    <div>
                        <strong>{{ $user->name }}</strong>
                        <p>{{ $roleLabel }}</p>
                    </div>
                </section>

                <nav class="sidebar-nav">
                    <div class="nav-group">
                        <span class="nav-label">Navigation</span>
                        @foreach ($sidebarNav as $item)
                            <a href="{{ $item['route'] }}" class="nav-link {{ $item['active'] ? 'active' : '' }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </nav>

                <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
                    @csrf
                    <button type="submit" class="button ghost-button">Logout</button>
                </form>
            </aside>

            <div class="app-main">
                <header class="topbar">
                    <div>
                        <p class="eyebrow">Expense Tracker</p>
                    </div>
                    <div class="topbar-meta">
                        <span>{{ now()->format('d M Y') }}</span>
                        <span>{{ $roleLabel }}</span>
                    </div>
                </header>

                <main class="container">
                    @if (session('success'))
                        <div class="alert success">{{ session('success') }}</div>
                    @endif

                    @if (session('warning'))
                        <div class="alert warning">{{ session('warning') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert danger">
                            <strong>Please fix the following:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <div class="guest-shell">
            <header class="guest-topbar">
                <a class="brand-mark" href="{{ route('login') }}">
                    <span class="brand-badge">ET</span>
                    <span>
                        <strong>Expense Tracker</strong>
                        <small>Expense control for growing teams</small>
                    </span>
                </a>

                <nav class="guest-nav">
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}" class="button">Register</a>
                </nav>
            </header>

            <main class="guest-container">
                @if (session('success'))
                    <div class="alert success">{{ session('success') }}</div>
                @endif

                @if (session('warning'))
                    <div class="alert warning">{{ session('warning') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert danger">
                        <strong>Please fix the following:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    @endauth
</body>
</html>
