@extends('layouts.app', ['title' => 'My Claims'])

@section('content')
    <section class="page-header row">
        <div>
            <h1>My Expense Claims</h1>
            <p>Pending claims can be edited or deleted.</p>
        </div>
        <a class="button" href="{{ route('team.claims.create') }}">New Claim</a>
    </section>

    <section class="cards">
        <div class="metric"><span>Total</span><strong>{{ $summary->total ?? 0 }}</strong></div>
        <div class="metric"><span>Pending</span><strong>{{ $summary->pending ?? 0 }}</strong></div>
        <div class="metric"><span>Approved</span><strong>{{ $summary->approved ?? 0 }}</strong></div>
        <div class="metric"><span>Rejected</span><strong>{{ $summary->rejected ?? 0 }}</strong></div>
    </section>

    <form method="GET" class="filters">
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

        <button type="submit">Filter</button>
        <a href="{{ route('team.claims.index') }}">Clear</a>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($claims as $claim)
                    <tr>
                        <td>{{ $claim->claim_date->format('M d, Y') }}</td>
                        <td>{{ $claim->category->name }}</td>
                        <td>{{ $claim->description }}</td>
                        <td>{{ number_format($claim->amount, 2) }}</td>
                        <td><span class="badge {{ $claim->status }}">{{ ucfirst($claim->status) }}</span></td>
                        <td>{{ $claim->manager_comment ?: '-' }}</td>
                        <td class="actions">
                            @if ($claim->isPending())
                                <a href="{{ route('team.claims.edit', $claim) }}">Edit</a>
                                <form method="POST" action="{{ route('team.claims.destroy', $claim) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="danger-button">Delete</button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No claims found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $claims->links() }}
@endsection
