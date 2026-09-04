@extends('layouts.app')

@section('title', 'Locked Note')

@section('content')
<main class="workspace">
    <div class="note-unlock-container">
        <div class="note-unlock-card">
            <div class="note-modal-header">
                <h2 class="note-modal-title"><i class="fa fa-lock"></i>&nbsp;{{ $note->title_notes }}</h2>
                <p class="note-modal-sub">This note is locked.</p>
            </div>
            <form method="POST" action="{{ route('gnotes.unlock', $note->id) }}">
                @csrf
                <div class="note-modal-field">
                    <input type="password" name="password" class="ios-input" placeholder="Enter the password" required autofocus>
                    @if($errors->any())
                        <div class="note-field-error">
                            <i class="fa fa-circle-exclamation"></i> {{ $errors->first() }}
                        </div>
                    @endif
                </div>
                <div class="form-actions">
                    <a href="{{ route('gnotes.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary"><i class="fa fa-unlock"></i>
                        Unlock
                    </button>
                </div>
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
