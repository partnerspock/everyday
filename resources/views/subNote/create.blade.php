@extends('layouts.app')

@section('title', $space->title_spaces . ' | New Note')

@section('content')
<main class="workspace">
    <div class="page-container">
        
        {{-- BUNGKUS SELURUH HEADER & INPUT DALAM BENTUK FORM --}}
        <form id="noteForm" method="POST" action="{{ route('subNote.store', $space->id) }}">
            @csrf

            <div class="form-index-header">
                <div class="header-left">
                    <a href="{{ route('subNote.index', $space->id) }}" class="btn-secondary" id="btnBack">
                        <i class="fa fa-chevron-left"></i> Back
                    </a>
                </div>
                {{-- Tombol submit sekarang di dalam form langsung --}}
                <button type="submit" class="btn-primary">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>

            {{-- TAMPILKAN ERROR VALIDASI JIKA ADA --}}
            @if ($errors->any())
                <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-card">
                <input
                    type="text"
                    id="noteTitle"
                    name="title_notes"
                    class="note-title-input"
                    placeholder="Title"
                    value="{{ old('title_notes') }}"
                    autocomplete="off"
                    required>

                <div class="note-meta-line">
                    {{ now()->format('d M Y H:i') }} &nbsp;·&nbsp; Date Created
                </div>

                <textarea
                    id="noteBody"
                    name="description"
                    class="note-body-input"
                    placeholder="Start writing…"
                    required>{{ old('description') }}</textarea>
            </div>
        </form>

    </div>   
</main>
@endsection