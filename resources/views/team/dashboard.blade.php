@extends('layouts.app', ['title' => 'Team Dashboard'])

@section('content')
    <section class="page-header">
        <h1>Team Member Dashboard</h1>
        <p>Submit and track your expense claims.</p>
    </section>

    <a class="button" href="{{ route('team.claims.index') }}">Open My Claims</a>
@endsection
