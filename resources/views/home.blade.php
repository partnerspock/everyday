@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="workspace">
    <div class="page-container" style="padding-bottom: 60px;">

        <div class="form-index-header">
            <div class="page-header-left">
                <div class="page-header-title">
                    <i class="fa fa-tachograph-digital"></i>
                    <h1>Dashboard</h1>
                </div>
                <p class="page-subtitle">
                    Welcome back, <strong>{{ Auth::user()->name }}</strong>
                    &nbsp;|&nbsp; {{ now()->format('l, d F Y') }}
                </p>
            </div>
        </div>

        <div class="stat-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="stat-card">
                <span class="stat-label"><i class="fa fa-users" style="margin-right:6px;"></i>Admin Users</span>
                <span class="stat-value">{{ $stats['admins'] }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label"><i class="fa fa-key" style="margin-right:6px;"></i>Roles</span>
                <span class="stat-value">{{ $stats['roles'] }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label"><i class="fa fa-sliders" style="margin-right:6px;"></i>Permissions</span>
                <span class="stat-value">{{ $stats['permissions'] }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label"><i class="fa fa-home" style="margin-right:6px;"></i>Team Spaces</span>
                <span class="stat-value stat-value--blue">{{ $stats['spaces'] }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label"><i class="fa fa-note-sticky" style="margin-right:6px;"></i>General Notes</span>
                <span class="stat-value">{{ $stats['gnotes'] }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label"><i class="fa fa-clipboard-list" style="margin-right:6px;"></i>Total Logs</span>
                <span class="stat-value stat-value--red">{{ $stats['logs'] }}</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; margin-top: 32px;">
            <div class="data-table-container">
                <div class="table-header-row">
                    <span class="table-title"><i class="fa fa-home" style="margin-right:6px;"></i>My Spaces</span>
                    <a href="#" onclick="openCreateSpaceModal(); return false;" class="btn-primary" style="gap:6px; font-size:0.82rem;">
                        <i class="fa fa-plus"></i> New
                    </a>
                </div>
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>Space</th>
                            <th style="text-align:center;">Notes</th>
                            <th style="text-align:center;">Projects</th>
                            <th style="text-align:center;">Reminders</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mySpaces as $space)
                        <tr>
                            <td style="font-weight:600;"><a href="{{ route('spaces.index', $space->id) }}" style="text-decoration: none; color: var(--text-primary);">{{ $space->title_spaces }} <i class="fa fa-circle-chevron-right"></i></a></td>
                            <td style="text-align:center;">
                                <span class="module-badge">{{ $space->sub_note_count }}</span>
                            </td>
                            <td style="text-align:center;">
                                <span class="module-badge">{{ $space->sub_project_count }}</span>
                            </td>
                            <td style="text-align:center;">
                                <span class="module-badge">{{ $space->sub_reminder_count }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:36px; color:var(--text-secondary);">
                                No spaces yet. Create your first space!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="data-table-container">
                <div class="table-header-row">
                    <span class="table-title"><i class="fa fa-note-sticky" style="margin-right:6px;"></i>General Notes</span>
                    <a href="{{ route('gnotes.index') }}" class="btn-secondary" style="font-size:0.82rem; gap:4px;">
                        See All <i class="fa fa-chevron-right"></i>
                    </a>
                </div>
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th style="text-align:center; width:70px;">Locked</th>
                            <!--<th>Updated</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentGnotes as $gnote)
                        <tr>
                            <td style="font-weight:500; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $gnote->title_notes }}
                            </td>
                            <td style="text-align:center;">
                                @if(!empty($gnote->password))
                                    <i class="fa fa-lock" style="color:var(--ios-orange);" title="Password Protected"></i>
                                @else
                                    <i class="fa fa-lock-open" style="color:var(--text-tertiary);"></i>
                                @endif
                            </td>
                            <!--<td style="color:var(--text-secondary); font-size:0.82rem;">
                                {{ $gnote->updated_at->diffForHumans() }}
                            </td>-->
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center; padding:36px; color:var(--text-secondary);">
                                Let's create your first notes.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Quick Actions ── --}}
        <div style="margin-top: 32px;">
            <p class="page-subtitle" style="margin-bottom: 14px; font-weight:600; color:var(--text-secondary); text-transform: uppercase; font-size:0.72rem; letter-spacing:0.8px;">Quick Actions</p>
            <div style="display:flex; flex-wrap:wrap; gap:12px;">
                <a href="{{ route('gnotes.create') }}" class="btn-primary" style="gap:8px;">
                    <i class="fa fa-pen"></i> New General Note
                </a>
                <a href="{{ route('admins.create') }}" class="btn-secondary" style="gap:8px;">
                    <i class="fa fa-user-plus"></i> New Admin
                </a>
                <a href="{{ route('roles.create') }}" class="btn-secondary" style="gap:8px;">
                    <i class="fa fa-key"></i> New Role
                </a>
                <a href="{{ route('permissions.create') }}" class="btn-secondary" style="gap:8px;">
                    <i class="fa fa-sliders"></i> New Permission
                </a>
                <a href="{{ route('activityLog.index') }}" class="btn-secondary" style="gap:8px;">
                    <i class="fa fa-clipboard-list"></i> View All Logs
                </a>
            </div>
        </div>

        {{-- ── Recent Activity Log ── --}}
        <div style="margin-top: 32px;">
            <div class="data-table-container">
                <div class="table-header-row">
                    <span class="table-title"><i class="fa fa-clipboard-list" style="margin-right:6px;"></i>Recent Activity</span>
                    <a href="{{ route('activityLog.index') }}" class="btn-secondary" style="font-size:0.82rem; gap:4px;">
                        See All <i class="fa fa-chevron-right"></i>
                    </a>
                </div>
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th style="text-align:center; width:140px;">Date & Time</th>
                            <th>User</th>
                            <th style="text-align:center;">Module</th>
                            <th style="text-align:center;">Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                        <tr>
                            <td style="color:var(--text-secondary); font-size:0.82rem; text-align:center; white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td style="font-weight:500;">{{ $log->admin->name ?? 'System' }}</td>
                            <td style="text-align:center;">
                                <span class="module-badge" style="text-transform:capitalize;">
                                    {{ str_replace('_', ' ', $log->module) }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                @php
                                    $action = strtolower($log->action);
                                    $color = match(true) {
                                        str_contains($action, 'delete') => ['rgba(255,69,58,0.1)', 'var(--ios-red)'],
                                        str_contains($action, 'create') => ['rgba(48,209,88,0.1)', 'var(--ios-green)'],
                                        str_contains($action, 'login')  => ['rgba(255,159,10,0.1)', 'var(--ios-orange)'],
                                        default                         => ['rgba(0,122,255,0.1)', 'var(--ios-blue)'],
                                    };
                                @endphp
                                <span class="module-badge" style="background-color:{{ $color[0] }}; color:{{ $color[1] }}; text-transform:capitalize;">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td style="color:var(--text-secondary); font-size:0.83rem; max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $log->description }}">
                                {{ $log->description ?: '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:40px; color:var(--text-secondary);">No activity recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // No specific nav item to highlight for home — leave all as default
    });
</script>
@endsection