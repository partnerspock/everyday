@extends('layouts.app')

@section('title', 'Add New Admin Users')

@section('content')
<main class="workspace">
    <div class="page-container">

        {{-- Header --}}
        <div class="form-back-header">
            <a href="{{ route('admins.index') }}" class="btn-secondary">
                <i class="fa fa-chevron-left"></i> Back
            </a>
            <h1>Add New Admin Users</h1>
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

            <form method="POST" action="{{ route('admins.store') }}">
                @csrf
                <div class="form-field">
                    <span class="form-section-title">Admin Users Information</span>
                    <p class="form-section-desc">Add new admin users.</p>
                    <br>
                    <label class="form-label">Name</label>
                    <input class="ios-input" type="text" name="name"
                           placeholder="e.g. John Doe"
                           value="{{ old('name') }}"
                           style="max-width:100%;">
                    <label class="form-label">Username</label>
                    <input class="ios-input" type="text" name="username"
                           placeholder="e.g. johndoe"
                           value="{{ old('username') }}"
                           style="max-width:100%; color:var(--text-secondary);">
                    <label class="form-label">Email</label>
                    <input class="ios-input" type="text" name="email"
                           placeholder="e.g. johndoe@everyday.com"
                           value="{{ old('email') }}"
                           style="max-width:100%; color:var(--text-secondary);">
                    <label class="form-label">Password</label>
                    <input class="ios-input" type="password" name="password"
                           placeholder="e.g. password"
                           value="{{ old('password') }}"
                           style="max-width:100%; color:var(--text-secondary);">
                    <label class="form-label">Role Selected</label>
                    <div class="ios-select-wrapper">
                        <select name="role_id" class="ios-select">
                            <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Select a role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="form-actions">
                    <a href="{{ route('admins.index') }}" class="btn-secondary">
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
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('nav-admins');
        if (el) el.classList.add('active');
    });
</script>

@endsection