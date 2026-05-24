@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <section class="page-header">
        <h1>Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }}.</p>
    </section>
@endsection
