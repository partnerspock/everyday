@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<main class="workspace">
    <div class="page-container">
        <div class="form-index-header">
            <div class="page-header-left">
                <div class="page-header-title">
                    <i class="fa fa-key"></i>
                    <h1>Roles</h1>
                </div>
                <p class="page-subtitle">Manage access control roles across all modules.</p>
            </div>
            <a href="{{ route('roles.create') }}" class="btn-primary" style="gap:8px;">
                <i class="fa fa-plus"></i>
                New Role
            </a>
        </div>

        @php
            $rolesCount = count($roles);
            
            if ($roles && $rolesCount > 0) {
                $firstRole  = $roles->first();
                $lastRole   = $roles->last();
                
                $newestData = $firstRole ? $firstRole->role_name : 'No roles yet';
                  $oldestData = $lastRole ? $lastRole->role_name : 'No roles yet';
            } else {
                $newestData = 'No roles yet';
                $oldestData = 'No roles yet';
            }
        @endphp

        <div class="stat-grid">
            <div class="stat-card">
                <span class="stat-label">Total Roles</span>
                <span class="stat-value stat-value--accent">{{ $rolesCount }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Newest Role</span>
                <span class="stat-value" style="font-size:{{ $rolesCount > 0 ? '1.8rem' : '2rem' }}; color: {{ $rolesCount > 0 ? 'inherit' : 'var(--text-secondary)' }};">
                    {{ $oldestData }}
                </span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Oldest Role</span>
                <span class="stat-value" style="font-size:{{ $rolesCount > 0 ? '1.8rem' : '2rem' }}; color: {{ $rolesCount > 0 ? 'inherit' : 'var(--text-secondary)' }};">
                    {{ $newestData }}
                </span>
            </div>
        </div>
        
        <div class="data-table-container">

            <div class="table-header-row">
                <span class="table-title">List Roles</span>
                <input
                    id="rolesSearch"
                    type="text"
                    placeholder="Search roles…"
                    oninput="filterRoles(this.value)"
                    class="ios-input"
                    style="max-width:200px;">
            </div>
            <table class="projects-table" id="rolesTable">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Role Name & <br> Created At</th>
                        <th>List Permissions</th>
                        <th style="width:80px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="rolesBody">
                    @forelse ($roles as $index => $role)
                        <tr class="role-row"
                            data-name="{{ strtolower($role->role_name) }}">
                            <td style="color:var(--text-tertiary); font-size:0.74rem;">{{ $index + 1 }}</td>
                            <td style="font-weight:500;">{{ $role->role_name }}
                                <br>
                            <span style="margin-top:10px; white-space: nowrap;" class="module-badge">{{ $role->created_at->format('d M Y, H:i') }}</span></td>
                            <td>
                                <div class="data-table-container" style="padding: 16px; margin-bottom: 10px;">
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    
                                    @foreach($role->permissions->groupBy('module_name') as $moduleName => $permissions)
                                        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 6px;">
                                            
                                            <span style="font-size: 0.82rem; font-weight: 700; color: var(--text-secondary); text-transform: capitalize;">
                                                {{ str_replace('_', ' ', $moduleName) }}:
                                            </span>
                                            
                                            @foreach($permissions as $permission)
                                                <span class="module-badge" style="width: fit-content; display: inline-flex;">
                                                    {{ $permission->permission_name }}
                                                </span>
                                            @endforeach
                                            
                                        </div>
                                    @endforeach
                                    @if($role->permissions->isEmpty())
                                        <div style="display: inline-flex;">
                                            <span class="module-badge" style="background: rgba(255, 69, 58, 0.1); color: var(--ios-red); border-color: transparent; width: fit-content;">
                                                No permissions assigned
                                            </span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                            </td>
                            <td>
                                <div class="action-grid">
                                    @if($role->is_default == 0)
                                    <a href="{{ route('roles.edit', $role->id) }}">
                                    <div class="btn-secondary btn-sq" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </div>
                                    </a>
                                    @else
                                    <div class="btn-secondary btn-sq" title="Edit" style="cursor: not-allowed;">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    @endif
                                    @if($role->is_default == 0)
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role? This cannot be undone.');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                        type="submit"
                                        class="btn-negative btn-sq"
                                        title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                        <button
                                        type="submit"
                                        class="btn-secondary btn-sq"
                                        style="cursor: not-allowed;"
                                        title="Delete" disabled>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:52px 20px; color:var(--text-secondary);">
                                <div style="font-weight:600; margin-bottom:4px;">No roles yet</div>
                                <div style="font-size:0.83rem;">Create your first role to get started.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noResults" style="display:none; text-align:center; padding:48px 20px; color:var(--text-secondary);">
                <div style="font-weight:600;">No roles found</div>
                <div style="font-size:0.83rem; margin-top:4px;">Try a different keyword.</div>
            </div>
        </div>

    </div>
</main>

<script>
    function filterRoles(query) {
        const q = query.toLowerCase().trim();
        const rows = document.querySelectorAll('.role-row');
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
        const el = document.getElementById('nav-roles');
        if (el) el.classList.add('active');
    });
</script>
@endsection