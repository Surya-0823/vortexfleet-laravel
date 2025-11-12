@extends('layouts.master')

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $page_title ?? 'Dashboard' }}</h1>
    <p class="page-subtitle">{{ $page_subtitle ?? 'Welcome!' }}</p>
</div>

<div class="dashboard-widgets">
    <div class="widget">
        <div class="widget-icon">
            <img src="{{ asset('assets/icons/users.svg') }}" alt="Drivers Icon">
        </div>
        <div class="widget-info">
            <span class="widget-value">{{ $driver_count ?? 0 }}</span>
            <span class="widget-label">Total Drivers</span>
        </div>
    </div>
    
    <div class="widget">
        <div class="widget-icon">
            <img src="{{ asset('assets/icons/bus.svg') }}" alt="Buses Icon">
        </div>
        <div class="widget-info">
            <span class="widget-value">{{ $bus_count ?? 0 }}</span>
            <span class="widget-label">Total Buses</span>
        </div>
    </div>
    
    <div class="widget">
        <div class="widget-icon">
            <img src="{{ asset('assets/icons/graduation-cap.svg') }}" alt="Students Icon">
        </div>
        <div class="widget-info">
            <span class="widget-value">{{ $student_count ?? 0 }}</span>
            <span class="widget-label">Total Students</span>
        </div>
    </div>
</div>

@endsection