@extends('layouts.app', ['title' => 'Edit Category'])

@section('content')
    <section class="page-header">
        <div>
            <span class="eyebrow">Update category</span>
            <h1>Edit Category</h1>
            <p>Fine-tune limits and activation state for this category.</p>
        </div>
    </section>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="form panel">
        @method('PUT')
        @include('admin.categories._form', ['buttonText' => 'Update Category'])
    </form>
@endsection
