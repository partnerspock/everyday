<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Everyday</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('everyday-theme');
            if (savedTheme === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
            } else {
                document.documentElement.setAttribute('data-theme', 'amoled');
            }
        })();
    </script>

    @stack('styles')
    <script src="https://kit.fontawesome.com/c66aa74d08.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Sidebar dibuat Flex Container full height (100vh) -->
<aside class="sidebar" style="position: relative; padding-bottom: 50px;">
        <div class="sidebar-logo" style="justify-content:center; flex-shrink: 0;">
            <span class="sidebar-logo-text"><i class="fa-brands fa-accusoft"></i></span>
            <span class="sidebar-logo-text">Everyday</span>
        </div>
        @auth
        <div class="sidebar-user" style="flex-shrink: 0;">
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">
                    <span title="{{ Auth::user()->name }}">
                        {{ Auth::user()->name }}
                        @if(Auth::user()->roles->first())
                            | {{ Auth::user()->roles->first()->role_name }}
                        @endif
                    </span>
                </div>
                <div class="sidebar-user-role">{{ Auth::user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout" title="Log Out">
                    <i class="fa fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
        @endauth
        <nav class="nav-menu" style="flex: 1; overflow-y: auto;">
            <a href="{{ route('home') }}" style="text-decoration:none;">
                <div class="nav-item {{ request()->routeIs('home.*') ? 'active' : '' }}" id="nav-home">
                    <i class="fa fa-tachograph-digital" style="width:16px;text-align:center;"></i>
                    Dashboard
                </div>
            </a>
            <div class="nav-section-title">Personal</div>
            <a href="{{ route('gnotes.index') }}" style="text-decoration:none;">
                <div class="nav-item {{ request()->routeIs('gnotes.*') ? 'active' : '' }}" id="nav-general_notes">
                    <i class="fa fa-note-sticky" style="width:16px;text-align:center;"></i>
                    General Notes
                </div>
            </a>
            <div class="nav-section-title {{ request()->routeIs('spaces.*') ? 'active' : '' }}" id="nav-spaces-top" style="justify-content: space-between; cursor: pointer;">
                <span>Team Spaces</span>
                <span id="spaces-expand-indicator" class="indicator">▼</span>
            </div>
            <div class="spaces-submenu open" id="spaces-submenu-list">
                <button type="button" class="btn-create-space" id="sidebar-create-space-btn-li" onclick="openCreateSpaceModal()">
                    <i class="fa fa-plus"></i> Create Space
                </button>
                @auth
                    @php
                        $mySpaces = \App\Models\Space::where('created_user', Auth::id())->get();
                    @endphp
                    @foreach($mySpaces as $space)
                        <a href="{{ route('spaces.index', $space->id) }}" style="text-decoration:none;">
                            <div class="nav-item space-item {{ (request()->routeIs('spaces.*') && request()->route('id') == $space->id) ? 'active' : '' }}">
                                <i class="fa fa-home" style="width:16px;text-align:center;"></i>
                                <span>{{ $space->title_spaces }}</span>
                            </div>
                        </a>
                    @endforeach
                @endauth
            </div>
            <div class="nav-section-title">Administration</div>
            <a href="{{ route('admins.index') }}" style="text-decoration:none;">
                <div class="nav-item {{ request()->routeIs('admins.*') ? 'active' : '' }}" id="nav-admins">
                    <i class="fa fa-users" style="width:16px;text-align:center;"></i>
                    Admin Users
                </div>
            </a>
            <a href="{{ route('roles.index') }}" style="text-decoration:none;">
                <div class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}" id="nav-roles">
                    <i class="fa fa-key" style="width:16px;text-align:center;"></i>
                    Roles
                </div>
            </a>
            <a href="{{ route('permissions.index') }}" style="text-decoration:none;">
                <div class="nav-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}" id="nav-permissions">
                    <i class="fa fa-sliders" style="width:16px;text-align:center;"></i>
                    Permissions
                </div>
            </a>
            <a href="{{ route('activityLog.index') }}" style="text-decoration:none;">
                <div class="nav-item {{ request()->routeIs('activityLog.*') ? 'active' : '' }}" id="nav-activitylog">
                    <i class="fa fa-clipboard-list" style="width:16px;text-align:center;"></i>
                    Activity Log
                </div>
            </a>
            <div class="nav-section-title">Preference</div>
            <div class="ios-toggle-row" id="theme-toggle-row">
                <div class="ios-toggle-label">
                    <i class="fa fa-circle-half-stroke" style="width:16px;text-align:center;"></i>
                    <span id="theme-label">Light Mode</span>
                </div>
                <label class="ios-toggle" for="theme-checkbox" onclick="event.stopPropagation()">
                    <input type="checkbox" id="theme-checkbox">
                    <span class="ios-toggle-track"></span>
                </label>
            </div>
        </nav>

        <a href="{{ route('portfolio') }}" style="text-decoration: none; position: absolute; bottom: 0; left: 0; right: 0; background: inherit; padding: 12px 0; border-top: 1px solid var(--separator);">
            <div class="sidebar-logo" style="justify-content: center; margin: 0;">
                <span class="sidebar-logo-text" style="font-size: 10px; font-weight: 300; color: #999;">@2026 Everyday. All rights reserved.</span>
            </div>
        </a>
    </aside>
    <div class="main-content">
        @yield('content')
    </div>
    <div id="createSpaceModal" class="custom-modal-overlay" style="display: none;" onclick="handleOverlayClick(event)">
        <div class="custom-modal-card">
            <h3>Create New Space</h3>
            <form action="{{ route('spaces.store') }}" method="POST">
                @csrf
                <div style="margin: 15px 0;">
                    <input type="text" name="title_spaces" id="title_spaces" class="ios-input" placeholder="e.g. Marketing Team" required style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc; box-sizing: border-box;">
                </div>

                <div class="custom-modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeCreateSpaceModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Create Space</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateSpaceModal() {
            const modal = document.getElementById('createSpaceModal');
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    document.getElementById('title_spaces').focus();
                }, 100);
            }
        }

        function closeCreateSpaceModal() {
            const modal = document.getElementById('createSpaceModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function handleOverlayClick(event) {
            if (event.target.id === 'createSpaceModal') {
                closeCreateSpaceModal();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const html       = document.documentElement;
            const checkbox   = document.getElementById('theme-checkbox');
            const themeLabel = document.getElementById('theme-label');
            const toggleRow  = document.getElementById('theme-toggle-row');

            const currentTheme = html.getAttribute('data-theme') || 'amoled';
            const isLight = currentTheme === 'light';

            if (checkbox) checkbox.checked = isLight;
            if (themeLabel) themeLabel.textContent = isLight ? 'Light Mode' : 'Dark Mode';

            function updateTheme(toLight) {
                html.setAttribute('data-theme', toLight ? 'light' : 'amoled');
                if (checkbox) checkbox.checked = toLight;
                if (themeLabel) themeLabel.textContent = toLight ? 'Light Mode' : 'Dark Mode';
                localStorage.setItem('everyday-theme', toLight ? 'light' : 'amoled');
            }

            if (checkbox) checkbox.addEventListener('change', () => updateTheme(checkbox.checked));
            if (toggleRow) toggleRow.addEventListener('click', () => updateTheme(!checkbox.checked));

            const trigger = document.getElementById('nav-spaces-top');
            const submenu = document.getElementById('spaces-submenu-list');

            if (trigger && submenu) {
                trigger.addEventListener('click', () => {
                    trigger.classList.toggle('active');
                    submenu.classList.toggle('open');
                });
            }
        });
    </script>
</body>
</html>