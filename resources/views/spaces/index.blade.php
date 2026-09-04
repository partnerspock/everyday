@extends('layouts.app')

@section('title', 'Spaces ' . $space->title_spaces)

@section('content')
<main class="workspace">
    <div class="page-container">
        {{-- Header Section --}}
        <div class="form-index-header">
            <div class="page-header-left">
                <div class="page-header-title">
                    <i class="fa fa-home"></i>
                    <h1>{{ $space->title_spaces }}</h1>
                </div>
                <p class="page-subtitle">Manage your teamspaces here.</p>
            </div>
        </div>
    
        <div class="stat-grid">
            <div class="stat-card">
                    <span class="stat-label">Total Notes</span>
                <span class="stat-value">{{ $space->sub_note_count }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Reminders</span>
                <span class="stat-value stat-value--blue">{{ $space->sub_reminder_count }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Projects</span>
                <span class="stat-value stat-value--red">{{ $space->sub_project_count }}</span>
            </div>
        </div>

        <section class="dashboard-section" style="margin: 20px 0px;">
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h2 style="font-size: 1.25rem; font-weight: 600; margin: 0;"><i class="fa fa-note-sticky"></i> Spaces Notes</h2>
                <a href="{{ route('subNote.index', $space->id) }}" class="btn-secondary" style="font-size: 0.875rem;">
                    See All <i class="fa fa-chevron-right" style="margin-left: 4px;"></i>
                </a>
            </div>
            
            <hr style="border: 0; border-top: 1px solid var(--border-color, #e2e8f0); margin-bottom: 16px;">

            <div style="display: flex; gap: 16px; overflow-x: auto; padding-bottom: 12px; scroll-snap-type: x mandatory;">
                
                @forelse($space->subNote()->latest()->take(10)->get() as $note)
                <div class="note-card" data-title="{{ strtolower($note->title_notes) }}" style="flex: 0 0 260px; scroll-snap-align: start;">
                    <div class="note-card-header">
                        <h3 class="note-card-title">
                            @if(!empty($note->password))
                                <i class="fa fa-lock" style="color: var(--ios-orange); margin-right: 4px; font-size: 0.85rem;" title="Locked"></i>
                            @endif
                            {{ $note->title_notes }}
                        </h3>
                    </div>
                    <div class="note-card-description">
                        <p>{{ $note->description ?? 'No content' }}</p>
                    </div>
                    <div class="note-card-footer">
                        <span class="module-badge note-card-date">{{ $note->created_at?->format('d M Y, H:i') }}</span>
                        <a href="{{ route('subNote.edit', $note->id) }}" class="btn-secondary btn-sq" title="View Note">
                            <i class="fa fa-eye"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div style="color: var(--text-secondary); font-size: 0.875rem; align-self: center; padding: 32px;">No notes created yet.</div>
                @endforelse
                <div style="flex: 0 0 180px; min-height: 140px; border: 2px dashed var(--separator); border-radius: 10px; scroll-snap-align: start; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                    <a href="{{ route('subNote.create', $space->id) }}" class="btn-primary" style="display: flex; flex-direction: column; align-items: center; gap: 8px; text-decoration: none; width: 100%; height: 100%;">
                        <i class="fa fa-plus" style="font-size: 1.25rem;"></i>
                        <span>Add Note</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="dashboard-section" style="margin: 20px 0px;">
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h2 style="font-size: 1.25rem; font-weight: 600; margin: 0;"><i class="fa fa-list-check"></i> Spaces Reminders</h2>
                <a href="{{ route('subReminder.index', $space->id) }}" class="btn-secondary" style="font-size: 0.875rem;">
                    See All<i class="fa fa-chevron-right" style="margin-left: 4px;"></i>
                </a>
            </div>
            <hr style="border: 0; border-top: 1px solid var(--border-color, #e2e8f0); margin-bottom: 16px;">
            <div class="data-table-container">
                <table class="projects-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th>Title</th>
                        <th style="width: 500px;">Description</th>
                        <th style="text-align: center;">Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($space->subReminder()->latest()->take(5)->get() as $reminder)
                    <tr class="reminder-row {{ strtolower($reminder->row_status) === 'ticked' || strtolower($reminder->row_status) === 'completed' ? 'completed-row' : '' }}" 
                            data-title="{{ strtolower($reminder->row_title) }}"
                            id="row-{{ $reminder->id }}">
                            <td style="text-align: center;">
                                 @if($reminder->row_status === 'ticked')
                                    <i class="fa fa-circle-check" style="color: var(--ios-green)"></i>
                                @else
                                    <i class="fa fa-triangle-exclamation" style="color: var(--ios-orange)"></i>
                                @endif
                            </td>
                            <td>{{ $reminder->row_title }}</td>
                            <td>{{ $reminder->row_description }}</td>
                            <td style="text-align: center; white-space: nowrap;">
                                <span class="module-badge note-card-date">
                                    {{ $reminder->created_at->format('d M Y, H:i') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px; color: var(--text-secondary); font-size: 0.875rem;">No reminders available.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="dashboard-section" style="margin: 20px 0px;">
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h2 style="font-size: 1.25rem; font-weight: 600; margin: 0;"><i class="fa fa-calendar-check"></i> Spaces Projects</h2>
                <a href="{{ route('subProject.index', $space->id) }}" class="btn-secondary" style="font-size: 0.875rem;">
                    See All <i class="fa fa-chevron-right" style="margin-left: 4px;"></i>
                </a>
            </div>
            <hr style="border: 0; border-top: 1px solid var(--border-color, #e2e8f0); margin-bottom: 20px;">
            
            <div class="data-table-container">
                <table class="projects-table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Project Title</th>
                            <th>Description</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center; width: 60px;">Files</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($space->subProject()->with('files')->latest()->take(5)->get() as $index => $project)
                        <tr>
                            <td style="color:var(--text-tertiary); font-size:0.75rem;">{{ $index + 1 }}</td>
                            <td style="font-weight: 500;">{{ $project->pro_title }}</td>
                            <td style="color: var(--text-secondary); max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $project->pro_description ?: '-' }}</td>
                            <td style="text-align: center;">
                                <span class="module-badge" style="background-color: rgba(0, 122, 255, 0.1); color: var(--ios-blue); text-transform: capitalize;">
                                    {{ str_replace('_', ' ', is_object($project->pro_status) ? $project->pro_status->value : $project->pro_status) }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @if($project->files && $project->files->count() > 0)
                                    <a href="#" title="{{ $project->files->count() }} file(s)" class="btn-secondary btn-sq" style="cursor: default;">
                                        <i class="fa fa-cloud-arrow-down"></i>&nbsp;
                                        <sup style="font-size: 0.65rem;">{{ $project->files->count() }}</sup>
                                    </a>
                                @else
                                <a class="btn-secondary btn-sq" style="cursor: not-allowed; color: var(--text-tertiary);">
                                    <i class="fa fa-cloud-arrow-down" title="No files"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 32px; color: var(--text-secondary);">
                                No projects created yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>



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
        const el = document.getElementById('title_spaces');
        if (el) el.classList.add('active');
    });
</script>
@endsection