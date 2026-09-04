<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\ActivityLog;
use App\Enums\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SpaceController extends Controller
{
    public function index($id)
    {
        $space = Space::withCount(['subNote', 'subProject', 'subReminder'])->findOrFail($id);

        return view('spaces.index', compact('space'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_spaces' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat Space Utama
            $space = Space::create([
                'created_user' => Auth::id(),
                'title_spaces' => $request->title_spaces,
            ]);

            // 2. Buat Template bawaan
            $space->subNote()->create([
                'title_notes' => 'Welcome to ' . $space->title_spaces,
                'description' => 'This is general notes for this space',
            ]);

            $space->subProject()->create([
                'pro_title'       => 'First Project',
                'pro_description' => 'This is general projects for space ' . $space->title_spaces,
                'pro_status'      => ProjectStatus::NOT_STARTED,
            ]);

            $space->subReminder()->create([
                'row_title'       => 'Complete setup for ' . $space->title_spaces,
                'row_description' => 'Complete setup for ' . $space->title_spaces,
                'row_status'      => 'not_ticked',
                'row_order'       => 1,
            ]);

            ActivityLog::record('Spaces', 'Create', 'Created space ' . $space->title_spaces);
        });

        return redirect()->back()->with('success', 'Space and default templates created successfully!');
    }

    public function destroy($id)
    {
        $space = Space::findOrFail($id);
        $spaceName = $space->title_spaces; // Fix typo variabel

        $space->delete();

        ActivityLog::record('Spaces', 'Delete', 'Deleted space ' . $spaceName);

        return redirect()->back()->with('success', 'Space deleted successfully!');
    }
}