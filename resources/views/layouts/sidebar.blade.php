<aside class="sidebar">
    <div class="sidebar-header-wrapper">
        <div class="p-4 border-b border-border flex items-center gap-3 sidebar-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--primary))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bus">
                <path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/>
            </svg>

            <div class="sidebar-title-wrapper">
                <h1 class="sidebar-title">Bus Manager</h1>
                <p class="sidebar-subtitle">Admin Portal</p>
            </div>
            </div>
    </div>

    <nav class="sidebar-nav">
        @php
        // PUTHU MAATRAM: Palaya PHP array-ah Blade-kulla vechirom
        // Namma routes/web.php file-ku etha maathiri 'Dashboard' path-ah maathirukkom
        $navItems = [
            [
                'label' => 'Dashboard', 
                'path' => '/dashboard', // '/' la irunthu '/dashboard' ah maathirukkom
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12"rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>'
            ],
            [
                'label' => 'Drivers', 
                'path' => '/drivers', 
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'
            ],
            [
                'label' => 'Buses', 
                'path' => '/buses', 
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"/><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"/><rect width="20" height="10" x="2" y="8" rx="2"/></svg>'
            ],
            [
                'label' => 'Students', 
                'path' => '/students', 
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20v2H6.5a2.5 2.5 0 0 1 0-5H20v2H6.5a2.5 2.5 0 0 1 0-5H20v2H6.5A2.5 2.5 0 0 1 4 9.5V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1.5"/><path d="M12 13V2l-2 3 2 3 2-3-2-3Z"/></svg>'
            ],
            [
                'label' => 'Routes', 
                'path' => '/routes', 
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1Z"/><line x1="4" x2="4" y1="15" y2="21"/><line x1="12" x2="12" y1="15" y2="21"/><line x1="20" x2="20" y1="15" y2="21"/></svg>'
            ],
            [
                'label' => 'Live Map', 
                'path' => '/tracking', 
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>'
            ],
        ];
        @endphp

        @foreach ($navItems as $item)
            @php
                // PUTHU MAATRAM: Palaya '$_SERVER' logic-ah thookittom
                // Namma ippo Laravel-oda 'request()->is()' helper-ah use panrom
                // 'ltrim' use panni '/' ah remove panrom (e.g., '/drivers' -> 'drivers')
                $isActive = request()->is(ltrim($item['path'], '/'));
                $buttonClass = $isActive ? 'btn btn-primary' : 'btn btn-ghost';
            @endphp
            
            <a href="{{ url($item['path']) }}" title="{{ $item['label'] }}">
                <div class="{{ $buttonClass }} nav-button">
                    
                    <span class="nav-icon">{!! $item['icon_svg'] !!}</span>

                    <span class="nav-label">{{ $item['label'] }}</span>
                </div>
            </a>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <button id="sidebar-toggle" class="sidebar-toggle-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
        </button>
    </div>
</aside>