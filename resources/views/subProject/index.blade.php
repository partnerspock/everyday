@extends('layouts.app')

@section('title', $space->title_spaces . ' | Projects')

@section('content')
<main class="workspace">
    <!-- Form Create Project (Hidden - Submitted via AJAX) -->
    <form id="createProjectForm" method="POST" action="{{ route('subProject.store', $space->id) }}" style="display: none;">
        @csrf
    </form>

    <div class="page-container">
        <!-- Sticky Header Index -->
        <div class="form-index-header">
            <div class="header-left">
                <a href="{{ route('spaces.index', $space->id) }}" class="btn-secondary" id="btnBack">
                    <i class="fa fa-chevron-left" style="margin-right: 6px;"></i> Back
                </a>
            </div>
        </div>

        <!-- Grouped Table Container -->
        <div class="data-table-container">
            <div class="table-header-row">
                <span class="table-title">{{ $space->title_spaces }} | Projects</span>
                <input
                    id="projectSearch"
                    type="text"
                    placeholder="Search projects..."
                    oninput="filterProjects(this.value)"
                    class="ios-input"
                    style="max-width: 220px;">
            </div>

            <table class="projects-table">
                <thead>
                    <tr>
                        <th style="width: 200px;">Title</th>
                        <th style="width: auto;">Description</th>
                        <th style="width: 150px; text-align: center;">Status</th>
                        <th style="width: 140px; text-align: center;">Start Date</th>
                        <th style="width: 140px; text-align: center;">End Date</th>
                        <th style="width: 130px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="projectTableBody">
                    @forelse ($subProject as $sProject)
                        @php
                            $filesArray = $sProject->files->map(function($f) {
                                return [
                                    'id'   => $f->id,
                                    'name' => $f->original_filename,
                                    'url'  => Storage::url($f->file_path),
                                    'size' => $f->formatted_size ?? ''
                                ];
                            })->values();
                            $hasFiles = count($filesArray) > 0;
                        @endphp
                        <tr class="project-row perm-row" data-title="{{ strtolower($sProject->pro_title) }}" id="row-{{ $sProject->id }}">
                            <td id="title-{{ $sProject->id }}">
                                <strong>{{ $sProject->pro_title }}</strong>
                            </td>
                            <td id="description-{{ $sProject->id }}" style="color: var(--text-secondary);">
                                {{ $sProject->pro_description ?? '-' }}
                            </td>

                            <!-- STATUS SELECT -->
                            <td style="padding: 6px 8px;">
                                <div class="ios-select-wrapper">
                                    <select name="pro_status" class="ios-select badge-{{ $sProject->pro_status->color() }}" onchange="autoSaveField(this, {{ $sProject->id }}, 'pro_status')">
                                        @foreach(App\Enums\ProjectStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $sProject->pro_status === $status ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>

                            <!-- START DATE -->
                            <td style="text-align: center; padding: 6px 8px;">
                                <div class="date-display-wrapper" onclick="enableDateEdit(this)" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                    <span class="date-text" id="start-date-text-{{ $sProject->id }}" style="font-size: 0.88rem; white-space: nowrap;">
                                        {{ $sProject->pro_start_at?->format('M d, Y') ?? 'Set date' }}
                                    </span>
                                    <i class="fa-regular fa-calendar-plus" style="opacity: 0.4; font-size: 0.85rem;"></i>
                                </div>
                                <div class="ios-date-wrapper" style="display: none;">
                                    <input type="date"
                                           value="{{ $sProject->pro_start_at?->format('Y-m-d') }}"
                                           class="ios-date"
                                           onchange="autoSaveField(this, {{ $sProject->id }}, 'pro_start_at')">
                                </div>
                            </td>

                            <!-- END DATE -->
                            <td style="text-align: center; padding: 6px 8px;">
                                <div class="date-display-wrapper" onclick="enableDateEdit(this)" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                    <span class="date-text" id="end-date-text-{{ $sProject->id }}" style="font-size: 0.88rem; white-space: nowrap;">
                                        {{ $sProject->pro_end_at?->format('M d, Y') ?? 'Set date' }}
                                    </span>
                                    <i class="fa-regular fa-calendar-plus" style="opacity: 0.4; font-size: 0.85rem;"></i>
                                </div>
                                <div class="ios-date-wrapper" style="display: none;">
                                    <input type="date"
                                           id="end-date-input-{{ $sProject->id }}"
                                           value="{{ $sProject->pro_end_at?->format('Y-m-d') }}"
                                           class="ios-date"
                                           onchange="autoSaveField(this, {{ $sProject->id }}, 'pro_end_at')">
                                </div>
                            </td>

                            <td style="text-align: center;" id="action-cell-{{ $sProject->id }}">
                                <div class="action-grid" id="action-buttons-{{ $sProject->id }}" style="margin: 0 auto; justify-content: center; display: flex; gap: 4px;">
                                    <a href="#"
                                       class="btn-secondary btn-sq"
                                       title="Upload Attachment"
                                       onclick="openUploadModal({{ $sProject->id }}); return false;">
                                        <i class="fa fa-cloud-upload"></i>
                                    </a>

                                    <a href="#"
                                       id="btn-view-{{ $sProject->id }}"
                                       class="btn-secondary btn-sq {{ !$hasFiles ? 'disabled-btn' : '' }}"
                                       title="{{ $hasFiles ? 'View Attachments' : 'No Files Uploaded' }}"
                                       data-files='@json($filesArray)'
                                       style="position: relative; {{ !$hasFiles ? 'opacity: 0.35; cursor: not-allowed; pointer-events: none;' : '' }}"
                                       onclick="openViewModal({{ $sProject->id }}, this); return false;">
                                        <i class="fa fa-cloud"></i>
                                        <span id="file-count-badge-{{ $sProject->id }}" 
                                        class="file-badge" 
                                        style="{{ !$hasFiles ? 'display: none;' : 'display: flex;' }}">
                                            {{ count($filesArray) }}
                                        </span>
                                    </a>

                                    <a href="#"
                                       class="btn-negative btn-sq"
                                       title="Delete"
                                       onclick="handleDeleteProject({{ $sProject->id }}, this); return false;">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>

                                <div id="upload-progress-container-{{ $sProject->id }}" style="display: none; flex-direction: column; gap: 4px; align-items: center;">
                                    <div style="width: 80px; background: var(--fill-primary); height: 6px; border-radius: 10px; overflow: hidden; border: 1px solid var(--separator);">
                                        <div id="upload-progress-bar-{{ $sProject->id }}" style="width: 0%; height: 100%; background: var(--ios-blue); transition: width 0.15s ease;"></div>
                                    </div>
                                    <span id="upload-progress-text-{{ $sProject->id }}" style="font-size: 0.72rem; color: var(--text-secondary); font-weight: 600;">0%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-state-row">
                            <td colspan="6">
                                <div class="notes-empty-state">
                                    <div class="notes-empty-state-title">Nothing here</div>
                                    <div class="notes-empty-state-sub">Let's create your first project.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="insertion-row" style="background: var(--fill-secondary);">
                        <td style="padding: 6px 8px;">
                            <input type="text" name="pro_title" form="createProjectForm" class="ios-input" placeholder="New Title..." style="width: 100%;" required>
                        </td>
                        <td style="padding: 6px 8px;">
                            <input type="text" name="pro_description" form="createProjectForm" class="ios-input" placeholder="Description..." style="width: 100%;">
                        </td>
                        <td style="padding: 6px 8px;">
                            <div class="ios-select-wrapper">
                                <select name="pro_status" form="createProjectForm" class="ios-select">
                                    @foreach(App\Enums\ProjectStatus::cases() as $status)
                                        <option value="{{ $status->value }}">
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td style="padding: 6px 8px;">
                            <div class="ios-date-wrapper">
                                <input type="date" name="pro_start_at" form="createProjectForm" class="ios-date">
                            </div>
                        </td>
                        <td style="padding: 6px 8px;">
                            <div class="ios-date-wrapper">
                                <input type="date" name="pro_end_at" form="createProjectForm" class="ios-date">
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <button type="button" onclick="handleCreateProject(event)" class="btn-primary btn-sq" id="btnSaveProject" style="margin: 0 auto;" title="Save Project">
                                <i class="fa fa-save"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="uploadProjectModal" class="custom-modal-overlay" style="display: none;" onclick="handleOverlayClick(event, 'uploadProjectModal')">
        <div class="custom-modal-card">
            <h3 class="modal-title" style="margin-bottom: 14px;">Upload Attachment</h3>
            <form id="uploadProjectForm" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="upload_project_id" name="project_id">
                <div class="form-field" style="margin-bottom: 20px;">
                    <label class="form-label">Select Files (Max 10MB per file)</label>
                    <input type="file" name="attachments[]" id="file_attachment" multiple required style="display: none;" onchange="updateFileName(this)">
                    <div class="custom-file-wrapper" style="display: flex; align-items: center; gap: 10px; background: var(--fill-primary); padding: 6px 10px; border-radius: 8px; border: 1px solid var(--separator); margin-top: 8px;">
                        <label for="file_attachment" class="btn-secondary" style="margin: 0; cursor: pointer; font-size: 0.82rem;">
                            <i class="fa fa-cloud-upload" style="margin-right: 6px;"></i> Choose Files
                        </label>
                        <span id="file-name-text" class="file-name-display" style="font-size: 0.82rem; color: var(--text-tertiary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">No files selected</span>
                    </div>
                </div>
                <div class="custom-modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('uploadProjectModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <div id="viewProjectFilesModal" class="custom-modal-overlay" style="display: none;" onclick="handleOverlayClick(event, 'viewProjectFilesModal')">
        <div class="custom-modal-card">
            <h3 class="modal-title" style="margin-bottom: 14px;">Uploaded Attachments</h3>
            <div id="existingFilesList" style="display: flex; flex-direction: column; gap: 8px; max-height: 250px; overflow-y: auto; margin-bottom: 20px;">
            </div>
            <div class="custom-modal-actions">
                <button type="button" 
                        class="btn-secondary" 
                        onclick="closeModal('viewProjectFilesModal'); openUploadModal({{ $sProject->id }}); return false;">
                    <i class="fa fa-cloud-upload" style="margin-right: 6px;"></i> Upload
                </button>
                <button type="button" class="btn-secondary" onclick="closeModal('viewProjectFilesModal')">Close</button>
            </div>
        </div>
    </div>
</main>

<script>
function openUploadModal(projectId) {
    const modal = document.getElementById('uploadProjectModal');
    const inputProjectId = document.getElementById('upload_project_id');
    const uploadForm = document.getElementById('uploadProjectForm');

    if (inputProjectId) inputProjectId.value = projectId;
    if (uploadForm) uploadForm.action = '/subProject/' + projectId + '/upload';

    if (modal) modal.style.display = 'flex';
}

function openViewModal(projectId, buttonElement) {
    const modal = document.getElementById('viewProjectFilesModal');
    const container = document.getElementById('existingFilesList');
    container.innerHTML = '';

    const files = JSON.parse(buttonElement.dataset.files || '[]');

    if (!files || files.length === 0) {
        container.innerHTML = '<span style="font-size: 0.82rem; color: var(--text-tertiary); text-align: center; padding: 12px 0;">No files attached to this project.</span>';
    } else {
        files.forEach(file => {
            const row = document.createElement('div');
            row.id = `file-row-${file.id}`;
            row.style.cssText = 'display:flex; align-items:center; justify-content:space-between; gap:8px; padding:6px 10px; background:var(--fill-secondary); border:1px solid var(--separator); border-radius:8px;';

            row.innerHTML = `
                <span style="font-size:0.85rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1;" title="${file.name}">${file.name}</span>
                <span style="font-size:0.72rem; color:var(--text-tertiary); flex-shrink:0;">${file.size}</span>
                <div style="display:flex; gap:4px; flex-shrink:0;">
                    <a href="${file.url}" target="_blank" class="btn-secondary btn-sq" title="Preview">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="/subProject/file/${file.id}/download" download class="btn-primary btn-sq" title="Download">
                        <i class="fa fa-download"></i>
                    </a>
                    <!-- Tombol Delete File -->
                    <button type="button" 
                            class="btn-negative btn-sq" 
                            title="Delete File" 
                            onclick="deleteAttachment(${file.id}, ${projectId})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
        });
    }

    if (modal) modal.style.display = 'flex';
}

function deleteAttachment(fileId, projectId) {
    if (!confirm('Are you sure want to delete this file?')) return;

    fetch('/subProject/file/' + fileId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // 1. Hapus elemen baris file dari DOM modal
            const fileRow = document.getElementById('file-row-' + fileId);
            if (fileRow) fileRow.remove();

            // 2. Update data-files di tombol View
            const viewBtn = document.getElementById('btn-view-' + projectId);
            const badge = document.getElementById('file-count-badge-' + projectId);

            if (viewBtn) {
                let existingFiles = JSON.parse(viewBtn.dataset.files || '[]');
                // Filter hapus file terkait dari array JSON
                existingFiles = existingFiles.filter(f => f.id !== fileId);
                viewBtn.dataset.files = JSON.stringify(existingFiles);

                // 3. Update Badge Angka & Status Tombol
                if (existingFiles.length > 0) {
                    if (badge) {
                        badge.innerText = existingFiles.length;
                        badge.style.display = 'flex';
                    }
                } else {
                    // Jika file habis (0)
                    if (badge) badge.style.display = 'none';
                    viewBtn.title = 'No Files Uploaded';
                    viewBtn.style.opacity = '0.35';
                    viewBtn.style.cursor = 'not-allowed';
                    viewBtn.style.pointerEvents = 'none';
                    viewBtn.classList.add('disabled-btn');

                    // Tampilkan pesan kosong di modal
                    const container = document.getElementById('existingFilesList');
                    if (container) {
                        container.innerHTML = '<span style="font-size: 0.82rem; color: var(--text-tertiary); text-align: center; padding: 12px 0;">No files attached to this project.</span>';
                    }
                }
            }
        } else {
            alert('Failed to delete file: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error('Error deleting file:', err);
        alert('An error occurred while deleting the file.');
    });
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'none';
}

function handleOverlayClick(event, modalId) {
    if (event.target.id === modalId) {
        closeModal(modalId);
    }
}

function updateFileName(input) {
    const fileNameText = document.getElementById('file-name-text');
    if (input.files && input.files.length > 0) {
        if (input.files.length === 1) {
            fileNameText.innerText = input.files[0].name;
        } else {
            fileNameText.innerText = input.files.length + ' files selected';
        }
        fileNameText.style.color = 'var(--text-primary)';
    } else {
        fileNameText.innerText = 'No files selected';
        fileNameText.style.color = 'var(--text-tertiary)';
    }
}

function enableDateEdit(wrapperElement) {
    const td = wrapperElement.closest('td');
    const inputWrapper = td.querySelector('.ios-date-wrapper');

    wrapperElement.style.display = 'none';
    inputWrapper.style.display = 'flex';

    const input = inputWrapper.querySelector('input[type="date"]');
    if (input && typeof input.showPicker === 'function') {
        input.showPicker();
    }
}

function autoSaveField(element, projectId, fieldName) {
    const fieldValue = element.value;
    const payload = {};
    payload[fieldName] = fieldValue;

    fetch('/subProject/' + projectId + '/update-field', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (fieldName === 'pro_status') {
                element.className = 'ios-select badge-' + data.status_color;

                if (data.pro_end_at) {
                    const endDateText = document.getElementById('end-date-text-' + projectId);
                    const endDateInput = document.getElementById('end-date-input-' + projectId);
                    if (endDateText) endDateText.innerText = data.end_at_formatted;
                    if (endDateInput) endDateInput.value = data.pro_end_at;
                }
            }

            if (fieldName === 'pro_start_at') {
                document.getElementById('start-date-text-' + projectId).innerText = data.start_at_formatted;
                switchBackToDisplay(element);
            }
            if (fieldName === 'pro_end_at') {
                document.getElementById('end-date-text-' + projectId).innerText = data.end_at_formatted;
                switchBackToDisplay(element);
            }
        }
    })
    .catch(err => console.error('Error auto-saving:', err));
}

function switchBackToDisplay(inputElement) {
    const td = inputElement.closest('td');
    const displayWrapper = td.querySelector('.date-display-wrapper');
    const inputWrapper = td.querySelector('.ios-date-wrapper');

    inputWrapper.style.display = 'none';
    displayWrapper.style.display = 'inline-flex';
}

// 4. Search Filter
function filterProjects(query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('.project-row');

    rows.forEach(row => {
        const title = row.dataset.title || '';
        const match = title.includes(q);
        row.style.display = match ? '' : 'none';
    });
}

function handleCreateProject(event) {
    event.preventDefault();

    const titleInput   = document.querySelector('input[name="pro_title"]');
    const descInput    = document.querySelector('input[name="pro_description"]');
    const statusSelect = document.querySelector('select[name="pro_status"][form="createProjectForm"]');
    const startInput   = document.querySelector('input[name="pro_start_at"]');
    const endInput     = document.querySelector('input[name="pro_end_at"]');

    if (!titleInput.value.trim()) {
        alert('Title project tidak boleh kosong!');
        return;
    }

    const btnSave = document.getElementById('btnSaveProject');
    const originalHtml = btnSave.innerHTML;
    btnSave.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    btnSave.disabled = true;

    const payload = {
        pro_title: titleInput.value,
        pro_description: descInput.value,
        pro_status: statusSelect.value,
        pro_start_at: startInput ? startInput.value : null,
        pro_end_at: endInput ? endInput.value : null,
    };

    fetch('{{ route("subProject.store", $space->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert('Failed to save project.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred.');
    })
    .finally(() => {
        btnSave.innerHTML = originalHtml;
        btnSave.disabled = false;
    });
}

// 6. Delete Project AJAX
function handleDeleteProject(id, buttonElement) {
    if(!confirm("Are you sure want to delete this project?")) return;

    fetch('/subProject/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const row = buttonElement.closest('tr');
            row.remove();
        } else {
            alert('Failed to delete project.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred while deleting.');
    });
}

// 7. Multi-File Upload Handling via AJAX + Dynamic Eye Button Activation
document.getElementById('uploadProjectForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const actionUrl = form.action;
    const projectId = document.getElementById('upload_project_id').value;
    const fileInput = document.getElementById('file_attachment');
    const files = fileInput.files;

    if (!files || files.length === 0) {
        alert('Pilih file terlebih dahulu!');
        return;
    }

    for (let i = 0; i < files.length; i++) {
        if (files[i].size > 10 * 1024 * 1024) {
            alert('File "' + files[i].name + '" melebihi batas ukuran 10MB!');
            return;
        }
    }

    closeModal('uploadProjectModal');
    document.getElementById('file-name-text').innerText = 'No files selected';
    document.getElementById('file-name-text').style.color = 'var(--text-tertiary)';

    const actionButtons = document.getElementById('action-buttons-' + projectId);
    const progressContainer = document.getElementById('upload-progress-container-' + projectId);
    const progressBar = document.getElementById('upload-progress-bar-' + projectId);
    const progressText = document.getElementById('upload-progress-text-' + projectId);

    if (actionButtons) actionButtons.style.display = 'none';
    if (progressContainer) progressContainer.style.display = 'flex';

    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('attachments[]', files[i]);
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', actionUrl, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            if (progressBar) progressBar.style.width = percent + '%';
            if (progressText) progressText.innerText = percent + '%';
        }
    };

    xhr.onload = function() {
    if (xhr.status >= 200 && xhr.status < 300) {
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                const viewBtn = document.getElementById('btn-view-' + projectId);
                const badge = document.getElementById('file-count-badge-' + projectId);

                if (viewBtn) {
                    const existing = JSON.parse(viewBtn.dataset.files || '[]');
                    const newFiles = response.files.map(f => ({
                        id: f.id,
                        name: f.name,
                        url: f.url,
                        size: f.size_human ?? ''
                    }));
                    const merged = existing.concat(newFiles);

                    viewBtn.dataset.files = JSON.stringify(merged);
                    viewBtn.title = 'View Attachments';
                    viewBtn.style.opacity = '1';
                    viewBtn.style.cursor = 'pointer';
                    viewBtn.style.pointerEvents = 'auto';
                    viewBtn.classList.remove('disabled-btn');

                    if (badge) {
                        badge.innerText = merged.length;
                        badge.style.display = 'inline-block';
                    }
                }

                if (progressContainer) {
                    progressContainer.innerHTML =
                        '<div style="display: flex; align-items: center; justify-content: center; gap: 4px; color: var(--ios-green); font-size: 0.82rem; font-weight: 600;">' +
                            '<i class="fa fa-circle-check"></i> Uploaded' +
                        '</div>';
                }
                setTimeout(() => revertUploadUI(projectId), 1000);
            } else {
                alert('Upload gagal: ' + (response.message || 'Unknown error'));
                revertUploadUI(projectId);
            }
        } catch (err) {
            console.error('JSON Parse Error / Raw Response:', xhr.responseText);
            alert('Gagal membaca respons dari server.');
            revertUploadUI(projectId);
        }
    }
};

    xhr.onerror = function() {
        alert('Koneksi terputus saat mengunggah file.');
        revertUploadUI(projectId);
        form.reset();
    };

    xhr.send(formData);
});

function revertUploadUI(projectId) {
    const actionButtons = document.getElementById('action-buttons-' + projectId);
    const progressContainer = document.getElementById('upload-progress-container-' + projectId);

    if (actionButtons) actionButtons.style.display = 'flex';
    if (progressContainer) {
        progressContainer.style.display = 'none';
        progressContainer.innerHTML =
            '<div style="width: 80px; background: var(--fill-primary); height: 6px; border-radius: 10px; overflow: hidden; border: 1px solid var(--separator);">' +
                '<div id="upload-progress-bar-' + projectId + '" style="width: 0%; height: 100%; background: var(--ios-blue); transition: width 0.15s ease;"></div>' +
            '</div>' +
            '<span id="upload-progress-text-' + projectId + '" style="font-size: 0.72rem; color: var(--text-secondary); font-weight: 600;">0%</span>';
    }
}
</script>
@endsection