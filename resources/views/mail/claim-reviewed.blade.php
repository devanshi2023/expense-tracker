<p>Hello {{ $claim->submitter->name }},</p>

<p>Your expense claim has been {{ $claim->status }}.</p>

<p><strong>Category:</strong> {{ $claim->category->name }}</p>
<p><strong>Amount:</strong> {{ number_format($claim->amount, 2) }}</p>
<p><strong>Claim Date:</strong> {{ $claim->claim_date->format('M d, Y') }}</p>
<p><strong>Status:</strong> {{ ucfirst($claim->status) }}</p>

@if ($claim->manager_comment)
    <p><strong>Comment:</strong> {{ $claim->manager_comment }}</p>
@endif

<p>Reviewed by: {{ $claim->reviewer?->name ?? 'System' }}</p>
