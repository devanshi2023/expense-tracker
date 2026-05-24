@extends('layouts.app', ['title' => 'Edit Category'])

@section('content')
    <section class="page-header"><h1>Edit Category</h1></section>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="form panel">
        @method('PUT')
        @include('admin.categories._form', ['buttonText' => 'Update Category'])
    </form>
@endsection
