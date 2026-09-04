@extends('layouts.app')

@section('title', $space->title_spaces . ' | Reminders')

@section('content')
<main class="workspace">
    <form id="createReminderForm" method="POST" action="{{ route('subReminder.store', $space->id) }}" style="display: none;" onsubmit="handleCreateReminder(event)">
        @csrf
        <input type="hidden" name="row_status" value="not_ticked">
    </form>
    <div class="page-container">
        <div class="form-index-header">
            <div class="header-left">
                <a href="{{ route('spaces.index', $space->id) }}" class="btn-secondary" id="btnBack" onclick="handleBack(event)">
                    <i class="fa fa-chevron-left"></i> Back
                </a>
            </div>
        </div>
        <div class="data-table-container">
            <div class="table-header-row">
                <span class="table-title">{{ $space->title_spaces }} | Reminders</span>
                <input
                    id="reminderSearch"
                    type="text"
                    placeholder="Search reminders..."
                    oninput="filterReminders(this.value)"
                    class="ios-input"
                    style="max-width:200px;">
            </div>
            <table class="projects-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th>Title</th>
                        <th style="width: 500px;">Description</th>
                        <th style="text-align: center; white-space: nowrap;">Date Created</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subReminder as $sReminder)
                        <tr class="reminder-row {{ strtolower($sReminder->row_status) === 'ticked' ? 'completed-row' : '' }}" 
                            data-title="{{ strtolower($sReminder->row_title) }}"
                            id="row-{{ $sReminder->id }}">
                            <td style="text-align: center;">
                                <input type="checkbox" class="status-checkbox"
                                    data-id="{{ $sReminder->id }}"
                                    {{ strtolower($sReminder->row_status) === 'ticked' ? 'checked' : '' }}
                                    onchange="toggleStatus(this)"
                                    style="transform: scale(1.2); cursor: pointer;">
                            </td>
                            <td>{{ $sReminder->row_title }}</td>
                            <td>{{ $sReminder->row_description }}</td>
                            <td style="text-align: center; white-space: nowrap;">
                                <span class="module-badge note-card-date">
                                    {{ $sReminder->created_at->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-grid" style="margin: 0 auto; justify-content: center;">
                                    <a href="#" 
                                    class="btn-negative btn-sq" 
                                    title="Delete"
                                    onclick="handleDeleteReminder({{ $sReminder->id }}, this); return false;">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="notes-empty-state" style="padding: 40px; text-align: center;">
                                    <div class="notes-empty-state-title" style="font-weight: 600; font-size: 1.1rem; margin-bottom: 8px;">Nothing here</div>
                                    <div class="notes-empty-state-sub" style="color: var(--text-secondary); font-size: 0.9rem;">Let's create your first reminder.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                        <tr id="insertion-row" style="background: var(--fill-secondary);">
                            <td style="text-align: center;">
                                <span style="color: var(--text-tertiary);"><i class="fa fa-plus"></i></span>
                            </td>
                            <td style="padding: 4px 8px;">
                                <input type="text" name="row_title" form="createReminderForm" class="ios-input" placeholder="New Title..." style="width: 100%; min-width: 150px;" required>
                            </td>
                            <td style="padding: 4px 8px;">
                                <input type="text" 
                                    name="row_description" 
                                    form="createReminderForm" 
                                    class="ios-input" 
                                    placeholder="New Description..." 
                                    style="width: 100%; min-width: 200px;" 
                                    required>
                            </td>
                            <td style="text-align: center; white-space: nowrap;">
                                <span class="module-badge note-card-date" style="opacity: 0.5;">{{ now()->format('d M Y, H:i') }}</span>
                            </td>
                            <td style="text-align: center;">
                                <button type="submit" form="createReminderForm" class="btn-primary btn-sq" style="margin: 0 auto; display: flex;" title="Save">
                                    <i class="fa fa-save"></i>
                                </button>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
    .completed-row td:not(:first-child):not(:last-child) {
        color: var(--text-tertiary) !important;
    }
</style>

<script>
    function filterReminders(query) {
        const q = query.toLowerCase().trim();
        const rows = document.querySelectorAll('.reminder-row');

        rows.forEach(row => {
            const match = row.dataset.title.includes(q);
            row.style.display = match ? '' : 'none';
        });
    }

    function toggleStatus(checkbox) {
    var row = checkbox.closest('tr');
    var id = checkbox.getAttribute('data-id');
    var isChecked = checkbox.checked;
    var newStatus = isChecked ? 'ticked' : 'not_ticked';

    // 1. Langsung ubah tampilan UI (Optimistic UI)
    if (isChecked) {
        row.classList.add('completed-row');
    } else {
        row.classList.remove('completed-row');
    }

    // 2. Kirim request ke backend
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/subReminder/' + id + '/toggle-status', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onload = function() {
        if (xhr.status !== 200) {
            // Revert jika server melempar error
            checkbox.checked = !isChecked;
            row.classList.toggle('completed-row');
            alert('Gagal mengupdate status di server.');
        }
    };

    xhr.onerror = function() {
        // Revert jika koneksi putus
        checkbox.checked = !isChecked;
        row.classList.toggle('completed-row');
        alert('Koneksi terputus.');
    };

    xhr.send(JSON.stringify({ row_status: newStatus }));
}

    function handleCreateReminder(event) {
        event.preventDefault();
        const form = document.getElementById('createReminderForm');
        const url = form.action;
        
        const titleInput = document.querySelector('input[name="row_title"][form="createReminderForm"]');
        const descInput = document.querySelector('input[name="row_description"][form="createReminderForm"]');
        
        if (!titleInput.value.trim() || !descInput.value.trim()) {
            return;
        }

        const submitBtn = document.querySelector('button[form="createReminderForm"]');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        submitBtn.disabled = true;

        const formData = new FormData(form);
        formData.append('row_title', titleInput.value);
        formData.append('row_description', descInput.value);
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if(data.success) {
                titleInput.value = '';
                descInput.value = '';
                
                const insertionRow = document.getElementById('insertion-row');
                
                const newRowHTML = 
                    '<tr class="reminder-row" data-title="' + data.reminder.row_title.toLowerCase() + '" id="row-' + data.reminder.id + '">' +
                        '<td style="text-align: center;">' +
                            '<input type="checkbox" class="status-checkbox" data-id="' + data.reminder.id + '" onchange="toggleStatus(this)" style="transform: scale(1.2); cursor: pointer;">' +
                        '</td>' +
                        '<td>' + data.reminder.row_title + '</td>' +
                        '<td>' + data.reminder.row_description + '</td>' +
                        '<td style="text-align: center; white-space: nowrap;">' +
                            '<span class="module-badge note-card-date">' + data.formatted_date + '</span>' +
                        '</td>' +
                        '<td style="text-align: center;">' +
                            '<div class="action-grid" style="margin: 0 auto; justify-content: center;">' +
                                '<a href="#" class="btn-negative btn-sq" title="Delete" onclick="handleDeleteReminder(' + data.reminder.id + ', this); return false;">' +
                                    '<i class="fa fa-trash"></i>' +
                                '</a>' +
                            '</div>' +
                        '</td>' +
                    '</tr>';

                insertionRow.insertAdjacentHTML('beforebegin', newRowHTML);
            } else {
                alert('Failed to save reminder.');
            }
        })
        .catch(function(err) {
            console.error(err);
            alert('An error occurred.');
        })
        .finally(function() {
            submitBtn.innerHTML = originalHtml;
            submitBtn.disabled = false;
        });
    }

    function handleDeleteReminder(id, buttonElement) {
        if(!confirm("Are you sure want to delete this reminder? This action cannot be undone.")) return;

        const deleteUrl = '/subReminder/' + encodeURIComponent(id);

        fetch(deleteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if(data.success) {
                const row = buttonElement.closest('tr');
                row.remove();
            } else {
                alert('Failed to delete reminder.');
            }
        })
        .catch(function(err) {
            console.error(err);
            alert('An error occurred while deleting.');
        });
    }
</script>
@endsection