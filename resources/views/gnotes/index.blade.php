@extends('layouts.app')

@section('title', 'General Notes')

@section('content')
<main class="workspace">
    <div class="page-container">
        <div class="form-index-header">
                <div class="page-header-left">
                    <div class="page-header-title">
                        <i class="fa fa-note-sticky"></i>
                        <h1>General Notes</h1>
                    </div>
                    <p class="page-subtitle">Hi! {{ Auth::user()->name }}, How are you today?</p>
                </div>
                <div class="note-toolbar-right">
                @php
                $hasUnlockedNotes = collect(session()->all())
                    ->keys()
                    ->contains(fn($key) => str_starts_with($key, 'note_unlocked_'));
                @endphp
                @if($hasUnlockedNotes)
                <form action="{{ route('gnotes.lockAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn-warning" onclick="return confirm('Are you sure want to lock all notes?')">
                    &nbsp;<i class="fa fa-lock"></i>&nbsp;
                </button>
                </form>
                @endif
                <a href="{{ route('gnotes.create') }}" class="btn-primary">
                    <i class="fa fa-plus"></i>&nbsp;Create Note
                </a>
            </div>
        </div>
        <div class="data-table-container">
            <div class="table-header-row">
                <span class="table-title">List General Notes</span>
                <input
                    id="notesSearch"
                    type="text"
                    placeholder="Search notes…"
                    oninput="filterNotes(this.value)"
                    class="ios-input"
                    style="max-width:200px;">
            </div>
            <div id="notesGrid" class="notes-grid">
                @forelse ($notes as $note)
                    <div class="note-card {{ $note->password && !session()->has("note_unlocked_{$note->id}") ? 'note-card--locked' : '' }}" data-title="{{ strtolower($note->title_notes) }}">
                        <div class="note-card-header">
                            <h3 class="note-card-title">{{ $note->title_notes }}</h3>
                            <div class="action-grid note-card-actions">
                                @if($note->password)
                                    <span class="note-lock-badge {{ session()->has("note_unlocked_{$note->id}") ? 'unlocked' : 'locked' }}" 
                                          title="{{ session()->has("note_unlocked_{$note->id}") ? 'Unlocked for this session' : 'Password protected' }}">
                                        <i class="fa {{ session()->has("note_unlocked_{$note->id}") ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($note->password && !session()->has("note_unlocked_{$note->id}"))
                            <div class="note-card-description note-card-description--locked">
                                <i class="fa fa-lock"></i>Locked Note
                            </div>
                        @else
                            @if($note->description)
                                <p class="note-card-description">{{ $note->description }}</p>
                            @endif
                        @endif
                        <div class="note-card-footer">
                            <span class="module-badge note-card-date">{{ $note->created_at->format('d M Y, H:i') }}</span>
                            <div class="action-grid note-card-actions">
                                <a href="{{ route('gnotes.edit', $note->id) }}">
                                    <div class="btn-secondary btn-sq" title="{{ $note->password && !session()->has("note_unlocked_{$note->id}") ? 'Unlock & Edit' : 'Edit' }}">
                                        <i class="fa {{ $note->password && !session()->has("note_unlocked_{$note->id}") ? 'fa-lock' : 'fa-eye' }}"></i>
                                    </div>
                                </a>
                                <form action="{{ route('gnotes.destroy', $note->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this note?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-negative btn-sq" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="notes-empty-state">
                        <div class="notes-empty-state-title">Nothing here</div>
                        <div class="notes-empty-state-sub">Let's create your first note.</div>
                    </div>
                @endforelse
            </div>

            {{-- No search results --}}
            <div id="noResults" class="notes-no-results">
                <i class="fa fa-magnifying-glass notes-no-results-icon"></i>
                <div class="notes-no-results-title">No results found</div>
                <div class="notes-no-results-sub">Try a different keyword.</div>
            </div>
        </div>

    </div>
</main>

<script>
    function filterNotes(query) {
        const q = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.note-card');
        let visible = 0;

        cards.forEach(card => {
            const match = card.dataset.title.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        document.getElementById('noResults').style.display =
            (visible === 0 && q.length > 0) ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('nav-general_notes');
        if (el) el.classList.add('active');
    });
</script>
@endsection