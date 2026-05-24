@extends('layouts.app', ['title' => 'Edit User'])

@section('content')
    <section class="page-header">
        <div>
            <span class="eyebrow">Update user</span>
            <h1>Edit User</h1>
            <p>Adjust role, account details, or reset credentials when needed.</p>
        </div>
    </section>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="form panel">
        @method('PUT')
        @include('admin.users._form', ['buttonText' => 'Update User'])
    </form>
@endsection
