@extends('layouts.app', ['title' => 'Create User'])

@section('content')
    <section class="page-header">
        <div>
            <span class="eyebrow">New user</span>
            <h1>Create User</h1>
            <p>Add a new account and assign the right role for the workspace.</p>
        </div>
    </section>
    <form method="POST" action="{{ route('admin.users.store') }}" class="form panel">
        @include('admin.users._form', ['buttonText' => 'Create User'])
    </form>
@endsection
