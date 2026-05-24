@extends('layouts.app', ['title' => 'Manager Dashboard'])

@section('content')
    <section class="page-header">
        <div>
            <h1>Manager Dashboard</h1>
            <p>Review pending claims, check budget utilization for the current month, and track processed claim history.</p>
        </div>
    </section>

    <section class="section-block">
        <h2>Pending Claims</h2>
        <p class="section-copy">Approve or reject team member claims. Self-approval stays blocked at the controller level.</p>
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
                                            <button
                                                type="submit"
                                                name="status"
                                                value="approved"
                                                @if (($approvalWarnings[$claim->id]['exceeds'] ?? false))
                                                    data-approval-confirm="{{ $approvalWarnings[$claim->id]['message'] }} Do you still want to approve this claim?"
                                                @endif
                                            >
                                                Approve
                                            </button>
                                            <button type="submit" name="status" value="rejected" class="danger-button">Reject</button>
                                        </div>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">No pending claims right now.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="section-block">
        <h2>Budget Utilization</h2>
        <p class="section-copy">Current month approved spend per category versus the budget limit.</p>
        @include('shared.budget-table', ['rows' => $budgetRows])
    </section>

    <section class="section-block">
        <h2>Processed Claims</h2>
        <p class="section-copy">History of reviewed claims with the manager comment visible.</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Submitter</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($processedClaims as $claim)
                        <tr>
                            <td>{{ $claim->submitter->name }}</td>
                            <td>{{ optional($claim->reviewed_at ?? $claim->claim_date)->format('M d, Y') }}</td>
                            <td>{{ $claim->category->name }}</td>
                            <td>{{ number_format($claim->amount, 2) }}</td>
                            <td><span class="badge {{ $claim->status }}">{{ ucfirst($claim->status) }}</span></td>
                            <td>{{ $claim->manager_comment ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">No processed claims yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="section-block">
        <h2>All Claims</h2>
        <p class="section-copy">Full claim list across pending, approved, and rejected records from team submissions.</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Submitter</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allClaims as $claim)
                        <tr>
                            <td>{{ $claim->submitter->name }}</td>
                            <td>{{ $claim->claim_date->format('M d, Y') }}</td>
                            <td>{{ $claim->category->name }}</td>
                            <td>{{ number_format($claim->amount, 2) }}</td>
                            <td><span class="badge {{ $claim->status }}">{{ ucfirst($claim->status) }}</span></td>
                            <td>{{ $claim->manager_comment ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">No claims found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
