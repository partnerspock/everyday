// Everyday Dashboard JS State & Interaction Controller

document.addEventListener('DOMContentLoaded', () => {
    // Initial Mock Databases
    const defaultAdmins = [
        { id: 'usr-1', name: 'Admin', username: 'admin', email: 'admin@everyday.test', role: 'Super Admin', deactivated_at: null },
        { id: 'usr-2', name: 'John Doe', username: 'john', email: 'john@everyday.test', role: 'Admin', deactivated_at: null },
        { id: 'usr-3', name: 'Jane Smith', username: 'jane', email: 'jane@everyday.test', role: 'Admin', deactivated_at: '2026-07-10T12:00:00Z' }
    ];

    const defaultNotes = [
        { id: 'note-1', created_user: 'usr-1', title_notes: 'Ideas for next project', description: 'Bold this text and enjoy simple formatting.', password: null, deletedAt: null, createdAt: new Date().toISOString() },
        { id: 'note-2', created_user: 'usr-1', title_notes: 'Personal credentials', description: 'Keep this private. Locked with custom password.', password: 'password123', deletedAt: null, createdAt: new Date().toISOString() }
    ];

    const defaultProjectStatuses = [
        { id: 'status-1', title_status: 'Not Started', colour_status: '#9ca3af' },
        { id: 'status-2', title_status: 'In Progress', colour_status: '#3b82f6' },
        { id: 'status-3', title_status: 'On Hold', colour_status: '#f59e0b' },
        { id: 'status-4', title_status: 'Completed', colour_status: '#10b981' }
    ];

    // State Variables
    let currentAdmin = defaultAdmins[0]; // Simulated logged-in user
    let admins = JSON.parse(localStorage.getItem('everyday_admins')) || defaultAdmins;
    let generalNotes = JSON.parse(localStorage.getItem('everyday_general_notes')) || defaultNotes;
    let spaces = JSON.parse(localStorage.getItem('everyday_spaces')) || [
        { id: 'space-1', created_user: 'usr-1', title_spaces: 'Coding Space', deletedAt: null }
    ];
    let projectStatuses = JSON.parse(localStorage.getItem('everyday_project_statuses')) || defaultProjectStatuses;

    let projects = JSON.parse(localStorage.getItem('everyday_projects')) || [
        { id: 'proj-1', id_spaces: 'space-1', row_title: 'UI Design Refactoring', row_status: 'status-2', start_at: '2026-07-13', end_at: '2026-07-20', description: 'Upgrades to the main dashboard aesthetic layout.', files: [{ name: 'dashboard_redesign.pdf', size: '2.4 MB' }], deletedAt: null }
    ];

    let reminders = JSON.parse(localStorage.getItem('everyday_reminders')) || [
        { id: 'rem-1', id_spaces: 'space-1', row_title: 'Review home.blade.php layout code', row_description: 'Need to styling the elements', row_status: 'not_ticked', deletedAt: null },
        { id: 'rem-2', id_spaces: 'space-1', row_title: 'Draft implementation plan document', row_description: 'Approved by user', row_status: 'ticked', deletedAt: null }
    ];

    let spaceNotes = JSON.parse(localStorage.getItem('everyday_space_notes')) || [
        { id: 's-note-1', id_spaces: 'space-1', title_notes: 'Space note 1', description: 'Space notes can not assign or unassign to general notes.', password: null, deletedAt: null }
    ];

    let activityLogs = JSON.parse(localStorage.getItem('everyday_activity_logs')) || [
        { id: 'log-1', admin_name: 'Admin', module: 'auth', action: 'login', description: 'Admin successfully logged in', timestamp: new Date().toISOString() }
    ];

    // Keep track of active states in current UI session
    let activeModule = 'general_notes'; // general_notes, space_detail, roles, activity_log
    let activeSpaceId = null;
    let activeGeneralNoteId = null;
    let activeSpaceNoteId = null;
    let unlockedNotes = {}; // noteId -> boolean (in-memory lock sessions)
    let currentSpaceTab = 'projects'; // projects, reminders, notes

    // Helper functions: Saves to LocalStorage
    function saveAll() {
        localStorage.setItem('everyday_admins', JSON.stringify(admins));
        localStorage.setItem('everyday_general_notes', JSON.stringify(generalNotes));
        localStorage.setItem('everyday_spaces', JSON.stringify(spaces));
        localStorage.setItem('everyday_project_statuses', JSON.stringify(projectStatuses));
        localStorage.setItem('everyday_projects', JSON.stringify(projects));
        localStorage.setItem('everyday_reminders', JSON.stringify(reminders));
        localStorage.setItem('everyday_space_notes', JSON.stringify(spaceNotes));
        localStorage.setItem('everyday_activity_logs', JSON.stringify(activityLogs));
    }

    function logActivity(module, action, description) {
        const log = {
            id: 'log-' + Math.random().toString(36).substr(2, 9),
            admin_name: currentAdmin.name,
            module: module,
            action: action,
            description: description,
            timestamp: new Date().toISOString()
        };
        activityLogs.unshift(log);
        saveAll();
    }

    // Modal Control functions
    window.openModal = function (modalId) {
        document.getElementById(modalId).style.display = 'flex';
    };

    window.closeModal = function (modalId) {
        document.getElementById(modalId).style.display = 'none';
    };

    // UI View switcher
    function switchModule(moduleName, spaceId = null) {
        activeModule = moduleName;
        activeSpaceId = spaceId;

        // Update sidebar links highlighting
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        document.querySelectorAll('.space-item').forEach(item => item.classList.remove('active'));

        if (spaceId) {
            const spaceItem = document.querySelector(`.space-item[data-id="${spaceId}"]`);
            if (spaceItem) spaceItem.classList.add('active');
            document.getElementById('nav-spaces-top').classList.add('active');
        } else {
            const navItem = document.getElementById(`nav-${moduleName}`);
            if (navItem) navItem.classList.add('active');
        }

        // Hide all module blocks, display active
        document.querySelectorAll('.module-container').forEach(container => {
            container.classList.remove('active');
        });

        const activeContainer = document.getElementById(`module-${moduleName}`);
        if (activeContainer) activeContainer.classList.add('active');

        // Trigger module list loading
        if (moduleName === 'general_notes') {
            renderGeneralNotes();
        } else if (moduleName === 'space_detail' && spaceId) {
            renderSpaceDetails(spaceId);
        } else if (moduleName === 'roles') {
            renderRoles();
        } else if (moduleName === 'activity_log') {
            renderActivityLogs();
        }
    }

    // Connect Sidebar triggers
    document.getElementById('nav-general_notes').addEventListener('click', () => switchModule('general_notes'));
    document.getElementById('nav-roles').addEventListener('click', () => switchModule('roles'));
    document.getElementById('nav-activity_log').addEventListener('click', () => switchModule('activity_log'));

    // Toggle spaces menu list dropdown
    let spacesExpanded = true;
    document.getElementById('nav-spaces-top').addEventListener('click', (e) => {
        if (e.target.closest('#btn-add-space-nav')) return; // ignore click on create space trigger
        const submenu = document.getElementById('spaces-submenu-list');
        spacesExpanded = !spacesExpanded;
        submenu.style.display = spacesExpanded ? 'flex' : 'none';
        const indicator = document.getElementById('spaces-expand-indicator');
        indicator.textContent = spacesExpanded ? '▼' : '►';
    });

    // Handle space creation
    document.getElementById('btn-add-space-nav').addEventListener('click', (e) => {
        e.stopPropagation();
        openModal('modal-create-space');
    });

    document.getElementById('form-create-space').addEventListener('submit', (e) => {
        e.preventDefault();
        const titleInput = document.getElementById('space-title-field');
        const title = titleInput.value.trim();
        if (!title) return;

        const newSpace = {
            id: 'space-' + Math.random().toString(36).substr(2, 9),
            created_user: currentAdmin.id,
            title_spaces: title,
            deletedAt: null
        };
        spaces.push(newSpace);
        logActivity('spaces', 'create', `Created space "${title}"`);
        titleInput.value = '';
        closeModal('modal-create-space');
        renderSpacesNav();
        switchModule('space_detail', newSpace.id);
    });

    // Render Spaces list in sidebar
    function renderSpacesNav() {
        const submenuList = document.getElementById('spaces-submenu-list');
        // Clear all except "+ Create Space" button if any
        const spaceElements = submenuList.querySelectorAll('.space-item');
        spaceElements.forEach(el => el.remove());

        const activeSpaces = spaces.filter(s => s.deletedAt === null);

        activeSpaces.forEach(space => {
            const item = document.createElement('div');
            item.className = 'space-item';
            item.setAttribute('data-id', space.id);
            item.innerHTML = `
                <span>📁 ${space.title_spaces}</span>
                <span class="space-del-indicator" style="font-size:0.75rem; opacity:0; transition:opacity 0.2s;">🗑️</span>
            `;

            // Hover action
            item.addEventListener('mouseenter', () => {
                item.querySelector('.space-del-indicator').style.opacity = '1';
            });
            item.addEventListener('mouseleave', () => {
                item.querySelector('.space-del-indicator').style.opacity = '0';
            });

            // Delete action
            item.querySelector('.space-del-indicator').addEventListener('click', (e) => {
                e.stopPropagation();
                if (confirm(`Are you sure you want to delete workspace "${space.title_spaces}"? This will delete all lists (projects, reminders, space notes) inside it.`)) {
                    deleteSpace(space.id);
                }
            });

            item.addEventListener('click', () => switchModule('space_detail', space.id));
            submenuList.insertBefore(item, document.getElementById('sidebar-create-space-btn-li'));
        });
    }

    function deleteSpace(spaceId) {
        const space = spaces.find(s => s.id === spaceId);
        if (!space) return;

        const now = new Date().toISOString();
        space.deletedAt = now;

        // Cascade soft delete projects
        projects.forEach(p => {
            if (p.id_spaces === spaceId) p.deletedAt = now;
        });

        // Cascade soft delete reminders
        reminders.forEach(r => {
            if (r.id_spaces === spaceId) r.deletedAt = now;
        });

        // Cascade soft delete space notes
        spaceNotes.forEach(sn => {
            if (sn.id_spaces === spaceId) sn.deletedAt = now;
        });

        logActivity('spaces', 'delete', `Soft deleted space "${space.title_spaces}" and all its sub-records`);
        renderSpacesNav();

        // Redirect back to general notes
        if (activeSpaceId === spaceId) {
            switchModule('general_notes');
        }
    }


    /* =========================================
       MODULE: GENERAL NOTES
       ========================================= */

    // Search general notes
    document.getElementById('notes-search-input').addEventListener('input', () => {
        renderGeneralNotes();
    });

    document.getElementById('btn-create-note-gen').addEventListener('click', () => {
        const newNote = {
            id: 'note-' + Math.random().toString(36).substr(2, 9),
            created_user: currentAdmin.id,
            title_notes: 'New Note',
            description: 'Write something...',
            password: null,
            deletedAt: null,
            createdAt: new Date().toISOString()
        };
        generalNotes.unshift(newNote);
        logActivity('general_notes', 'create', `Created general note "${newNote.title_notes}"`);
        activeGeneralNoteId = newNote.id;
        renderGeneralNotes();
    });

    // Formatting Toolbar: use browser execCommand
    document.querySelectorAll('.btn-format').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const command = btn.getAttribute('data-command');
            document.execCommand(command, false, null);
            saveNoteEditorContent();
        });
    });

    function renderGeneralNotes() {
        const searchQuery = document.getElementById('notes-search-input').value.toLowerCase();
        const listDiv = document.getElementById('general-notes-list-items');
        listDiv.innerHTML = '';

        const activeNotes = generalNotes.filter(n => n.deletedAt === null && (
            n.title_notes.toLowerCase().includes(searchQuery) ||
            (n.description && n.description.toLowerCase().includes(searchQuery))
        ));

        if (!activeNotes.find(n => n.id === activeGeneralNoteId) && activeNotes.length > 0) {
            activeGeneralNoteId = activeNotes[0].id;
        } else if (activeNotes.length === 0) {
            activeGeneralNoteId = null;
        }

        activeNotes.forEach(note => {
            const item = document.createElement('div');
            item.className = `note-list-item ${note.id === activeGeneralNoteId ? 'active' : ''}`;

            const isLocked = note.password !== null;
            const previewText = isLocked ? 'Locked note' : (note.description ? note.description.replace(/<[^>]*>/g, '').substring(0, 45) + '...' : 'Empty');

            item.innerHTML = `
                <div class="note-item-title">${note.title_notes}</div>
                <div class="note-item-snippet">
                    ${isLocked ? '🔒' : '📝'} ${previewText}
                </div>
            `;

            item.addEventListener('click', () => {
                activeGeneralNoteId = note.id;
                renderGeneralNotes();
            });

            listDiv.appendChild(item);
        });

        renderNoteEditor();
    }

    function renderNoteEditor() {
        const editorContainer = document.getElementById('general-notes-editor-pane');

        if (!activeGeneralNoteId) {
            editorContainer.innerHTML = `
                <div style="flex-grow:1; display:flex; align-items:center; justify-content:center; color:var(--text-secondary);">
                    Select a note or create a new one to begin writing.
                </div>
            `;
            return;
        }

        const note = generalNotes.find(n => n.id === activeGeneralNoteId);

        // Check lock status
        if (note.password && !unlockedNotes[note.id]) {
            // Render Unlock Screen
            editorContainer.innerHTML = `
                <div style="position:relative; height:100%; width:100%;">
                    <div class="editor-lock-overlay">
                        <div class="editor-lock-icon">🔒</div>
                        <h3 style="font-weight:600;">Locked Note</h3>
                        <p style="font-size:0.85rem; color:var(--text-secondary);">This note is encrypted and password-protected.</p>
                        <input class="form-input-text" type="password" id="input-unlock-password" placeholder="Enter password" class="form-input" style="text-align:center;">
                        <div id="unlock-error" style="color:var(--accent-danger); font-size:0.8rem; display:none;">Invalid Password</div>
                        <button class="btn-primary" id="btn-unlock-note">Unlock</button>
                        <button class="btn-secondary" id="btn-delete-forgot-note" style="color:var(--accent-danger); border-color:rgba(239,68,68,0.2);">Forgot password? Delete Note</button>
                    </div>
                </div>
            `;

            document.getElementById('btn-unlock-note').addEventListener('click', () => {
                const pass = document.getElementById('input-unlock-password').value;
                if (pass === note.password) {
                    unlockedNotes[note.id] = true;
                    logActivity('general_notes', 'unlock', `Unlocked general note "${note.title_notes}"`);
                    renderGeneralNotes();
                } else {
                    document.getElementById('unlock-error').style.display = 'block';
                }
            });

            document.getElementById('btn-delete-forgot-note').addEventListener('click', () => {
                if (confirm('If you forgot your password, you must delete this note. Are you sure?')) {
                    deleteActiveGeneralNote();
                }
            });
            return;
        }

        // Render main editor view
        const isLocked = note.password !== null;
        editorContainer.innerHTML = `
            <div class="editor-toolbar">
                <div class="toolbar-formatting">
                    <button class="btn-format" data-command="bold" title="Bold">B</button>
                    <button class="btn-format" data-command="italic" title="Italic">I</button>
                    <button class="btn-format" data-command="underline" title="Underline">U</button>
                </div>
                <div class="editor-actions">
                    ${isLocked
                ? `<button class="btn-action" id="btn-remove-lock-gen">🔓 Remove Lock</button>`
                : `<button class="btn-action" id="btn-lock-gen">🔒 Lock Note</button>`}
                    <button class="btn-action danger" id="btn-delete-gen">🗑️ Delete</button>
                </div>
            </div>
            <div class="editor-title-container">
                <input type="text" class="editor-title-input" id="note-title-input" value="${note.title_notes}" placeholder="Rename Notes Title">
            </div>
            <div class="editor-body" contenteditable="true" id="note-body-content">${note.description || ''}</div>
        `;

        // Connect editor listeners
        const titleInput = document.getElementById('note-title-input');
        const bodyContent = document.getElementById('note-body-content');

        titleInput.addEventListener('input', () => {
            const oldTitle = note.title_notes;
            note.title_notes = titleInput.value || 'Untitled Note';
            // Live update list item
            const items = document.querySelectorAll('.note-list-item');
            const activeIndex = generalNotes.filter(n => n.deletedAt === null).findIndex(n => n.id === activeGeneralNoteId);
            if (items[activeIndex]) {
                items[activeIndex].querySelector('.note-item-title').textContent = note.title_notes;
            }
            saveAll();
        });

        titleInput.addEventListener('blur', () => {
            logActivity('general_notes', 'rename', `Renamed note to "${note.title_notes}"`);
        });

        bodyContent.addEventListener('input', saveNoteEditorContent);

        // Sub Actions
        if (isLocked) {
            document.getElementById('btn-remove-lock-gen').addEventListener('click', () => {
                const pass = prompt('Enter your password to remove lock:');
                if (pass === note.password) {
                    note.password = null;
                    delete unlockedNotes[note.id];
                    logActivity('general_notes', 'remove_lock', `Removed lock from note "${note.title_notes}"`);
                    saveAll();
                    renderGeneralNotes();
                } else if (pass !== null) {
                    alert('Incorrect password!');
                }
            });
        } else {
            document.getElementById('btn-lock-gen').addEventListener('click', () => {
                const pass = prompt('Set a custom password to lock this note:');
                if (pass) {
                    note.password = pass;
                    unlockedNotes[note.id] = true; // keep currently unlocked
                    logActivity('general_notes', 'lock', `Locked note "${note.title_notes}" with a password`);
                    saveAll();
                    renderGeneralNotes();
                }
            });
        }

        document.getElementById('btn-delete-gen').addEventListener('click', () => {
            if (confirm('Are you sure you want to delete this note?')) {
                deleteActiveGeneralNote();
            }
        });

        // Reconnect formatting handlers
        editorContainer.querySelectorAll('.btn-format').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const command = btn.getAttribute('data-command');
                document.execCommand(command, false, null);
                saveNoteEditorContent();
            });
        });
    }

    function saveNoteEditorContent() {
        const bodyContent = document.getElementById('note-body-content');
        if (bodyContent && activeGeneralNoteId) {
            const note = generalNotes.find(n => n.id === activeGeneralNoteId);
            if (note) {
                note.description = bodyContent.innerHTML;
                saveAll();

                // Update snippet in list
                const activeEl = document.querySelector('.note-list-item.active');
                if (activeEl) {
                    const previewText = note.description ? note.description.replace(/<[^>]*>/g, '').substring(0, 45) + '...' : 'Empty';
                    activeEl.querySelector('.note-item-snippet').innerHTML = `📝 ${previewText}`;
                }
            }
        }
    }

    function deleteActiveGeneralNote() {
        const note = generalNotes.find(n => n.id === activeGeneralNoteId);
        if (note) {
            note.deletedAt = new Date().toISOString();
            logActivity('general_notes', 'delete', `Soft deleted note "${note.title_notes}"`);
            activeGeneralNoteId = null;
            renderGeneralNotes();
        }
    }


    /* =========================================
       MODULE: SPACES SUB-PAGES
       ========================================= */

    function renderSpaceDetails(spaceId) {
        const space = spaces.find(s => s.id === spaceId);
        if (!space) return;

        // Render parent wrapper for spaces
        const container = document.getElementById('module-space_detail');
        container.innerHTML = `
            <div class="space-header">
                <div class="space-title-section">
                    <h1>📁 ${space.title_spaces}</h1>
                    <div class="space-tabs">
                        <button class="space-tab-btn ${currentSpaceTab === 'projects' ? 'active' : ''}" id="tab-btn-projects">📊 Projects</button>
                        <button class="space-tab-btn ${currentSpaceTab === 'reminders' ? 'active' : ''}" id="tab-btn-reminders">🔔 Reminder</button>
                        <button class="space-tab-btn ${currentSpaceTab === 'notes' ? 'active' : ''}" id="tab-btn-notes">📝 Notes</button>
                    </div>
                </div>
                <div>
                   <button class="btn-action danger" id="space-action-delete" style="font-size:0.85rem;">🗑️ Delete Space</button>
                </div>
            </div>
            <div class="space-tab-content-container">
                <div class="space-tab-content ${currentSpaceTab === 'projects' ? 'active' : ''}" id="space-content-projects"></div>
                <div class="space-tab-content ${currentSpaceTab === 'reminders' ? 'active' : ''}" id="space-content-reminders"></div>
                <div class="space-tab-content ${currentSpaceTab === 'notes' ? 'active' : ''}" id="space-content-notes"></div>
            </div>
        `;

        // Space action handlers
        document.getElementById('space-action-delete').addEventListener('click', () => {
            if (confirm(`Delete workspace "${space.title_spaces}"? This soft-deletes the space and all contained projects, reminders and notes.`)) {
                deleteSpace(spaceId);
            }
        });

        // Tabs switcher
        document.getElementById('tab-btn-projects').addEventListener('click', () => switchSpaceTab('projects'));
        document.getElementById('tab-btn-reminders').addEventListener('click', () => switchSpaceTab('reminders'));
        document.getElementById('tab-btn-notes').addEventListener('click', () => switchSpaceTab('notes'));

        // Load correct active tab
        renderSpaceTabContent();
    }

    function switchSpaceTab(tabName) {
        currentSpaceTab = tabName;
        document.querySelectorAll('.space-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.space-tab-content').forEach(c => c.classList.remove('active'));

        let btnId = '';
        if (tabName === 'projects') btnId = 'tab-btn-projects';
        if (tabName === 'reminders') btnId = 'tab-btn-reminders';
        if (tabName === 'notes') btnId = 'tab-btn-notes';

        document.getElementById(btnId).classList.add('active');
        document.getElementById(`space-content-${tabName}`).classList.add('active');

        renderSpaceTabContent();
    }

    function renderSpaceTabContent() {
        if (currentSpaceTab === 'projects') {
            renderProjectsTab();
        } else if (currentSpaceTab === 'reminders') {
            renderRemindersTab();
        } else if (currentSpaceTab === 'notes') {
            renderSpaceNotesTab();
        }
    }

    /* PROJECTS SUBTAB */
    function renderProjectsTab() {
        const tabDiv = document.getElementById('space-content-projects');
        const activeProjects = projects.filter(p => p.id_spaces === activeSpaceId && p.deletedAt === null);

        let tableRows = '';
        activeProjects.forEach(proj => {
            const statusObj = projectStatuses.find(s => s.id === proj.row_status) || { title_status: 'Unassigned', colour_status: '#6b7280' };

            // Build files render
            let fileListHTML = '<span style="color:var(--text-secondary); font-size:0.8rem;">No files</span>';
            if (proj.files && proj.files.length > 0) {
                fileListHTML = `<div class="file-list-tags">`;
                proj.files.forEach(file => {
                    fileListHTML += `
                        <div class="file-tag" title="${file.name}">
                            <span>📄</span>
                            <span class="file-tag-name">${file.name}</span>
                            <span style="font-size:0.7rem; opacity:0.7;">(${file.size})</span>
                        </div>
                    `;
                });
                fileListHTML += '</div>';
            }

            tableRows += `
                <tr>
                    <td style="font-weight: 600;">${proj.row_title}</td>
                    <td>
                        <span class="project-status-badge" style="background:${statusObj.colour_status}1a; color:${statusObj.colour_status}; border:1px solid ${statusObj.colour_status}3d;">
                            ${statusObj.title_status}
                        </span>
                    </td>
                    <td>
                        <span style="font-size:0.85rem;">
                            ${proj.start_at ? proj.start_at : ''}
                            ${proj.start_at && proj.end_at ? ' to ' : ''}
                            ${proj.end_at ? proj.end_at : (proj.start_at ? ' (Ongoing)' : '—')}
                        </span>
                    </td>
                    <td><div style="max-height:60px; overflow:hidden; text-overflow:ellipsis; max-width:250px; font-size:0.85rem; color:var(--text-secondary); white-space:pre-wrap;">${proj.description || ''}</div></td>
                    <td>${fileListHTML}</td>
                    <td>
                       <button class="btn-action" onclick="deleteProject('${proj.id}')" style="padding:4px 8px; font-size:0.75rem;">🗑️</button>
                    </td>
                </tr>
            `;
        });

        tabDiv.innerHTML = `
            <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                <div style="display:flex; gap:10px;">
                    <button class="btn-primary" id="btn-create-project">+ Create Project</button>
                    <button class="btn-secondary" id="btn-manage-statuses">⚙️ Customize Statuses</button>
                </div>
            </div>
            <div class="data-table-container">
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Status</th>
                            <th>Timeline</th>
                            <th>Description</th>
                            <th>Files</th>
                            <th style="width:50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="projects-table-body">
                        ${tableRows ? tableRows : '<tr><td colspan="6" style="text-align:center; color:var(--text-secondary);">No projects registered. Click "+ Create Project" to start.</td></tr>'}
                    </tbody>
                </table>
            </div>
        `;

        document.getElementById('btn-create-project').addEventListener('click', () => {
            openProjectCreateModal();
        });

        document.getElementById('btn-manage-statuses').addEventListener('click', () => {
            openStatusCustomModal();
        });
    }

    window.deleteProject = function (projId) {
        if (confirm('Delete this project?')) {
            const proj = projects.find(p => p.id === projId);
            if (proj) {
                proj.deletedAt = new Date().toISOString();
                logActivity('spaces_projects', 'delete_project', `Deleted project "${proj.row_title}"`);
                renderProjectsTab();
            }
        }
    };

    function openProjectCreateModal() {
        const modalContainer = document.getElementById('modal-generic-container');

        let statusOptions = '';
        projectStatuses.forEach(s => {
            statusOptions += `<option value="${s.id}">${s.title_status}</option>`;
        });

        modalContainer.innerHTML = `
            <div class="modal-overlay" id="modal-project-create" style="display:flex;">
                <div class="modal-card">
                    <div class="modal-header">
                        <div class="modal-title">New Project</div>
                        <button class="btn-close" onclick="closeModal('modal-project-create')">×</button>
                    </div>
                    <form id="form-create-project-sub">
                        <div class="form-field">
                            <label class="form-label">Project Name *</label>
                            <input class="form-input-text" type="text" id="proj-title-input" required>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Status</label>
                            <select class="form-input-text" id="proj-status-input" style="background:#0f172a; border: 1px solid var(--border-color); color:var(--text-primary);">
                                ${statusOptions}
                            </select>
                        </div>
                        <div style="display:flex; gap:12px;">
                            <div class="form-field" style="flex:1;">
                                <label class="form-label">Start Date (Optional)</label>
                                <input class="form-input-text" type="date" id="proj-start-input">
                            </div>
                            <div class="form-field" style="flex:1;">
                                <label class="form-label">End Date (Optional)</label>
                                <input class="form-input-text" type="date" id="proj-end-input">
                            </div>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Description (Long text)</label>
                            <textarea class="form-input-text" id="proj-description-input" rows="4" style="resize:vertical;"></textarea>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Upload File</label>
                            <input type="file" id="proj-file-input" style="color:var(--text-secondary); font-size:0.88rem;">
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn-secondary" onclick="closeModal('modal-project-create')">Cancel</button>
                            <button type="submit" class="btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        document.getElementById('form-create-project-sub').addEventListener('submit', (e) => {
            e.preventDefault();
            const title = document.getElementById('proj-title-input').value.trim();
            const status = document.getElementById('proj-status-input').value;
            const start = document.getElementById('proj-start-input').value || null;
            const end = document.getElementById('proj-end-input').value || null;
            const description = document.getElementById('proj-description-input').value;

            // Handle file upload preview
            const fileInput = document.getElementById('proj-file-input');
            const files = [];
            if (fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    const f = fileInput.files[i];
                    files.push({
                        name: f.name,
                        size: (f.size / (1024 * 1024)).toFixed(1) + ' MB'
                    });
                }
            }

            const newProj = {
                id: 'proj-' + Math.random().toString(36).substr(2, 9),
                id_spaces: activeSpaceId,
                row_title: title,
                row_status: status,
                start_at: start,
                end_at: end,
                description: description,
                files: files,
                deletedAt: null
            };

            projects.push(newProj);
            logActivity('spaces_projects', 'create_project', `Created project "${title}" in space`);
            closeModal('modal-project-create');
            renderProjectsTab();
        });
    }

    function openStatusCustomModal() {
        const modalContainer = document.getElementById('modal-generic-container');

        let statusListHTML = '';
        projectStatuses.forEach(s => {
            statusListHTML += `
                <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(0,0,0,0.1); padding:8px 12px; border-radius:6px; margin-bottom:6px; border:1px solid var(--border-color);">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="display:inline-block; width:12px; height:12px; border-radius:50%; background:${s.colour_status};"></span>
                        <span>${s.title_status}</span>
                    </div>
                    <button class="btn-close" onclick="deleteCustomStatus('${s.id}')" style="font-size:1rem; color:var(--accent-danger);">×</button>
                </div>
            `;
        });

        modalContainer.innerHTML = `
            <div class="modal-overlay" id="modal-statuses-manage" style="display:flex;">
                <div class="modal-card">
                    <div class="modal-header">
                        <div class="modal-title">Customize Statuses</div>
                        <button class="btn-close" onclick="closeModal('modal-statuses-manage')">×</button>
                    </div>
                    <div style="max-height:220px; overflow-y:auto; margin-bottom:12px;">
                        ${statusListHTML ? statusListHTML : '<div style="text-align:center; color:var(--text-secondary);">No custom status setup.</div>'}
                    </div>
                    <form id="form-create-status">
                        <div style="display:flex; gap:8px; align-items:flex-end;">
                            <div class="form-field" style="margin-bottom:0; flex-grow:1;">
                                <label class="form-label">Status Title</label>
                                <input class="form-input-text" type="text" id="status-title-input" required placeholder="e.g. In Testing">
                            </div>
                            <div class="form-field" style="margin-bottom:0; width:70px;">
                                <label class="form-label">Color</label>
                                <input type="color" id="status-color-input" value="#6366f1" style="width:100%; height:40px; padding:0; border:1px solid var(--border-color); border-radius:8px; background:none; cursor:pointer;">
                            </div>
                            <button type="submit" class="btn-primary" style="height:40px; padding:0 12px;">Add</button>
                        </div>
                    </form>
                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="closeModal('modal-statuses-manage')">Close</button>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('form-create-status').addEventListener('submit', (e) => {
            e.preventDefault();
            const title = document.getElementById('status-title-input').value.trim();
            const color = document.getElementById('status-color-input').value;

            if (!title) return;

            const newStatus = {
                id: 'status-' + Math.random().toString(36).substr(2, 9),
                title_status: title,
                colour_status: color
            };
            projectStatuses.push(newStatus);
            logActivity('spaces_projects', 'create_status', `Added custom status option "${title}"`);
            closeModal('modal-statuses-manage');
            openStatusCustomModal();
            renderProjectsTab();
        });
    }

    window.deleteCustomStatus = function (statusId) {
        if (projectStatuses.length <= 1) {
            alert('Must have at least one status option!');
            return;
        }

        projectStatuses = projectStatuses.filter(s => s.id !== statusId);
        logActivity('spaces_projects', 'delete_status', `Deleted custom status option`);
        closeModal('modal-statuses-manage');
        openStatusCustomModal();
        renderProjectsTab();
    };

    /* REMINDERS SUBTAB */
    function renderRemindersTab() {
        const tabDiv = document.getElementById('space-content-reminders');
        const activeReminders = reminders.filter(r => r.id_spaces === activeSpaceId && r.deletedAt === null);

        // Sort: not_ticked first, ticked last
        activeReminders.sort((a, b) => {
            if (a.row_status === b.row_status) return 0;
            return a.row_status === 'not_ticked' ? -1 : 1;
        });

        let listHTML = '';
        activeReminders.forEach(rem => {
            const isTicked = rem.row_status === 'ticked';
            listHTML += `
                <div class="reminder-item ${isTicked ? 'ticked' : ''}" data-id="${rem.id}">
                    <input type="checkbox" class="reminder-checkbox" ${isTicked ? 'checked' : ''} onchange="toggleReminderTick('${rem.id}', this.checked)">
                    <div class="reminder-text-content">
                        <div class="reminder-title">${rem.row_title}</div>
                        ${rem.row_description ? `<div class="reminder-desc">${rem.row_description}</div>` : ''}
                    </div>
                    <button class="btn-close" onclick="deleteReminder('${rem.id}')" style="font-size:0.9rem; margin-top:2px;">×</button>
                </div>
            `;
        });

        tabDiv.innerHTML = `
            <div class="reminders-container">
                <form id="form-reminder-add" class="reminder-quick-add">
                    <input class="form-input-text" type="text" id="reminder-quick-input" placeholder="Add a reminder... (Press Enter to save)" required>
                    <button type="submit" class="btn-primary" style="white-space:nowrap;">Add</button>
                </form>
                <div class="reminders-list-wrapper">
                    ${listHTML ? listHTML : '<div style="text-align:center; padding:20px; color:var(--text-secondary);">All done! Zero reminders.</div>'}
                </div>
            </div>
        `;

        document.getElementById('form-reminder-add').addEventListener('submit', (e) => {
            e.preventDefault();
            const textHTML = document.getElementById('reminder-quick-input').value.trim();
            if (!textHTML) return;

            const newRem = {
                id: 'rem-' + Math.random().toString(36).substr(2, 9),
                id_spaces: activeSpaceId,
                row_title: textHTML,
                row_description: '',
                row_status: 'not_ticked',
                deletedAt: null
            };

            reminders.push(newRem);
            logActivity('spaces_projects', 'create_reminder', `Added reminder: "${textHTML}"`);
            renderRemindersTab();
        });
    }

    window.toggleReminderTick = function (remId, checked) {
        const rem = reminders.find(r => r.id === remId);
        if (rem) {
            rem.row_status = checked ? 'ticked' : 'not_ticked';
            logActivity('spaces_projects', checked ? 'tick_reminder' : 'untick_reminder', `Toggled reminder status: "${rem.row_title}"`);
            renderRemindersTab();
        }
    };

    window.deleteReminder = function (remId) {
        const rem = reminders.find(r => r.id === remId);
        if (rem) {
            rem.deletedAt = new Date().toISOString();
            logActivity('spaces_projects', 'delete_reminder', `Deleted reminder: "${rem.row_title}"`);
            renderRemindersTab();
        }
    };

    /* SPACE NOTES SUBTAB */
    function renderSpaceNotesTab() {
        const tabDiv = document.getElementById('space-content-notes');
        const activeSubNotes = spaceNotes.filter(n => n.id_spaces === activeSpaceId && n.deletedAt === null);

        let listHTML = '';
        activeSubNotes.forEach(sn => {
            listHTML += `
                <div style="background:var(--bg-secondary); border:1px solid var(--border-color); border-radius:10px; padding:16px; margin-bottom:12px; position:relative;">
                    <div style="display:flex; justify-content:between; width:100%; border-bottom: 1px solid var(--border-color); padding-bottom:8px; margin-bottom:12px;">
                        <input type="text" value="${sn.title_notes}" onblur="renameSpaceNote('${sn.id}', this.value)" style="background:transparent; border:none; color:var(--text-primary); font-size:1.1rem; font-weight:600; outline:none; flex-grow:1;">
                        <button class="btn-action danger" onclick="deleteSpaceNote('${sn.id}')" style="padding:4px 8px; font-size:0.75rem;">Delete</button>
                    </div>
                    <div contenteditable="true" onblur="updateSpaceNoteBody('${sn.id}', this.innerHTML)" style="color:var(--text-primary); font-size:0.92rem; outline:none; line-height:1.5; min-height:80px; max-height:220px; overflow-y:auto;">
                        ${sn.description || 'Write notes here...'}
                    </div>
                </div>
            `;
        });

        tabDiv.innerHTML = `
            <div>
                <button class="btn-primary" id="btn-create-sp-note" style="margin-bottom:16px;">+ Create Space Note</button>
                <div id="space-notes-list-wrapper">
                    ${listHTML ? listHTML : '<div style="text-align:center; padding:30px; color:var(--text-secondary);">No notes in this space. Click "+ Create Space Note" to start.</div>'}
                </div>
            </div>
        `;

        document.getElementById('btn-create-sp-note').addEventListener('click', () => {
            const newSpNote = {
                id: 's-note-' + Math.random().toString(36).substr(2, 9),
                id_spaces: activeSpaceId,
                title_notes: 'New Space Note',
                description: 'Write somethings...',
                deletedAt: null
            };
            spaceNotes.push(newSpNote);
            logActivity('spaces_projects', 'create_note', `Created space note "${newSpNote.title_notes}"`);
            renderSpaceNotesTab();
        });
    }

    window.renameSpaceNote = function (snId, newTitle) {
        const sn = spaceNotes.find(n => n.id === snId);
        if (sn && newTitle.trim()) {
            sn.title_notes = newTitle.trim();
            logActivity('spaces_projects', 'rename_note', `Renamed space note to "${sn.title_notes}"`);
            saveAll();
        }
    };

    window.updateSpaceNoteBody = function (snId, innerHTML) {
        const sn = spaceNotes.find(n => n.id === snId);
        if (sn) {
            sn.description = innerHTML;
            saveAll();
        }
    };

    window.deleteSpaceNote = function (snId) {
        if (confirm('Delete this space note?')) {
            const sn = spaceNotes.find(n => n.id === snId);
            if (sn) {
                sn.deletedAt = new Date().toISOString();
                logActivity('spaces_projects', 'delete_note', `Deleted space note "${sn.title_notes}"`);
                renderSpaceNotesTab();
            }
        }
    };


    /* =========================================
       MODULE: ROLES MANAGEMENT
       ========================================= */

    function renderRoles() {
        const listDiv = document.getElementById('roles-table-body');
        listDiv.innerHTML = '';

        admins.forEach(admin => {
            const isDeactivated = admin.deactivated_at !== null;
            const statusHTML = isDeactivated
                ? `<span class="status-indicator deactivated">Deactivated</span>`
                : `<span class="status-indicator active">Active</span>`;

            const btnDeactivateText = isDeactivated ? 'Activate' : 'Deactivate';
            const btnDeactivateClass = isDeactivated ? '' : 'danger';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td style="padding:14px 20px; border-bottom:1px solid var(--border-color); font-weight:600;">${admin.name}</td>
                <td style="padding:14px 20px; border-bottom:1px solid var(--border-color); color:var(--text-secondary);">${admin.username}</td>
                <td style="padding:14px 20px; border-bottom:1px solid var(--border-color); color:var(--text-secondary);">${admin.email}</td>
                <td style="padding:14px 20px; border-bottom:1px solid var(--border-color); font-size:0.85rem;"><span style="background:rgba(99,102,241,0.12); color:var(--accent); font-weight:600; padding:2px 8px; border-radius:4px;">${admin.role}</span></td>
                <td style="padding:14px 20px; border-bottom:1px solid var(--border-color);">${statusHTML}</td>
                <td style="padding:14px 20px; border-bottom:1px solid var(--border-color); text-align:right;">
                    <div style="display:flex; justify-content:flex-end; gap:6px;">
                        <button class="btn-action" onclick="openEditUserModal('${admin.id}')" style="padding:4px 10px; font-size:0.82rem;">✏️ Edit</button>
                        <button class="btn-action ${btnDeactivateClass}" onclick="toggleUserDeactivate('${admin.id}')" style="padding:4px 10px; font-size:0.82rem;">
                            ${btnDeactivateText}
                        </button>
                    </div>
                </td>
            `;

            listDiv.appendChild(row);
        });
    }

    window.toggleUserDeactivate = function (adminId) {
        const admin = admins.find(a => a.id === adminId);
        if (!admin) return;

        // Prevent self-deactivation of logged-in simulator user
        if (admin.id === currentAdmin.id) {
            alert('Cannot deactivate your own active session!');
            return;
        }

        const isDeactivated = admin.deactivated_at !== null;
        if (isDeactivated) {
            admin.deactivated_at = null;
            logActivity('roles_permissions', 'activate', `Activated administration user "${admin.name}"`);
        } else {
            admin.deactivated_at = new Date().toISOString();
            logActivity('roles_permissions', 'deactivate', `Deactivated administration user "${admin.name}"`);
        }
        renderRoles();
    };

    window.openEditUserModal = function (adminId) {
        const admin = admins.find(a => a.id === adminId);
        if (!admin) return;

        const modalContainer = document.getElementById('modal-generic-container');
        modalContainer.innerHTML = `
            <div class="modal-overlay" id="modal-edit-user" style="display:flex;">
                <div class="modal-card">
                    <div class="modal-header">
                        <div class="modal-title">Edit User Details</div>
                        <button class="btn-close" onclick="closeModal('modal-edit-user')">×</button>
                    </div>
                    <form id="form-edit-user-sub">
                        <div class="form-field">
                            <label class="form-label">Full Name</label>
                            <input class="form-input-text" type="text" id="edit-user-name" value="${admin.name}" required>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Username</label>
                            <input class="form-input-text" type="text" id="edit-user-username" value="${admin.username}" required>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Email</label>
                            <input class="form-input-text" type="email" id="edit-user-email" value="${admin.email}" required>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Role</label>
                            <select class="form-input-text" id="edit-user-role" style="background:#0f172a; border: 1px solid var(--border-color); color:var(--text-primary);">
                                <option value="Super Admin" ${admin.role === 'Super Admin' ? 'selected' : ''}>Super Admin</option>
                                <option value="Admin" ${admin.role === 'Admin' ? 'selected' : ''}>Admin</option>
                            </select>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-user')">Cancel</button>
                            <button type="submit" class="btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        document.getElementById('form-edit-user-sub').addEventListener('submit', (e) => {
            e.preventDefault();
            admin.name = document.getElementById('edit-user-name').value.trim();
            admin.username = document.getElementById('edit-user-username').value.trim();
            admin.email = document.getElementById('edit-user-email').value.trim();
            admin.role = document.getElementById('edit-user-role').value;

            logActivity('roles_permissions', 'edit_user', `Edited account details of user "${admin.name}" (${admin.username})`);
            closeModal('modal-edit-user');
            renderRoles();
        });
    };


    /* =========================================
       MODULE: ACTIVITY LOGS
       ========================================= */

    function renderActivityLogs() {
        const listDiv = document.getElementById('activity-log-table-body');
        listDiv.innerHTML = '';

        activityLogs.forEach(log => {
            const date = new Date(log.timestamp);
            const timeString = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            let moduleBadge = '';
            if (log.module === 'general_notes') moduleBadge = '<span style="background:rgba(99,102,241,0.12); color:#818cf8; padding:2px 6px; border-radius:4px; font-size:0.75rem; font-weight:600;">General Notes</span>';
            else if (log.module === 'spaces') moduleBadge = '<span style="background:rgba(16,185,129,0.12); color:#4ade80; padding:2px 6px; border-radius:4px; font-size:0.75rem; font-weight:600;">Spaces</span>';
            else if (log.module === 'spaces_projects') moduleBadge = '<span style="background:rgba(6,182,212,0.12); color:#22d3ee; padding:2px 6px; border-radius:4px; font-size:0.75rem; font-weight:600;">Spaces > Sub</span>';
            else if (log.module === 'roles_permissions') moduleBadge = '<span style="background:rgba(245,158,11,0.12); color:#fbbf24; padding:2px 6px; border-radius:4px; font-size:0.75rem; font-weight:600;">Roles</span>';
            else if (log.module === 'auth') moduleBadge = '<span style="background:rgba(239,68,68,0.12); color:#fca5a5; padding:2px 6px; border-radius:4px; font-size:0.75rem; font-weight:600;">Security</span>';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td style="padding:12px 16px; border-bottom:1px solid var(--border-color); color:var(--text-secondary); width:150px;">${timeString}</td>
                <td style="padding:12px 16px; border-bottom:1px solid var(--border-color); font-weight:600; width:120px;">${log.admin_name}</td>
                <td style="padding:12px 16px; border-bottom:1px solid var(--border-color); width:130px;">${moduleBadge}</td>
                <td style="padding:12px 16px; border-bottom:1px solid var(--border-color); text-transform:uppercase; font-size:0.75rem; font-weight:700; color:var(--text-secondary); width:100px;">${log.action}</td>
                <td style="padding:12px 16px; border-bottom:1px solid var(--border-color); color:var(--text-primary);">${log.description}</td>
            `;

            listDiv.appendChild(row);
        });
    }

    // Clear Logs function
    document.getElementById('btn-clear-logs').addEventListener('click', () => {
        if (confirm('Clear all logs?')) {
            activityLogs = [];
            logActivity('auth', 'clear_logs', 'Cleared activity logs history dataset');
            renderActivityLogs();
        }
    });


    // Theme Switcher Logic
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');
    const themeToggleBtn = document.getElementById('btn-toggle-theme');

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        if (theme === 'light') {
            if (themeIcon) themeIcon.textContent = '☀️';
            if (themeText) themeText.textContent = 'Theme: Light';
        } else {
            if (themeIcon) themeIcon.textContent = '🌑';
            if (themeText) themeText.textContent = 'Theme: AMOLED';
        }
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = localStorage.getItem('everyday_theme') || 'amoled';
            const nextTheme = currentTheme === 'light' ? 'amoled' : 'light';
            localStorage.setItem('everyday_theme', nextTheme);
            applyTheme(nextTheme);
            logActivity('auth', 'theme_change', `Switched theme interface to ${nextTheme.toUpperCase()}`);
        });
    }

    // Load active theme
    const savedTheme = localStorage.getItem('everyday_theme') || 'amoled';
    applyTheme(savedTheme);

    // Initial setup on load
    renderSpacesNav();
    switchModule('general_notes');
});
