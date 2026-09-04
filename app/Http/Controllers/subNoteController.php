<?php

namespace App\Http\Controllers;

use App\Models\subNote;
use App\Models\Space;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class subNoteController extends Controller
{
    public function index($space_id)
    {
        $space = Space::findOrFail($space_id);
        $subNote = subNote::where('id_spaces', $space_id)->get();

        return view('subNote.index', compact('subNote', 'space'));
    }

    public function create($space_id)
    {
        $space = Space::findOrFail($space_id);
        return view('subNote.create', compact('space'));
    }

    public function store(Request $request, $space_id)
    {
        $validated = $request->validate([
            'title_notes' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $validated['id_spaces'] = $space_id;
        subNote::create($validated);

        ActivityLog::record('Space Notes', 'Create', 'Created Space Notes ' . $validated['title_notes']);
        return redirect()->route('subNote.index', $space_id)
                         ->with('success', $validated['title_notes'] . ' created successfully!');
    }

    public function edit($id)
    {
        $subNote = subNote::findOrFail($id);
        $space = Space::findOrFail($subNote->id_spaces);
        return view('subNote.edit', compact('subNote', 'space'));
    }

    public function update(Request $request, $id)
    {
        $subNote = subNote::findOrFail($id);
        $validated = $request->validate([
            'title_notes' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
         $subNote->update($validated);

        ActivityLog::record('Space Notes', 'Update', 'Updated Space Notes ' . $subNote->title_notes);
        return redirect()->route('subNote.index', $subNote->id_spaces)
                         ->with('success', $validated['title_notes'] . ' updated successfully!');
    }

    public function destroy($id)
    {
        $subNote = subNote::findOrFail($id);
        $id_spaces = $subNote->id_spaces; // Simpan dulu sebelum di-delete
        $subNote->delete();

        ActivityLog::record('Space Notes', 'Delete', 'Deleted Space Notes ' . $subNote->title_notes);
        return redirect()->route('subNote.index', $id_spaces)
                         ->with('success', $subNote->title_notes . ' deleted successfully!');
    }
}