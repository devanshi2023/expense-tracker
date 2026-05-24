@extends('layouts.app', ['title' => 'Submit Claim'])

@section('content')
    <section class="page-header">
        <div>
            <span class="eyebrow">New request</span>
            <h1>Submit Expense Claim</h1>
            <p>Fill the form below to create a new claim for manager review.</p>
        </div>
        <a href="{{ route('team.claims.index') }}" class="button secondary">Back to claims</a>
    </section>

    <form method="POST" action="{{ route('team.claims.store') }}" class="form panel">
        @include('team.claims._form', ['buttonText' => 'Submit Claim'])
    </form>
@endsection
