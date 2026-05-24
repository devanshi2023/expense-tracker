@extends('layouts.app', ['title' => 'Manager Dashboard'])

@section('content')
    <section class="page-header">
        <h1>Manager Dashboard</h1>
        <p>Review pending claims and monitor current month budget usage.</p>
    </section>

    <h2>Pending Claims</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Submitter</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingClaims as $claim)
                    <tr>
                        <td>{{ $claim->submitter->name }}</td>
                        <td>{{ $claim->claim_date->format('M d, Y') }}</td>
                        <td>{{ $claim->category->name }}</td>
                        <td>{{ $claim->description }}</td>
                        <td>{{ number_format($claim->amount, 2) }}</td>
                        <td>
                            @if ($claim->user_id === auth()->id())
                                <span class="muted">Self approval blocked</span>
                            @else
                                <form method="POST" action="{{ route('manager.claims.review', $claim) }}" class="review-form">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="manager_comment" placeholder="Optional comment">{{ old('manager_comment') }}</textarea>
                                    <div class="actions">
                                        <button type="submit" name="status" value="approved">Approve</button>
                                        <button type="submit" name="status" value="rejected" class="danger-button">Reject</button>
                                    </div>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No pending claims.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h2>Current Month Budget Utilization</h2>
    @include('shared.budget-table', ['rows' => $budgetRows])

    <h2>Processed Claim History</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Submitter</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Reviewed By</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($processedClaims as $claim)
                    <tr>
                        <td>{{ $claim->submitter->name }}</td>
                        <td>{{ $claim->category->name }}</td>
                        <td>{{ number_format($claim->amount, 2) }}</td>
                        <td><span class="badge {{ $claim->status }}">{{ ucfirst($claim->status) }}</span></td>
                        <td>{{ $claim->reviewer?->name ?? '-' }}</td>
                        <td>{{ $claim->manager_comment ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No processed claims yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
