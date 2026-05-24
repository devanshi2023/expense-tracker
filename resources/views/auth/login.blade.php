@extends('layouts.app', ['title' => 'Login'])

@section('content')
    <section class="auth-panel">
        <h1>Login</h1>
        <form method="POST" action="{{ route('login') }}" class="form">
            @csrf

            <label>
                Email
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>

            <label>
                Password
                <input type="password" name="password" required>
            </label>

            <label class="inline">
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <button type="submit">Login</button>
        </form>
    </section>
@endsection
