@extends('layouts.app')

@section('title', $space->title_spaces . ' | ' . $subNote->title_notes)

@section('content')
<main class="workspace">
    <div class="page-container">
        <div class="form-index-header">
            <div class="header-left">
                <a href="{{ route('subNote.index', $subNote->id_spaces) }}" class="btn-secondary" id="btnBack" onclick="handleBack(event)">
                    <i class="fa fa-chevron-left"></i> Back
                </a>
            </div>
            <button type="submit" form="noteForm" class="btn-primary">
                <i class="fa fa-save"></i>&nbsp;Save
            </button>
        </div>

            <div class="form-card">
                <form id="noteForm" method="POST" action="{{ route('subNote.update', $subNote->id) }}">
                @csrf
                @method('PUT')
                <input
                    type="text"
                    id="noteTitle"
                    name="title_notes"
                    class="note-title-input"
                    placeholder="Title"
                    value="{{ old('title_notes', $subNote->title_notes) }}"
                    autocomplete="off">
                <div class="note-meta-line">
                    @if($subNote->updated_at == null)
                        {{ $subNote->created_at->format('d M Y H:i') }} &nbsp;·&nbsp; Date Created
                    @else
                        {{ $subNote->updated_at->format('d M Y H:i') }} &nbsp;·&nbsp; Last Updated
                    @endif
                </div>
                <textarea
                    id="noteBody"
                    name="description"
                    class="note-body-input"
                    placeholder="Start writing…">{{ old('description', $subNote->description) }}</textarea>
                </form>
            </div>

 </div>   
</main>

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
