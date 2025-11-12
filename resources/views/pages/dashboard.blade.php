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
                    <span class="stat-title">Active Drivers</span>
                    <span class="stat-value">{{ $driver_count ?? 0 }}</span>
                </div>
                <div class="stat-icon bg-purple-gradient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card"><path d="M12 12h.01"/><path d="M7 10h5"/><path d="M7 14h8"/><rect width="18" height="14" x="3" y="5" rx="2"/></svg>
                </div>
            </div>
            <p class="stat-trend">Drivers that are verified and ready to operate.</p>
        </article>

        <article class="stat-card">
            <div class="stat-card-content">
                <div class="stat-info">
                    <span class="stat-title">Buses In Service</span>
                    <span class="stat-value">{{ $bus_count ?? 0 }}</span>
                </div>
                <div class="stat-icon bg-cyan-gradient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bus"><path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/></svg>
                </div>
            </div>
            <p class="stat-trend">Includes all buses currently assigned to routes.</p>
        </article>

        <article class="stat-card">
            <div class="stat-card-content">
                <div class="stat-info">
                    <span class="stat-title">Students Onboarded</span>
                    <span class="stat-value">{{ $student_count ?? 0 }}</span>
                </div>
                <div class="stat-icon bg-pink-gradient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap"><path d="M12 14L21 9L12 4L3 9L12 14Z"/><path d="M12 14V21"/><path d="M7 12L7 17"/></svg>
                </div>
            </div>
            <p class="stat-trend">Verified students with access to the tracking app.</p>
        </article>
    </section>

    <div class="dashboard-main-content">
        <section class="activity-section">
            <h2 class="section-title">Recent Insights</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div>
                        <div class="activity-title">Driver Verification</div>
                        <div class="activity-subtitle">Keep your driver roster updated with latest documents.</div>
                    </div>
                    <div class="activity-meta">
                        <span class="activity-time">{{ now()->format('M d') }}</span>
                    </div>
                </div>
                <div class="activity-item">
                    <div>
                        <div class="activity-title">Route Performance</div>
                        <div class="activity-subtitle">Monitor route assignments to ensure every bus has coverage.</div>
                    </div>
                    <div class="activity-meta">
                        <span class="activity-time">Weekly reminder</span>
                    </div>
                </div>
                <div class="activity-item">
                    <div>
                        <div class="activity-title">Student Onboarding</div>
                        <div class="activity-subtitle">Invite new students and trigger OTP verification in one click.</div>
                    </div>
                    <div class="activity-meta">
                        <span class="activity-time">Ongoing</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="quick-actions-section">
            <h2 class="section-title">Quick Actions</h2>
            <div class="quick-actions-grid">
                <a href="{{ url('/drivers') }}" class="quick-action-btn">Add Driver</a>
                <a href="{{ url('/buses') }}" class="quick-action-btn">Register Bus</a>
                <a href="{{ url('/students') }}" class="quick-action-btn">Invite Student</a>
                <a href="{{ url('/routes') }}" class="quick-action-btn">Create Route</a>
            </div>
        </section>
    </div>
</div>

@endsection