@extends('layouts.app', ['title' => 'Create Category'])

@section('content')
    <section class="page-header">
        <div>
            <span class="eyebrow">New category</span>
            <h1>Create Category</h1>
            <p>Set up a new expense category with its monthly budget limit.</p>
        </div>
    </section>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="form panel">
        @include('admin.categories._form', ['buttonText' => 'Create Category'])
    </form>
@endsection
