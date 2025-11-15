@extends('layouts.master')

@section('content')

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">{{ $page_title ?? 'Dashboard' }}</h1>
        <p class="dashboard-subtitle">{{ $page_subtitle ?? "Here's a quick snapshot of your fleet." }}</p>
    </div>

    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-card-content">
                <div class="stat-info">
                    <span class="stat-title">Buses</span>
                    <span class="stat-value">{{ $current_buses ?? 0 }} / {{ $max_buses ?? 5 }}</span>
                </div>
                <div class="stat-icon bg-cyan-gradient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bus"><path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/></svg>
                </div>
            </div>
            <p class="stat-trend">Your current subscription limit.</p>
        </article>

        <article class="stat-card">
            <div class="stat-card-content">
                <div class="stat-info">
                    <span class="stat-title">Students</span>
                    <span class="stat-value">{{ $current_students ?? 0 }} / {{ $max_students ?? 200 }}</span>
                </div>
                <div class="stat-icon bg-pink-gradient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap"><path d="M12 14L21 9L12 4L3 9L12 14Z"/><path d="M12 14V21"/><path d="M7 12L7 17"/></svg>
                </div>
            </div>
            <p class="stat-trend">Based on your bus limit (40 per bus).</p>
        </article>

        <article class="stat-card">
            <div class="stat-card-content">
                <div class="stat-info">
                    <span class="stat-title">Active Drivers</span>
                    <span class="stat-value">{{ $current_drivers ?? 0 }}</span>
                </div>
                <div class="stat-icon bg-purple-gradient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card"><path d="M12 12h.01"/><path d="M7 10h5"/><path d="M7 14h8"/><rect width="18" height="14" x="3" y="5" rx="2"/></svg>
                </div>
            </div>
            <p class="stat-trend">Total verified drivers in your fleet.</p>
        </article>

        <article class="stat-card">
            <div class="stat-card-content">
                <div class="stat-info">
                    <span class="stat-title">Active Routes</span>
                    <span class="stat-value">{{ $current_routes ?? 0 }}</span>
                </div>
                <div class="stat-icon bg-success-gradient"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-route"><path d="M12 20a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1Z"/><path d="M12 4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1Z"/><path d="M19 12a1 1 0 0 1-1-1h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v4Z"/><path d="M5 12a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H5Z"/><path d="M17.8 7.8a1 1 0 0 1-1.4 0l-.7-.7a1 1 0 0 1 0-1.4l.7-.7a1 1 0 0 1 1.4 0l.7.7a1 1 0 0 1 0 1.4l-.7.7Z"/><path d="M6.2 19.8a1 1 0 0 1-1.4 0l-.7-.7a1 1 0 0 1 0-1.4l.7-.7a1 1 0 0 1 1.4 0l.7.7a1 1 0 0 1 0 1.4l-.7.7Z"/><path d="M6.2 7.8a1 1 0 0 1 0 1.4l-.7.7a1 1 0 0 1-1.4 0l-.7-.7a1 1 0 0 1 0-1.4l.7-.7a1 1 0 0 1 1.4 0l.7.7Z"/><path d="M17.8 19.8a1 1 0 0 1 0 1.4l-.7.7a1 1 0 0 1-1.4 0l-.7-.7a1 1 0 0 1 0-1.4l.7-.7a1 1 0 0 1 1.4 0l.7.7Z"/></svg>
                </div>
            </div>
            <p class="stat-trend">Total routes configured in the system.</p>
        </article>
    </section>

    <div class="dashboard-main-content">
        <section class="activity-section">
            <h2 class="section-title">Action Items</h2>
            <div class="activity-list">

                @if(!isset($pending_drivers) || ($pending_drivers == 0 && $pending_students == 0 && $buses_without_driver == 0 && $routes_without_bus == 0))
                    <div class="action-item-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle-2"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        <span>No pending actions. Everything looks good.</span>
                    </div>
                @endif

                @if(isset($pending_drivers) && $pending_drivers > 0)
                <a href="{{ url('/drivers') }}" class="action-item-link">
                    <div class="action-item status-pending">
                        <div class="action-info">
                            <div class="action-title">Pending Drivers</div>
                            <div class="action-subtitle">{{ $pending_drivers }} {{ $pending_drivers > 1 ? 'drivers' : 'driver' }} need verification.</div>
                        </div>
                        <div class="action-cta">Review</div>
                    </div>
                </a>
                @endif

                @if(isset($pending_students) && $pending_students > 0)
                <a href="{{ url('/students') }}" class="action-item-link">
                    <div class="action-item status-pending">
                        <div class="action-info">
                            <div class="action-title">Pending Students</div>
                            <div class="action-subtitle">{{ $pending_students }} {{ $pending_students > 1 ? 'students' : 'student' }} need approval.</div>
                        </div>
                        <div class="action-cta">Review</div>
                    </div>
                </a>
                @endif

                @if(isset($buses_without_driver) && $buses_without_driver > 0)
                <a href="{{ url('/buses') }}" class="action-item-link">
                    <div class="action-item status-warning">
                        <div class="action-info">
                            <div class="action-title">Buses without Drivers</div>
                            <div class="action-subtitle">{{ $buses_without_driver }} {{ $buses_without_driver > 1 ? 'buses' : 'bus' }} are not assigned a driver.</div>
                        </div>
                        <div class="action-cta">Assign</div>
                    </div>
                </a>
                @endif

                @if(isset($routes_without_bus) && $routes_without_bus > 0)
                <a href="{{ url('/routes') }}" class="action-item-link">
                    <div class="action-item status-warning">
                        <div class="action-info">
                            <div class="action-title">Routes without Buses</div>
                            <div class="action-subtitle">{{ $routes_without_bus }} {{ $routes_without_bus > 1 ? 'routes' : 'route' }} are not assigned a bus.</div>
                        </div>
                        <div class="action-cta">Assign</div>
                    </div>
                </a>
                @endif

            </div>
        </section>

        </div>
</div>

@endsection