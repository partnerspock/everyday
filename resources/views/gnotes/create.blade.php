@extends('layouts.app')

@section('title', 'New Note')

@section('content')
<main class="workspace">
    <div class="page-container">
        <div class="form-back-header" style="justify-content: space-between; align-items: center;">
            <div class="header-left" style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('gnotes.index') }}" class="btn-secondary">
                    <i class="fa fa-chevron-left"></i> Back
                </a>
            </div>
            <div class="note-tooblar-right">
                <button type="submit" form="noteForm" class="btn-primary">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>
        <div class="note-editor-layout">
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
            <form id="noteForm" method="POST" action="{{ route('gnotes.store') }}">
                @csrf
                <input
                    type="text"
                    name="title_notes"
                    class="note-title-input"
                    placeholder="Title"
                    value="{{ old('title_notes') }}"
                    autocomplete="off">
                <div class="note-meta-line">
                        {{ date('d M Y H:i') }}
                </div>
                <textarea
                    id="noteBody"
                    name="description"
                    class="note-body-input"
                    placeholder="Start writing…">{{ old('description') }}</textarea>
            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('nav-general_notes');
        if (el) el.classList.add('active');
    });
</script>
@endsection
