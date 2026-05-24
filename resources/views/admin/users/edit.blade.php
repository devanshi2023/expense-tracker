@extends('layouts.app', ['title' => 'Edit User'])

@section('content')
    <section class="page-header"><h1>Edit User</h1></section>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="form panel">
        @method('PUT')
        @include('admin.users._form', ['buttonText' => 'Update User'])
    </form>
@endsection
