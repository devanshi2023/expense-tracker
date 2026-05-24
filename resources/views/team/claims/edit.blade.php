@extends('layouts.app', ['title' => 'Edit Claim'])

@section('content')
    <section class="page-header">
        <div>
            <span class="eyebrow">Update request</span>
            <h1>Edit Expense Claim</h1>
            <p>Adjust pending claim details before the review is completed.</p>
        </div>
        <a href="{{ route('team.claims.index') }}" class="button secondary">Back to claims</a>
    </section>

    <form method="POST" action="{{ route('team.claims.update', $claim) }}" class="form panel">
        @method('PUT')
        @include('team.claims._form', ['buttonText' => 'Update Claim'])
    </form>
@endsection
