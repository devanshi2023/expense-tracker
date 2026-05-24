@extends('layouts.app', ['title' => 'Admin Dashboard'])

@section('content')
    <section class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Manage users, categories, budget limits, and claim reports.</p>
    </section>

    <div class="actions">
        <a class="button" href="{{ route('admin.users.index') }}">Manage Users</a>
        <a class="button" href="{{ route('admin.categories.index') }}">Manage Categories</a>
        <a class="button" href="{{ route('admin.claims.index') }}">View Claims</a>
    </div>
@endsection
