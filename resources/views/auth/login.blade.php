@extends('layouts.app', ['title' => 'Login'])

@section('content')
    <section class="auth-panel">
        <div class="auth-showcase">
            <div class="auth-copy">
                <span class="eyebrow">Expense command center</span>
                <h1>Track claims, budgets, and approvals in one beautiful workspace.</h1>
                <p>Fast logins for team members, clean review flows for managers, and full reporting visibility for admins.</p>
                <ul class="auth-points">
                    <li>Role-based dashboards with instant navigation</li>
                    <li>Monthly budget visibility with polished cards and tables</li>
                    <li>Simple claim submission and faster approvals</li>
                </ul>
            </div>

            <div class="auth-stat-grid">
                <div class="auth-stat">
                    <strong>24/7</strong>
                    <span>Always-on access to team spend status</span>
                </div>
                <div class="auth-stat">
                    <strong>Live</strong>
                    <span>Budget usage snapshots for every category</span>
                </div>
            </div>
        </div>

        <div class="auth-form-wrap">
            <div class="auth-form-card">
                <div>
                    <h2>Welcome back</h2>
                    <p>Sign in to open your dashboard and continue managing team expenses.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="form">
                    @csrf

                    <label>
                        Email
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </label>

                    <label>
                        Password
                        <div class="password-field">
                            <input type="password" name="password" required data-password-input>
                            <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">Show</button>
                        </div>
                    </label>

                    <label class="inline">
                        <input type="checkbox" name="remember" value="1">
                        Remember me
                    </label>

                    <div class="form-actions">
                        <button type="submit">Login</button>
                        <span class="auth-switch">New here? <a href="{{ route('register') }}">Create account</a></span>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
