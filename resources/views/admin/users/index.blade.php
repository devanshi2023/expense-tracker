@extends('layouts.app', ['title' => 'Users'])

@section('content')
    <section class="page-header">
        <div>
            <span class="eyebrow">Access control</span>
            <h1>Users</h1>
            <p>Manage user roles and access from a cleaner admin screen.</p>
        </div>
        <a class="button" href="{{ route('admin.users.create') }}">New User</a>
    </section>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge approved">{{ str_replace('_', ' ', $user->role) }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            @if (! auth()->user()->is($user))
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="danger-button">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
@endsection
