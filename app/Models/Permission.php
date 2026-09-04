<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'admins_permissions';

    protected $fillable = [
        'permission_name',
        'permission_status',
        'module_name',
        'module_key',
        'deactivated_at',
        'is_default'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admins_role_has_permission', 'id_permission', 'id_role');
    }
}