@extends('layouts.app', ['title' => 'Create Category'])

@section('content')
    <section class="page-header"><h1>Create Category</h1></section>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="form panel">
        @include('admin.categories._form', ['buttonText' => 'Create Category'])
    </form>
@endsection
