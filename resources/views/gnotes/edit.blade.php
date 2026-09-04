@extends('layouts.app')

@section('title', 'Edit Note')

@section('content')
<main class="workspace">
    <div class="page-container">
        <div class="form-back-header" style="justify-content: space-between; align-items: center;">
            <div class="header-left" style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('gnotes.index') }}" class="btn-secondary" id="btnBack" onclick="handleBack(event)">
                    <i class="fa fa-chevron-left"></i> Back
                </a>
            </div>

            <div class="note-toolbar-right">
                <div class="note-lock-group" id="lockGroup">
                    @if($note->password && session()->has("note_unlocked_{$note->id}"))
                        <form action="{{ route('gnotes.lock', $note->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to lock this note?');">
                            @csrf
                            <button type="submit" class="note-toolbar-btn">
                                <i class="fa fa-lock-open"></i>
                            </button>
                        </form>
                        <button type="button" class="note-toolbar-btn note-lock-caret" id="lockCaretBtn"
                            onclick="toggleLockMenu(event)" title="Lock options">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                        <div class="note-lock-menu" id="lockMenu">
                            <button type="button" class="note-lock-menu-item note-lock-menu-warning"
                                    onclick="openRemovePasswordModal()">
                                <i class="fa fa-triangle-exclamation"></i> Remove Password
                            </button>
                        </div>
                    @else
                        <button type="button" class="note-lock-menu-item"
                                    onclick="openSetPasswordModal()">
                                <i class="fa fa-lock"></i>
                            </button>
                    @endif
                </div>
                <button type="submit" form="noteForm" class="btn-primary">
                    <i class="fa fa-save"></i>Save
                </button>
            </div>
        </div>
        @if(session('success'))
            <div class="note-flash note-flash-success">
                <i class="fa fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="note-flash note-flash-error">
                <i class="fa fa-circle-exclamation"></i>
                @foreach($errors->all() as $e) {{ $e }} @endforeach
            </div>
        @endif

        <div class="form-card">
            <form id="noteForm" method="POST" action="{{ route('gnotes.update', $note->id) }}">
                @csrf
                @method('PUT')
                <input
                    type="text"
                    id="noteTitle"
                    name="title_notes"
                    class="note-title-input"
                    placeholder="Title"
                    value="{{ old('title_notes', $note->title_notes) }}"
                    autocomplete="off">
                <div class="note-meta-line">
                    @if($note->updated_at == null)
                        {{ $note->created_at->format('d M Y H:i') }} &nbsp;·&nbsp; Date Created
                    @else
                        {{ $note->updated_at->format('d M Y H:i') }} &nbsp;·&nbsp; Last Updated
                    @endif
                </div>
                <textarea
                    id="noteBody"
                    name="description"
                    class="note-body-input"
                    placeholder="Start writing…">{{ old('description', $note->description) }}</textarea>
            </form>
        </div>

    </div>
</main>

<div class="note-modal-overlay" id="setPasswordModal">
    <div class="note-modal">
        <div class="note-modal-header">
            <h2 class="note-modal-title"><i class="fa fa-lock"></i>&nbsp;Lock This Note</h2>
            <p class="note-modal-sub">Set a password.</p>
        </div>
        <form method="POST" action="{{ route('gnotes.setPassword', $note->id) }}">
            @csrf
            <div class="note-modal-field">
                <input type="password" name="password" class="ios-input" placeholder="Enter new password" autocomplete="new-password">
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('setPasswordModal')">Cancel</button>
                <button type="submit" class="btn-primary"><i class="fa fa-lock"></i> Lock</button>
            </div>
        </form>
    </div>
</div>

<div class="note-modal-overlay" id="removePasswordModal">
    <div class="note-modal">
        <div class="note-modal-header">
            <h2 class="note-modal-title"><i class="fa fa-triangle-exclamation"></i>&nbsp;Remove Password</h2>
            <p class="note-modal-sub">Enter your current password first.</p>
        </div>
        <form method="POST" action="{{ route('gnotes.removePassword', $note->id) }}">
            @csrf
            <div class="note-modal-field">
                <input type="password" name="current_password" class="ios-input" placeholder="Enter current password" autocomplete="current-password">
                @error('current_password')
                    <span class="note-field-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('removePasswordModal')">Cancel</button>
                <button type="submit" class="btn-warning"><i class="fa fa-triangle-exclamation"></i> Remove</button>
            </div>
        </form>
    </div>
</div>

<script>
let initialTitle = "";
let initialDesc = "";

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('nav-general_notes');
    if (el) el.classList.add('active');

    @if($errors->has('current_password'))
        openRemovePasswordModal();
    @endif

    const textarea = document.getElementById('noteBody');
    if (textarea) {
        const autoGrow = () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        };
        textarea.addEventListener('input', autoGrow);
        autoGrow();
    }

    const titleInput = document.getElementById('noteTitle');
    const descInput = document.getElementById('noteBody');

    initialTitle = titleInput ? titleInput.value.trim() : "";
    initialDesc = descInput ? descInput.value.trim() : "";
});

function handleLockClick() {
    const hasPassword = {{ $note->password ? 'true' : 'false' }};
    if (!hasPassword) openSetPasswordModal();
}

function toggleLockMenu(event) {
    event.stopPropagation();
    document.getElementById('lockMenu').classList.toggle('open');
}

document.addEventListener('click', () => {
    const lockMenu = document.getElementById('lockMenu');
    if (lockMenu) lockMenu.classList.remove('open');
});

function openSetPasswordModal() { document.getElementById('setPasswordModal').classList.add('open'); }
function openRemovePasswordModal() { document.getElementById('removePasswordModal').classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function handleBack(event) {
    const currentTitle = document.getElementById('noteTitle') ? document.getElementById('noteTitle').value.trim() : "";
    const currentDesc = document.getElementById('noteBody') ? document.getElementById('noteBody').value.trim() : "";

    const isChanged = (currentTitle !== initialTitle) || (currentDesc !== initialDesc);

    if (isChanged) {
        event.preventDefault();
        
        const choice = confirm('You have unsaved changes. Are you sure you want to go back?');
        if (choice) {
            window.location.href = event.currentTarget.href;
        }
    }
}
</script>
@endsection