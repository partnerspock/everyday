@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
<main class="workspace">
    <div class="page-container">
        <div class="form-index-header">
            <div class="page-header-left">
                <div class="page-header-title">
                    <i class="fa fa-sliders"></i>
                    <h1>Permissions</h1>
                </div>
                <p class="page-subtitle">Manage access control permissions across all modules.</p>
            </div>
            <a href="{{ route('permissions.create') }}" class="btn-primary" style="gap:8px;">
                <i class="fa fa-plus"></i>
                New Permission
            </a>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <span class="stat-label">Total Permissions</span>
                <span class="stat-value">{{ $permissions->count() }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Active</span>
                <span class="stat-value stat-value--blue">{{ $permissions->where('permission_status', 'active')->count() }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Inactive</span>
                <span class="stat-value stat-value--red">{{ $permissions->where('permission_status', 'inactive')->count() }}</span>
            </div>
        </div>

        <div class="data-table-container">

            <div class="table-header-row">
                <span class="table-title">List Permissions</span>
                <input
                    id="permissionsSearch"
                    type="text"
                    placeholder="Search permissions…"
                    oninput="filterPermissions(this.value)"
                    class="ios-input"
                    style="max-width:200px;">
            </div>

            <table class="projects-table" id="permissionsTable">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Permission Name</th>
                        <th style="text-align: center;">Module Name</th>
                        <th style="text-align: center;">Status</th>
                        <th style="width:80px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="permissionsBody">
                    @forelse ($permissions as $index => $permission)
                        <tr class="perm-row"
                            data-module="{{ strtolower($permission->module_name) }}"
                            data-name="{{ strtolower($permission->permission_name) }}">
                            <td style="color:var(--text-tertiary); font-size:0.74rem;">{{ $index + 1 }}</td>
                            <td style="font-weight:500;">{{ $permission->permission_name }}</td>
                            <td style="text-align: center;">
                                <span class="module-badge">{{ str_replace('_', ' ', $permission->module_name) }}</span>                            </td>
                            <td style="text-align: center; ">
                                <span class="module-badge" 
                                    style="background-color: {{ $permission->permission_status === 'inactive' ? 'rgba(255,69,58,0.1)' : 'rgba(0,122,255,0.1)' }}; 
                                    color: {{ $permission->permission_status === 'inactive' ? 'var(--ios-red)' : 'var(--ios-blue)' }};">
                                    {{ ucfirst($permission->permission_status) }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-grid" style="margin: 0 auto; justify-content: center;">
                                    @if($permission->is_default == 0) <!-- kalau == 2 kali brrti kya adalah-->
                                    <a href="{{ route('permissions.edit', $permission->id) }}" 
                                    class="btn-secondary btn-sq" 
                                    title="Edit" 
                                    style="display: inline-flex; justify-content: center; align-items: center;">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    @else
                                    <a class="btn-secondary btn-sq" 
                                    title="Edit" 
                                    style="display: inline-flex; justify-content: center; align-items: center; cursor: not-allowed;" disabled>
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:52px 20px; color:var(--text-secondary);">
                                <div style="font-weight:600; margin-bottom:4px;">No permissions yet</div>
                                <div style="font-size:0.83rem;">Create your first permission to get started.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination" style="margin: 10px 20px; justify-content: flex-end;">
                {{ $permissions->links('pagination::bootstrap-4') }}
            </div>

            <div id="noResults" style="display:none; text-align:center; padding:48px 20px; color:var(--text-secondary);">
                <div style="font-weight:600;">No permissions found</div>
                <div style="font-size:0.83rem; margin-top:4px;">Try a different keyword.</div>
            </div>
        </div>

    </div>
</main>

<script>
    function filterPermissions(query) {
        const q = query.toLowerCase().trim();
        const rows = document.querySelectorAll('.perm-row');
        let visible = 0;

        rows.forEach(row => {
            const match = (row.dataset.module + row.dataset.name).includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        document.getElementById('noResults').style.display =
            (visible === 0 && q.length > 0) ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('nav-permissions');
        if (el) el.classList.add('active');
    });
</script>
@endsection