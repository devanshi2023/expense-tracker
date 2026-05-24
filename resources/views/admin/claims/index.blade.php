@extends('layouts.app', ['title' => 'All Claims'])

@section('content')
    <section class="page-header">
        <h1>Global Claim Report</h1>
        <p>Filter and override claim statuses.</p>
    </section>

    <form method="GET" class="filters report-filters">
        <select name="status">
            <option value="">All statuses</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="category_id">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="user_id">
            <option value="">All submitters</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}">
        <input type="date" name="date_to" value="{{ request('date_to') }}">
        <button type="submit">Filter</button>
        <a href="{{ route('admin.claims.index') }}">Clear</a>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Submitter</th><th>Date</th><th>Category</th><th>Amount</th><th>Status</th><th>Override</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($claims as $claim)
                    <tr>
                        <td>{{ $claim->submitter->name }}</td>
                        <td>{{ $claim->claim_date->format('M d, Y') }}</td>
                        <td>{{ $claim->category->name }}</td>
                        <td>{{ number_format($claim->amount, 2) }}</td>
                        <td><span class="badge {{ $claim->status }}">{{ ucfirst($claim->status) }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.claims.update', $claim) }}" class="review-form">
                                @csrf
                                @method('PATCH')
                                <select name="status">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($claim->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <textarea name="manager_comment" placeholder="Admin comment">{{ $claim->manager_comment }}</textarea>
                                <button type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No claims found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $claims->links() }}
@endsection
