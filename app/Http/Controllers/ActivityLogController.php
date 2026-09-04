<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('admin')->latest('created_at')->paginate(10);
        return view('activityLog.index', compact('logs'));
    }
}
