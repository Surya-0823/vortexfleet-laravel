@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<div class="page-header drivers-page-header">
    <div class="header-left-content">
        <h1 class="page-title">{{ $page_title ?? 'Live Map' }}</h1>
        <p class="page-subtitle">{{ $page_subtitle ?? 'Monitor all active buses' }}</p>
    </div>
</div>
<div class="page-container" style="padding: 0; margin: 0; height: calc(100vh - 160px);">
    <div id="map" style="width: 100%; height: 100%; border-radius: 8px;"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
@endsection