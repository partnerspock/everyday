<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\SubProject;
use App\Models\SubProjectFile;
use App\Models\ActivityLog;
use App\Enums\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Storage;

class SubProjectController extends Controller
{
    public function index($spaceId)
    {
        $space = Space::findOrFail($spaceId);

        $subProject = SubProject::where('id_spaces', $spaceId)
            ->with('files')
            ->latest()
            ->get();

        $subProject->each(function ($project) {
            $project->files_for_modal = $project->files->map(function ($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->original_filename,
                    'url' => $f->url,
                    'size' => $f->formatted_size,
                ];
            })->values();
        });

        return view('subProject.index', compact('space', 'subProject'));
    }

    public function store(Request $request, $spaceId)
    {
        $space = Space::findOrFail($spaceId);

        $validated = $request->validate([
            'pro_title'       => 'required|string|max:255',
            'pro_description' => 'nullable|string',
            'pro_start_at'    => 'nullable|date',
            'pro_end_at'      => 'nullable|date|after_or_equal:pro_start_at',
            'pro_status'      => ['nullable', new Enum(ProjectStatus::class)],
        ]);

        // Default status jika tidak diisi
        $validated['pro_status'] = $request->pro_status ?? ProjectStatus::NOT_STARTED;

        // Magic SA Logic: Kalau saat create statusnya langsung COMPLETED tapi end_date kosong
        if ($validated['pro_status'] === ProjectStatus::COMPLETED->value && empty($validated['pro_end_at'])) {
            $validated['pro_end_at'] = now()->format('Y-m-d');
        }

        $space->subProject()->create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Project created successfully!'
            ]);
        }

        ActivityLog::record('Space Projects', 'Create', 'Created Space Projects ' . $validated['pro_title']);
        return redirect()->back()->with('success', 'Project created successfully!');
    }

    public function update(Request $request, $id)
    {
        $project = SubProject::findOrFail($id);

        $validated = $request->validate([
            'pro_title'       => 'required|string|max:255',
            'pro_description' => 'nullable|string',
            'pro_start_at'    => 'nullable|date',
            'pro_end_at'      => 'nullable|date|after_or_equal:pro_start_at',
            'pro_status'      => ['required', new Enum(ProjectStatus::class)],
        ]);

        $project->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully!'
            ]);
        }

        ActivityLog::record('Space Projects', 'Update', 'Updated Space Projects ' . $validated['pro_title']);
        return redirect()->back()->with('success', 'Project updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $project = SubProject::findOrFail($id);
        $project->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully!'
            ]);
        }

        ActivityLog::record('Space Projects', 'Delete', 'Deleted Space Projects ' . $project->pro_title);
        return redirect()->back()->with('success', 'Project deleted successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $project = SubProject::findOrFail($id);

        $validated = $request->validate([
            'pro_status' => ['required', new Enum(ProjectStatus::class)],
        ]);

        $project->update([
            'pro_status' => $validated['pro_status']
        ]);

        ActivityLog::record('Space Projects', 'Update', 'Updated status Space Projects ' . $project->pro_title);
        return response()->json([
            'success' => true,
            'status'  => $project->pro_status->value,
            'color'   => $project->pro_status->color()
        ]);
    }

    public function updateField(Request $request, $id)
    {
        $project = SubProject::findOrFail($id);

        $validated = $request->validate([
            'pro_status'   => ['nullable', new Enum(ProjectStatus::class)],
            'pro_start_at' => 'nullable|date',
            'pro_end_at'   => 'nullable|date',
        ]);

        $dataToUpdate = array_filter($validated, fn($value) => !is_null($value));

        if (isset($validated['pro_status'])) {
            if ($validated['pro_status'] === ProjectStatus::COMPLETED->value && !$project->pro_end_at) {
                $dataToUpdate['pro_end_at'] = now()->format('Y-m-d');
            }
        }

        $project->update($dataToUpdate);

        $project->refresh();

        ActivityLog::record('Space Projects', 'Update', 'Updated status Space Projects ' . $project->pro_title);
        return response()->json([
            'success'            => true,
            'message'            => 'Project updated successfully!',
            'status'             => $project->pro_status?->value,
            'status_color'       => $project->pro_status?->color(),
            'pro_start_at'       => $project->pro_start_at?->format('Y-m-d'),
            'start_at_formatted' => $project->pro_start_at?->format('M d, Y') ?? '-',
            'pro_end_at'         => $project->pro_end_at?->format('Y-m-d'),
            'end_at_formatted'   => $project->pro_end_at?->format('M d, Y') ?? '-',
        ]);
    }

    public function uploadAttachment(Request $request, $id)
    {
        try {
            $subProject = SubProject::findOrFail($id);

            // Validasi file (Max 10MB per file)
            $request->validate([
                'attachments'   => 'required|array',
                'attachments.*' => 'file|max:10240',
            ]);

            $savedFiles = [];

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    // 1. Simpan fisik file
                    $path = $file->store('attachments/projects', 'public');

                    // 2. Insert ke DB
                    $projectFile = SubProjectFile::create([
                        'id_projects'       => $subProject->id,
                        'original_filename' => $file->getClientOriginalName(),
                        'file_path'         => $path,
                        'mime_type'         => $file->getClientMimeType(),
                        'size'              => $file->getSize(),
                    ]);

                    $savedFiles[] = [
                        'id'   => $projectFile->id,
                        'name' => $projectFile->original_filename,
                        'url'  => Storage::url($path),
                    ];
                }

                try {
                    ActivityLog::record('Space Projects', 'Upload File', 'Uploaded files for ' . $subProject->pro_title);
                } catch (\Throwable $e) {}

                return response()->json([
                    'success' => true,
                    'message' => 'Files uploaded successfully!',
                    'files'   => $savedFiles
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'No files attached.'], 400);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Project ID not found.'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->validator->errors()->first()], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadAttachment($id)
    {
        $file = SubProjectFile::findOrFail($id);
        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download(
            $file->file_path,
            $file->original_filename
        );
    }

    public function deleteAttachment($id)
    {
        try {
            $file = SubProjectFile::findOrFail($id);

            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }
}