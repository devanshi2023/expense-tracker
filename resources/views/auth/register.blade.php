@extends('layouts.app', ['title' => 'Register'])

@section('content')
    <section class="auth-panel">
        <div class="auth-showcase">
            <div class="auth-copy">
                <span class="eyebrow">Create your workspace</span>
                <h1>Give your team a sharper expense workflow from day one.</h1>
                <p>New users join as team members and get instant access to claim tracking, budget views, and a cleaner dashboard experience.</p>
                <ul class="auth-points">
                    <li>Guided submit-and-track journey for claims</li>
                    <li>Clear monthly budget visibility by category</li>
                    <li>Modern UI built for desktop and mobile screens</li>
                </ul>
            </div>

            <div class="auth-stat-grid">
                <div class="auth-stat">
                    <strong>1 min</strong>
                    <span>Quick setup to enter the dashboard</span>
                </div>
                <div class="auth-stat">
                    <strong>Secure</strong>
                    <span>Role-aware access for each screen</span>
                </div>
            </div>
        </div>

        <div class="auth-form-wrap">
            <div class="auth-form-card">
                <div>
                    <h2>Create account</h2>
                    <p>Register a new team member profile and start using the expense tracker right away.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="form">
                    @csrf

                    <label>
                        Name
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </label>

                    <label>
                        Email
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </label>

                    <label>
                        Password
                        <div class="password-field">
                            <input type="password" name="password" required data-password-input>
                            <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">Show</button>
                        </div>
                    </label>

                    <label>
                        Confirm Password
                        <div class="password-field">
                            <input type="password" name="password_confirmation" required data-password-input>
                            <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">Show</button>
                        </div>
                    </label>

                    <div class="form-actions">
                        <button type="submit">Register</button>
                        <span class="auth-switch">Already registered? <a href="{{ route('login') }}">Login</a></span>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
