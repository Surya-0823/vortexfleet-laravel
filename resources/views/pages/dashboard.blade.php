{{-- Namma 'master.blade.php' layout-ah use panrom --}}
@extends('layouts.master')

{{-- Namma content inga thaan start aaguthu nu solrom --}}
@section('content')

<div class="dashboard-container">
    <div class="stats-grid">
        @php
        // Controller la irunthu vara variables ah inga use panrom
        $stats = [
            ['title' => 'Total Drivers', 'value' => $driver_count, 'trend' => '+2 this week', 'icon_bg' => 'bg-purple-gradient', 'icon' => 'users.svg'],
            ['title' => 'Active Buses', 'value' => $bus_count, 'trend' => '3 in maintenance', 'icon_bg' => 'bg-cyan-gradient', 'icon' => 'bus.svg'],
            ['title' => 'Students', 'value' => $student_count, 'trend' => '+12 this month', 'icon_bg' => 'bg-success-gradient', 'icon' => 'graduation-cap.svg'],
        ];
        @endphp

        @foreach ($stats as $stat)
            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-info">
                        <p class="stat-title">{{ $stat['title'] }}</p>
                        <p class="stat-value">{{ $stat['value'] }}</p>
                    </div>
                    <div class="stat-icon {{ $stat['icon_bg'] }}">
                        @php
                        // Icon path-ah correct panrom
                        $icon_path = BASE_PATH . '/public/assets/icons/' . $stat['icon'];
                        if (file_exists($icon_path)) {
                            // file_get_contents use pannathala, ippadi "un-escape" pannanum
                            echo file_get_contents($icon_path);
                        }
                        @endphp
                    </div>
                </div>
                <p class="stat-trend">{{ $stat['trend'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="dashboard-main-content">
        {{-- Inga content varalaam --}}
    </div>
</div>

@endsection