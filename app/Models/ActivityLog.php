<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'module',
        'action',
        'description',
        'created_at',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public static function record($module, $action, $description = null)
    {
        if (Auth::check()) {
            static::create([
                'admin_id' => Auth::id(),
                'module' => $module,
                'action' => $action,
                'description' => $description,
                'created_at' => now(),
            ]);
        }
    }
}