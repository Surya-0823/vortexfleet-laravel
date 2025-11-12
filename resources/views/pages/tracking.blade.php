@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<div class="page-header">
    <div class="header-left-content">
        <h1 class="page-title">{{ $page_title ?? 'Live Tracking' }}</h1>
        <p class="page-subtitle">{{ $page_subtitle ?? 'Monitor every active bus and driver in real time.' }}</p>
    </div>
</div>

<div class="tracking-layout">
    <div class="tracking-map-card">
        <div id="map"></div>
    </div>

    <aside class="tracking-sidebar">
        <div class="tracking-sidebar__header">
            <h2>Live Drivers</h2>
            <span id="live-driver-count" class="tracking-sidebar__badge">0</span>
        </div>
        <div id="live-driver-list" class="tracking-sidebar__list">
            <div class="tracking-sidebar__empty" id="live-driver-empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-radar">
                    <path d="M19.5 12A7.5 7.5 0 1 0 12 19.5"/><path d="M12 7v5l3 3"/></svg>
                <p>No buses are reporting live location yet.</p>
            </div>
        </div>
    </aside>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
@endsection