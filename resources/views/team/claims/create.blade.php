@extends('layouts.app', ['title' => 'Submit Claim'])

@section('content')
    <section class="page-header">
        <h1>Submit Expense Claim</h1>
        <a href="{{ route('team.claims.index') }}">Back to claims</a>
    </section>

    <form method="POST" action="{{ route('team.claims.store') }}" class="form panel">
        @include('team.claims._form', ['buttonText' => 'Submit Claim'])
    </form>
@endsection
