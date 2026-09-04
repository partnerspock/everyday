@extends('layouts.app')

@section('title', $space->title_spaces . ' | Notes')

@section('content')
<main class="workspace">
    <div class="page-container">
        <div class="form-index-header">
            <div class="header-left">
                <a href="{{ route('spaces.index', $space->id) }}" class="btn-secondary" id="btnBack" onclick="handleBack(event)">
                    <i class="fa fa-chevron-left"></i> Back
                </a>
            </div>
            <a href="{{ route('subNote.create', $space->id) }}" class="btn-primary">
                    <i class="fa fa-plus"></i>&nbsp;Create Note
                </a>
        </div>
        <div class="data-table-container">
            <div class="table-header-row">
                <span class="table-title">List Notes | {{ $space->title_spaces }}</span>
                <input
                    id="notesSearch"
                    type="text"
                    placeholder="Search notes…"
                    oninput="filterNotes(this.value)"
                    class="ios-input"
                    style="max-width:200px;">
            </div>
            <div id="notesGrid" class="notes-grid">
                @forelse ($subNote as $sNote)
    <div class="note-card" data-title="{{ strtolower($sNote->title_notes) }}">
        <div class="note-card-header">
            <h3 class="note-card-title">{{ $sNote->title_notes }}</h3>
        </div>
        <div class="note-card-description">
            <p>{{ $sNote->description }}</p>
        </div>
        <div class="note-card-footer">
            <span class="module-badge note-card-date">{{ $sNote->created_at->format('d M Y, H:i') }}</span>
            <div class="action-grid note-card-actions">
                <a href="{{ route('subNote.edit', $sNote->id) }}">
                    <div class="btn-secondary btn-sq" title="Edit">
                        <i class="fa fa-eye"></i>
                    </div>
                </a>
                <form action="{{ route('subNote.destroy', $sNote->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure want to delete this note? This action cannot be undone.');" style="display:inline;">
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
</script>
@endsection
