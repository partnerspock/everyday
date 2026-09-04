<?php

namespace App\Http\Controllers;

use App\Models\Gnote;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GnoteController extends Controller
{
    public function index()
    {
        $adminId = Auth::id();
        $notes = Gnote::where('created_user', $adminId)
            ->latest()
            ->paginate(10);

        return view('gnotes.index', compact('notes'));
    }

    public function create()
    {
        return view('gnotes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_notes' => 'required|string|max:255',
            'description' => 'required|string',
            'password'  => 'nullable|string|max:255',
        ]);

        $validated['created_user'] = Auth::id();

        Gnote::create($validated);

        ActivityLog::record('General Notes', 'Create', 'Created General Notes ' . $validated['title_notes']);
        return redirect()->route('gnotes.index')->with('success', $validated['title_notes'].' Notes created!');
    }

    public function edit($id)
    {
        $note = Gnote::where('created_user', Auth::id())->findOrFail($id);

        if ($note->password && !session()->has("note_unlocked_{$note->id}")) {
            return redirect()->route('gnotes.showUnlock', $note->id);
        }

        return view('gnotes.edit', compact('note'));
    }

    public function update(Request $request, $id)
    {
        $note = Gnote::where('created_user', Auth::id())->findOrFail($id);

        if ($note->password && !session()->has("note_unlocked_{$note->id}")) {
            return abort(403, 'Unauthorized action. Note is locked.');
        }

        $validated = $request->validate([
            'title_notes' => 'required|string|max:255',
            'description' => 'required|string',
            'password'  => 'nullable|string|max:255',
        ]);

        $note->update($validated);
        ActivityLog::record('General Notes', 'Update', 'Updated General Notes' . $note->title_notes);
        return redirect()->route('gnotes.index')->with('success', $validated['title_notes'].' updated successfully!');
    }

    public function setPassword(Request $request, $id)
    {
        $note = Gnote::where('created_user', Auth::id())->findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:4|max:255',
        ]);

        $note->update(['password' => bcrypt($request->password)]);
        session()->put("note_unlocked_{$note->id}", true);

        ActivityLog::record('General Notes', 'Set Password', 'Set Password General Notes' . $note->title_notes);
        return back()->with('success', 'Note locked successfully.');
    }

    public function removePassword(Request $request, $id)
    {
        $note = Gnote::where('created_user', Auth::id())->findOrFail($id);

        $request->validate([
            'current_password' => 'required|string',
        ]);

        if (!\Hash::check($request->current_password, $note->password)) {
            return back()->withErrors(['current_password' => 'Password is incorrect.']);
        }

        $note->update(['password' => null]);
        session()->forget("note_unlocked_{$note->id}");

        ActivityLog::record('General Notes', 'Remove Password', 'Remove Password General Notes' . $note->title_notes);
        return back()->with('success', 'Password removed.');
    }

    public function showUnlock($id)
    {
        $note = Gnote::where('created_user', Auth::id())->findOrFail($id);

        if (!$note->password || session()->has("note_unlocked_{$note->id}")) {
            return redirect()->route('gnotes.edit', $note->id);
        }

        return view('gnotes.unlock', compact('note'));
    }

    public function unlock(Request $request, $id)
    {
        $note = Gnote::where('created_user', Auth::id())->findOrFail($id);

        $request->validate([
            'password' => 'required|string',
        ]);

        if (\Hash::check($request->password, $note->password)) {
            session()->put("note_unlocked_{$note->id}", true);

            ActivityLog::record('General Notes', 'Unlock', 'Unlock General Notes' . $note->title_notes);
            return redirect()->route('gnotes.edit', $note->id);
        }

        return back()->withErrors(['password' => 'Password is incorrect.']);
    }

    public function lock($id)
    {
        session()->forget("note_unlocked_{$id}");

        ActivityLog::record('General Notes', 'Lock', 'Lock General Notes' . $note->title_notes);
        return back()->with('success', 'Note locked successfully.');
    }

    public function lockAll()
    {
        $allSessions = session()->all();

        foreach ($allSessions as $key => $value) {
            if (str_starts_with ($key, 'note_unlocked_')) {
                session()->forget($key);
            }
        }

        ActivityLog::record('General Notes', 'Lock All', 'Lock All General Notes');
        return back()->with('success', 'All notes locked successfully.');
    }
}