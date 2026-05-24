@extends('layouts.app', ['title' => 'Admin Dashboard'])

@section('content')
    <section class="page-header">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Manage users, categories, monthly budget limits, and the global claim report from the admin workspace.</p>
        </div>
    </section>

    <section class="cards">
        <article class="metric">
            <span>Users</span>
            <strong>CRUD</strong>
            <p>Create, update, and remove application users.</p>
        </article>
        <article class="metric">
            <span>Categories</span>
            <strong>CRUD</strong>
            <p>Maintain categories and their monthly budget limits.</p>
        </article>
        <article class="metric">
            <span>Claims</span>
            <strong>Review</strong>
            <p>Override statuses and inspect claim history.</p>
        </article>
        <article class="metric">
            <span>Reports</span>
            <strong>Filter</strong>
            <p>Use status, category, date range, and submitter filters.</p>
        </article>
    </section>

    <section class="section-block">
        <h2>Admin Actions</h2>
        <p class="section-copy">Use the links below to manage the parts required for the expense tracker.</p>
        <div class="quick-links">
            <a href="{{ route('admin.users.index') }}">
                <strong>Users</strong>
                <span>Open full user CRUD and manage roles.</span>
            </a>
            <a href="{{ route('admin.categories.index') }}">
                <strong>Categories and budget limits</strong>
                <span>Create categories and update monthly budget caps.</span>
            </a>
            <a href="{{ route('admin.claims.index') }}">
                <strong>Global claim report</strong>
                <span>Filter claims by status, category, submitter, and date range.</span>
            </a>
        </div>
    </section>
@endsection
