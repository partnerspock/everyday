<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GnoteController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\subNoteController;
use App\Http\Controllers\subProjectController;
use App\Http\Controllers\subReminderController;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Gnote;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Space;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register Admin
Route::get('/adminregister', [AdminRegisterController::class, 'create'])->name('adminregister');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth','signed'])->name('verification.verify');
Route::post('/adminregister', [AdminRegisterController::class, 'store'])->name('adminregister.store');

Route::view('/portfolio', 'temp-port')->name('portfolio');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $stats = [
            'admins'      => Admin::count(),
            'roles'       => Role::count(),
            'permissions' => Permission::count(),
            'spaces'      => Space::where('created_user', Auth::id())->count(),
            'gnotes'      => Gnote::where('created_user', Auth::id())->count(),
            'logs'        => ActivityLog::count(),
        ];

        $mySpaces    = Space::where('created_user', Auth::id())->withCount(['subNote', 'subProject', 'subReminder'])->latest()->get();
        $recentLogs  = ActivityLog::with('admin')->latest()->take(8)->get();
        $recentGnotes = Gnote::where('created_user', Auth::id())->latest()->take(5)->get();

        return view('home', compact('stats', 'mySpaces', 'recentLogs', 'recentGnotes'));
    })->name('home');

    // Admin
    Route::resource('admins', AdminController::class)->except(['show']);
    Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('/admins/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');

    // Roles
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
   
    // Permission
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Activity Log
    Route::resource('activityLog', ActivityLogController::class)->except(['show']);

    // General Notes
    Route::resource('gnotes', Gnotecontroller::class)->except(['show']);
    Route::post('/gnotes/{id}/set-password', [GnoteController::class, 'setPassword'])->name('gnotes.setPassword');
    Route::post('/gnotes/{id}/remove-password', [GnoteController::class, 'removePassword'])->name('gnotes.removePassword');
    Route::get('/gnotes/{id}/unlock', [GnoteController::class, 'showUnlock'])->name('gnotes.showUnlock');
    Route::post('/gnotes/{id}/unlock', [GnoteController::class, 'unlock'])->name('gnotes.unlock');
    Route::post('/gnotes/{id}/lock', [GnoteController::class, 'lock'])->name('gnotes.lock');
    Route::post('/gnotes/lock-all', [GnoteController::class, 'lockAll'])->name('gnotes.lockAll');

    // Spaces
    Route::post('/spaces/store', [SpaceController::class, 'store'])->name('spaces.store');
    Route::get('/spaces/{id}', [SpaceController::class, 'index'])->name('spaces.index');

    // Sub Notes
    Route::post('/subNote/store/{space_id}', [subNoteController::class, 'store'])->name('subNote.store');
    Route::get('/subNote/{id}/create', [subNoteController::class, 'create'])->name('subNote.create');
    Route::get('/subNote/{id}', [subNoteController::class, 'index'])->name('subNote.index');
    Route::get('/subNote/{id}/edit', [subNoteController::class, 'edit'])->name('subNote.edit');
    Route::put('/subNote/{id}/update', [subNoteController::class, 'update'])->name('subNote.update');
    Route::delete('/subNote/{id}', [subNoteController::class, 'destroy'])->name('subNote.destroy');

    // Sub Projects
    Route::get('/subProject/{id}', [SubProjectController::class, 'index'])->name('subProject.index');
    Route::post('/subProject/store/{space_id}', [SubProjectController::class, 'store'])->name('subProject.store');
    Route::patch('/subProject/{id}/update-status', [SubProjectController::class, 'updateStatus'])->name('subProject.updateStatus');
    Route::patch('/subProject/{id}/update-field', [SubProjectController::class, 'updateField'])->name('subProject.updateField');
    Route::put('/subProject/{id}/update', [SubProjectController::class, 'update'])->name('subProject.update');
    Route::post('/subProject/{id}/upload', [SubProjectController::class, 'uploadAttachment'])->name('subProject.upload');
    Route::get('/subProject/file/{id}/download', [SubProjectController::class, 'downloadAttachment'])->name('subProject.file.download');
    Route::delete('/subProject/file/{id}', [SubProjectController::class, 'deleteAttachment'])->name('subProject.file.delete');
    Route::delete('/subProject/{id}', [SubProjectController::class, 'destroy'])->name('subProject.destroy');

    // Sub Reminders
    Route::post('/subReminder/store/{space_id}', [subReminderController::class, 'store'])->name('subReminder.store');
    Route::get('/subReminder/{id}/create', [subReminderController::class, 'create'])->name('subReminder.create');
    Route::get('/subReminder/{id}', [subReminderController::class, 'index'])->name('subReminder.index');
    Route::get('/subReminder/{id}/edit', [subReminderController::class, 'edit'])->name('subReminder.edit');
    Route::put('/subReminder/{id}/update', [subReminderController::class, 'update'])->name('subReminder.update');
    Route::post('/subReminder/{id}/toggle-status', [subReminderController::class, 'toggleStatus'])->name('subReminder.toggleStatus');
    Route::delete('/subReminder/{id}', [subReminderController::class, 'destroy'])->name('subReminder.destroy');

});