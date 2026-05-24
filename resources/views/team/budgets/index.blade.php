@extends('layouts.app', ['title' => 'Budget Utilization'])

@section('content')
    <section class="hero-panel">
        <div class="page-header">
            <div>
                <span class="eyebrow">Budget dashboard</span>
                <h1>Budget Utilization</h1>
                <p>Approved spend by category for the selected calendar month, now in a dashboard-style layout.</p>
            </div>
            <div class="actions">
                <a href="{{ route('team.claims.index') }}" class="button secondary">Back to claims</a>
            </div>
        </div>

        <form method="GET" class="filters">
            <input type="month" name="month" value="{{ $month }}">
            <button type="submit">View Month</button>
        </form>
    </section>

    @include('shared.budget-table', ['rows' => $rows])
@endsection
