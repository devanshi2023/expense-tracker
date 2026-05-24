@extends('layouts.app', ['title' => 'Categories'])

@section('content')
    <section class="page-header row">
        <div><h1>Categories</h1><p>Manage categories and monthly limits.</p></div>
        <a class="button" href="{{ route('admin.categories.create') }}">New Category</a>
    </section>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Monthly Limit</th><th>Active</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ number_format($category->monthly_budget_limit, 2) }}</td>
                        <td>{{ $category->is_active ? 'Yes' : 'No' }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}
@endsection
