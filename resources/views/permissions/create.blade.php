@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
<main class="workspace">
    <div class="page-container">

        {{-- Header --}}
        <div class="form-back-header">
            <a href="{{ route('permissions.index') }}" class="btn-secondary">
                <i class="fa fa-chevron-left"></i> Back
            </a>
            <h1>New Permission</h1>
        </div>

        {{-- Form Card --}}
        <div class="form-card">
            @if ($errors->any())
                <div style="background:rgba(255,69,58,0.1); border:1px solid rgba(255,69,58,0.25); border-radius:10px; padding:12px 16px; margin-bottom:18px; color:var(--ios-red); font-size:0.85rem;">
                    <ul style="padding-left:16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('permissions.store') }}">
                @csrf
                <div class="form-field">
                    <span class="form-section-title">Permission Information</span>
                    <p class="form-section-desc">Create role access control.</p>
                    <br>
                    <label class="form-label">Permission Name</label>
                    <input class="ios-input" type="text" name="permission_name"
                           placeholder="e.g. Spaces View, Spaces Create, Spaces Delete"
                           value="{{ old('permission_name') }}"
                           style="max-width:100%;">
                    <label class="form-label">Module Key</label>
                    <input class="ios-input" type="text" name="module_key"
                           placeholder="e.g. view_spaces, create_spaces, delete_spaces"
                           value="{{ old('module_key') }}"
                           style="max-width:100%;">
                    <label class="form-label">Module Name</label>
                    <div class="ios-select-wrapper">
                        <select name="module_name" class="ios-select">
                            <option value="" disabled {{ old('module_name') ? '' : 'selected' }}>Select a module...</option>
                            @foreach(['general_notes', 'spaces', 'admin_users', 'roles', 'permissions', 'activity_logs'] as $module)
                                <option value="{{ $module }}" {{ old('module_name') === $module ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $module)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
               </div>

                <br>
                <div class="form-field">
                    <label class="form-label">Permission Status</label>
                    <div class="radio-group">
                        <div style="flex: 1;">
                            <input type="radio" 
                                id="status_active"
                                name="permission_status" 
                                value="active" 
                                class="radio-input" 
                                style="display: none;"
                                {{ old('permission_status') === 'active' ? 'checked' : '' }}>
                            <label for="status_active" class="radio-label status-active" style="justify-content: center;">
                                <i class="fa fa-check-circle"></i> Active
                            </label>
                        </div>
                        <div style="flex: 1;">
                            <input type="radio" 
                                id="status_inactive"
                                name="permission_status" 
                                value="inactive" 
                                class="radio-input" 
                                style="display: none;"
                                {{ old('permission_status') === 'inactive' ? 'checked' : '' }}>
                            <label for="status_inactive" class="radio-label status-inactive" style="justify-content: center;">
                                <i class="fa fa-times-circle"></i> Inactive
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('permissions.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fa fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
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