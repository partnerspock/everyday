@extends('layouts.app')

@section('title', 'Admin Users')

@section('content')
<main class="workspace">
    <div class="page-container">

        {{-- Page Header --}}
        <div class="form-index-header">
            <div class="page-header-left">
                <div class="page-header-title">
                    <i class="fa fa-users"></i>
                    <h1>Admin Users</h1>
                </div>
                <p class="page-subtitle">Manage access control users across all modules.</p>
            </div>
            <a href="{{ route('admins.create') }}" class="btn-primary" style="gap:8px;">
                <i class="fa fa-plus"></i>
                New Admin
            </a>
        </div>

        {{-- Stats Cards --}}
        @php
            $totalCount = $admins->count();
            $totalActive = $admins->where('deactivated_at', null)->count();
            $totalInactive = $admins->where('deactivated_at', '!=', null)->count();
        @endphp
        <div class="stat-grid">
            <div class="stat-card">
                <span class="stat-label">Total Admin Users</span>
                <span class="stat-value">{{ $totalCount }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Active</span>
                <span class="stat-value stat-value--blue">{{ $totalActive }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Inactive</span>
                <span class="stat-value stat-value--red">{{ $totalInactive }}</span>
            </div>
        </div>

        <div class="data-table-container">

            <div class="table-header-row">
                <span class="table-title">List Admins</span>
                <input
                    id="adminsSearch"
                    type="text"
                    placeholder="Search users…"
                    oninput="filterAdmins(this.value)"
                    class="ios-input"
                    style="max-width:200px;">
            </div>

            <table class="projects-table" id="adminsTable">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="text-align: center;">Status</th>
                        @if(auth()->user()->hasRole('partner'))
                        <th style="text-align: center; width:80px">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="adminsBody">
                    @forelse ($admins as $index => $admins)
                        <tr class="admin-row"
                            data-name="{{ strtolower($admins->name) }}"
                            data-module="{{ strtolower($admins->module) }}">
                            <td style="color:var(--text-tertiary); font-size:0.74rem;">{{ $index + 1 }}</td>
                            <td style="font-weight:500;">{{ $admins->name }}</td>
                            <td style="font-weight:500;">{{ $admins->username }}</td>
                            <td style="font-weight:500;">{{ $admins->email }}</td>
                            <td style="font-weight:500;">{{ $admins->roles->first()->role_name ?? 'No Role Selected'}}</td>
                            <td style="text-align: center;">
                                <span class="module-badge" 
                                    style="background-color: {{ $admins->deactivated_at ? 'rgba(255,69,58,0.1)' : 'rgba(0,122,255,0.1)' }}; 
                                    color: {{ $admins->deactivated_at ? 'var(--ios-red)' : 'var(--ios-blue)' }};">
                                    {{ ucfirst($admins->deactivated_at ? 'Inactive' : 'Active') }}
                                </span>
                            </td>
                            @if(auth()->user()->hasRole('partner'))
                            <td>
                                <div class="action-grid">
                                    <a href="{{ route('admins.edit', $admins->id) }}">
                                    <div class="btn-secondary btn-sq" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </div>
                                    </a>
                                    <form action="{{ route('admins.destroy', $admins->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin? This cannot be undone.');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <button type="submit" class="btn-negative btn-sq" title="Delete" style="border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:52px 20px; color:var(--text-secondary);">
                                <div style="font-weight:600; margin-bottom:4px;">No users yet</div>
                                <div style="font-size:0.83rem;">Create your first users to get started.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noResults" style="display:none; text-align:center; padding:48px 20px; color:var(--text-secondary);">
                <i class="fa fa-magnifying-glass" style="font-size:1.8rem; margin-bottom:10px; display:block; color:var(--text-tertiary);"></i>
                <div style="font-weight:600;">No results found</div>
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
        const el = document.getElementById('nav-admins');
        if (el) el.classList.add('active');
    });
</script>

@endsection