<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'admins_roles';

    protected $fillable = ['role_name', 'is_default'];

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'admins_role_has_permission',
            'id_role',
            'id_permission'
        );
    }

    public function admins()
    {
        return $this->belongsToMany(
            Admin::class,
            'admins_has_role',
            'id_role',
            'id_user'
        );
    }
}