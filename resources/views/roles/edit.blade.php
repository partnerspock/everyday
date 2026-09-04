@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<main class="workspace">
    <div class="page-container">

        {{-- Header --}}
        <div class="form-back-header">
            <a href="{{ route('roles.index') }}" class="btn-secondary">
                <i class="fa fa-chevron-left"></i> Back
            </a>
            <h1>Edit Role</h1>
        </div>

        {{-- Error Alerts --}}
        @if ($errors->any())
            <div style="background:rgba(255,69,58,0.1); border:1px solid rgba(255,69,58,0.25); border-radius:10px; padding:12px 16px; margin-bottom:18px; color:var(--ios-red); font-size:0.85rem; max-width: 1000px;">
                <ul style="padding-left:16px; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Main Two-Column Layout Grid --}}
        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @csrf
            @method('PUT')

            <div class="form-card-grid">
                {{-- Left Column: Role Details Card --}}
                <div class="form-card" style="margin: 0;">
                    <div class="form-field" style="margin-bottom: 0;">
                        <span class="form-section-title">Role Information</span>
                        <p class="form-section-desc">Modify role settings</p>
                        <br>
                        <label class="form-label">Role Name</label>
                        <input class="ios-input" type="text" name="role_name"
                               placeholder="e.g. Administrator, Editor"
                               value="{{ old('role_name', $role->role_name) }}"
                               style="max-width:100%;" required>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('roles.index') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fa fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>

                {{-- Right Column: Permissions Management Grid --}}
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="form-card" style="margin: 0;">
                        <span class="form-section-title">Assigned Permissions</span>
                        <p class="form-section-desc">Update assigned modules</p>
                        <br>
                    @if(isset($groupedPermissions) && $groupedPermissions->count() > 0)
                        @foreach ($groupedPermissions as $moduleName => $permissions)
                            <div class="data-table-container" style="padding: 16px; margin-bottom: 10px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 12px;">
                                    <span style="font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--accent);">
                                        {{ str_replace('_', ' ', $moduleName) }}
                                    </span>
                                    <span style="font-size: 0.88rem; color: var(--text-tertiary);">
                                        {{ count($permissions) }} Active
                                    </span>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                    @foreach ($permissions as $permission)
                                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.88rem; user-select: none;">
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}"
                                                   {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) || in_array($permission->id, $assignedPermissionIds ?? []) ? 'checked' : '' }}
                                                   style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--accent);">
                                            <span style="color: var(--text-primary);">{{ $permission->permission_name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="data-table-container" style="padding: 24px; text-align: center; color: var(--text-secondary);">
                            <i class="fa fa-exclamation-triangle" style="font-size: 1.5rem; margin-bottom: 8px; color: #ff9f0a;"></i>
                            <p style="margin: 0; font-size: 0.83rem;">No active permissions available to assign.</p>
                        </div>
                    @endif
                </div>

            </div>
        </form>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('nav-roles');
        if (el) el.classList.add('active');
    });
</script>
@endsection