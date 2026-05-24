<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Team Expense Tracker' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}">Team Expense Tracker</a>
        <nav class="nav">
            @auth
                @if (auth()->user()->isTeamMember())
                    <a href="{{ route('team.dashboard') }}">My Claims</a>
                @endif
                <a href="{{ route('team.budgets.index') }}">Budgets</a>
                @if (auth()->user()->isManager())
                    <a href="{{ route('manager.dashboard') }}">Manager</a>
                @endif
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                <span>{{ auth()->user()->name }} · {{ str_replace('_', ' ', auth()->user()->role) }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="link-button">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </nav>
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
</body>
</html>
