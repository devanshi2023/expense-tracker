@extends('layouts.app', ['title' => 'Edit Claim'])

@section('content')
    <section class="page-header">
        <h1>Edit Expense Claim</h1>
        <a href="{{ route('team.claims.index') }}">Back to claims</a>
    </section>

    <form method="POST" action="{{ route('team.claims.update', $claim) }}" class="form panel">
        @method('PUT')
        @include('team.claims._form', ['buttonText' => 'Update Claim'])
    </form>
@endsection
