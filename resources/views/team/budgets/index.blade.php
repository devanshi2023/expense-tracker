@extends('layouts.app', ['title' => 'Budget Utilization'])

@section('content')
    <section class="page-header row">
        <div>
            <h1>Budget Utilization</h1>
            <p>Approved spend by category for the selected calendar month.</p>
        </div>
        <a href="{{ route('team.claims.index') }}">Back to claims</a>
    </section>

    <form method="GET" class="filters">
        <input type="month" name="month" value="{{ $month }}">
        <button type="submit">View Month</button>
    </form>

    @include('shared.budget-table', ['rows' => $rows])
@endsection
