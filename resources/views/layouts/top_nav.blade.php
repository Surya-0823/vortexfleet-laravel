<header class="top-nav">
    <div class="breadcrumbs">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-home"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>/</span>
        <span class="breadcrumb-active">{{ $page_title ?? 'Dashboard' }}</span>
    </div>

    <div class="user-actions">
        <div class="user-profile-card">
            <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ Session::get('user_name', 'Admin') }}" alt="Admin Avatar" class="user-profile-avatar">
            <div class="user-profile-info">
                <span class="user-name">{{ Session::get('user_name', 'Admin User') }}</span>
                <span class="user-role">Administrator</span>
            </div>
        </div>
        <a href="{{ url('/logout') }}" class="logout-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            <span>Logout</span>
        </a>
    </div>
</header>