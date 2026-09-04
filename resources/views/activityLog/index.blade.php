@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<main class="workspace">
    <div class="page-container">
        <div class="form-index-header">
            <div class="page-header-left">
                <div class="page-header-title">
                    <i class="fa fa-history"></i>
                    <h1>Activity Logs</h1>
                </div>
                <p class="page-subtitle">Track and monitor admin actions across all modules.</p>
            </div>
        </div>

        <div class="data-table-container">
            <div class="table-header-row">
                <span class="table-title">List Activity Logs</span>
                <input
                    id="logsSearch"
                    type="text"
                    placeholder="Search logs…"
                    oninput="filterLogs(this.value)"
                    class="ios-input"
                    style="max-width:200px;">
            </div>
            <table class="projects-table" id="logsTable">
                <thead>
                    <tr>
                        <th style="text-align: center;">Date & Time</th>
                        <th>User</th>
                        <th style="text-align: center;">Module</th>
                        <th style="text-align: center;">Action</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="logsBody">
                    @forelse ($logs as $index => $log)
                        <tr class="log-row"
                            data-user="{{ strtolower($log->admin->name ?? '') }}"
                            data-module="{{ strtolower($log->module) }}"
                            data-action="{{ strtolower($log->action) }}">
                            <td style="color:var(--text-secondary); font-size: 0.85rem; text-align: center;">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}</td>
                            <td style="font-weight:500;">{{ $log->admin->name ?? 'System' }}</td>
                            <td style="text-align: center;">
                                <span class="module-badge" style="text-transform: capitalize;">{{ str_replace('_', ' ', $log->module) }}</span>
                            </td>
                            <td style="text-align: center; font-weight:500;">
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
                            <td style="color:var(--text-secondary); font-size: 0.85rem; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->description }}">
                                {{ $log->description ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:52px 20px; color:var(--text-secondary);">
                                <div style="font-weight:600; margin-bottom:4px;">No logs yet</div>
                                <div style="font-size:0.83rem;">No activities have been recorded.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination" style="margin: 10px 20px; justify-content: flex-end;">
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>

            <div id="noResults" style="display:none; text-align:center; padding:48px 20px; color:var(--text-secondary);">                <div style="font-weight:600;">No results found</div>
                <div style="font-size:0.83rem; margin-top:4px;">Try a different keyword.</div>
            </div>
        </div>

    </div>
</main>

<script>
    function filterLogs(query) {
        const q = query.toLowerCase().trim();
        const rows = document.querySelectorAll('.log-row');
        let visible = 0;

        rows.forEach(row => {
            const match = (row.dataset.user + row.dataset.module + row.dataset.action).includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        document.getElementById('noResults').style.display =
            (visible === 0 && q.length > 0) ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('nav-activitylog');
        if (el) el.classList.add('active');
    });
</script>
@endsection
