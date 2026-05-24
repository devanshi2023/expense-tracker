@extends('layouts.app', ['title' => 'Create User'])

@section('content')
    <section class="page-header"><h1>Create User</h1></section>
    <form method="POST" action="{{ route('admin.users.store') }}" class="form panel">
        @include('admin.users._form', ['buttonText' => 'Create User'])
    </form>
@endsection
