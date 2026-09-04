<?php

namespace App\Http\Controllers;

use App\Models\subReminder;
use App\Models\Space;
use Illuminate\Http\Request;

class subReminderController extends Controller
{
    public function index($space_id)
    {
        $space = Space::findOrFail($space_id);
        $subReminder = subReminder::where('id_spaces', $space_id)->get();

        return view('subReminder.index', compact('subReminder', 'space'));
    }

    public function create($space_id)
    {
        $space = Space::findOrFail($space_id);
        return view('subReminder.create', compact('space'));
    }

    public function store(Request $request, $space_id)
    {
        $validated = $request->validate([
            'row_title'       => 'required|string|max:255',
            'row_description' => 'required|string',
            'row_status'      => 'required|string|in:not_ticked,ticked',
        ]);

        $validated['id_spaces'] = $space_id;
        $reminder = subReminder::create($validated);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success'        => true,
                'reminder'       => $reminder,
                'formatted_date' => $reminder->created_at->format('d M Y, H:i')
            ]);
        }

        ActivityLog::record('Space Reminders', 'Create', 'Created Space Reminders ' . $reminder->row_title);
        return redirect()->route('subReminder.index', $space_id)
                         ->with('success', $validated['row_title'] . ' created successfully!');
    }

    public function edit($id)
    {
        $subReminder = subReminder::findOrFail($id);
        $space = Space::findOrFail($subReminder->id_spaces);
        return view('subReminder.edit', compact('subReminder', 'space'));
    }

    public function update(Request $request, $id)
    {
        $subReminder = subReminder::findOrFail($id);
        
        $validated = $request->validate([
            'row_title'       => 'required|string|max:255',
            'row_description' => 'required|string',
            'row_status'      => 'required|string|in:not_ticked,ticked',
        ]);

        $subReminder->update($validated);

        ActivityLog::record('Space Reminders', 'Update', 'Updated Space Reminders ' . $subReminder->row_title);
        return redirect()->route('subReminder.index', $subReminder->id_spaces)
                         ->with('success', $validated['row_title'] . ' updated successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
    $subReminder = subReminder::findOrFail($id);
    
    $validated = $request->validate([
        'row_status' => 'required|string|in:not_ticked,ticked',
    ]);

    $subReminder->update(['row_status' => $validated['row_status']]);
    return response()->json([
        'success' => true, 
        'status'  => $subReminder->row_status
    ]);
    }

    public function destroy($id)
    {
        $subReminder = subReminder::findOrFail($id);
        $id_spaces = $subReminder->id_spaces;
        $subReminder->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        ActivityLog::record('Space Reminders', 'Delete', 'Deleted Space Reminders ' . $subReminder->row_title);
        return redirect()->route('subReminder.index', $id_spaces)->with('success', $subReminder->row_title . ' deleted successfully');
    }
}