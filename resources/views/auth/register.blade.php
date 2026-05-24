@extends('layouts.app', ['title' => 'Register'])

@section('content')
    <section class="auth-panel">
        <h1>Create Account</h1>
        <p>New registrations are created as team members.</p>

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
                <input type="password" name="password" required>
            </label>

            <label>
                Confirm Password
                <input type="password" name="password_confirmation" required>
            </label>

            <button type="submit">Register</button>
        </form>
    </section>
@endsection
