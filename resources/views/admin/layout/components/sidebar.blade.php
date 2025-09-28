<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-category">Main</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
         @if (auth()->user()->role === 'admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <span class="icon-bg"><i class="mdi mdi-crosshairs-gps menu-icon"></i></span>
                <span class="menu-title">Users</span>

            </a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#filterMenu" aria-expanded="false" aria-controls="filterMenu">
                <span class="icon-bg"><i class="mdi mdi-filter-outline menu-icon"></i></span>
                <span class="menu-title">Filters</span>
                <i class="menu-arrow" style="transform: rotate(180deg)"></i>
            </a>
            <div class="collapse" id="filterMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.topic.index') }}">Topics</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.guides.index') }}">Guides</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.levels.index') }}">Levels</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.country.index') }}">Country</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.category.index') }}">Categories</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.video.index') }}">
                <span class="icon-bg"><i class="mdi mdi-format-list-bulleted menu-icon"></i></span>
                <span class="menu-title">Youtube Videos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.series.index') }}">
                <span class="icon-bg"><i class="mdi mdi-format-list-bulleted menu-icon"></i></span>
                <span class="menu-title">Youtube Series</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.page.index') }}">
                <span class="icon-bg"><i class="mdi mdi-format-list-bulleted menu-icon"></i></span>
                <span class="menu-title">Pages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.blog.index') }}">
                <span class="icon-bg"><i class="mdi mdi-format-list-bulleted menu-icon"></i></span>
                <span class="menu-title">Blogs</span>
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.contactus.index') }}">
                <span class="icon-bg"><i class="mdi mdi-format-list-bulleted menu-icon"></i></span>
                <span class="menu-title">Messages</span>
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.newsletter.index') }}">
                <span class="icon-bg"><i class="mdi mdi-format-list-bulleted menu-icon"></i></span>
                <span class="menu-title">Send News Letter</span>
            </a>
        </li>
         @if (auth()->user()->role === 'admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.setting.index') }}">
                <span class="icon-bg"><i class="mdi mdi-format-list-bulleted menu-icon"></i></span>
                <span class="menu-title">Settings</span>
            </a>
        </li>
        @endif

        {{-- <li class="nav-item sidebar-user-actions">
            <div class="sidebar-user-menu">
                <a href="#" class="nav-link"><i class="mdi mdi-settings menu-icon"></i>
                    <span class="menu-title">Settings</span>
                </a>
            </div>
        </li> --}}

        <li class="nav-item sidebar-user-actions">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <div class="sidebar-user-menu">
                <!-- Change the anchor tag's action to submit the form via JS -->
                <a href="#" class="nav-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout menu-icon"></i>
                    <span class="menu-title">Log Out</span>
                </a>
            </div>
        </li>

    </ul>
</nav>
