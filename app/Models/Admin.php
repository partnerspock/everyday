<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_confirmed',
        'last_login_at',
        'confirmed_at',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'last_login_at'     => 'datetime',
            'confirmed_at'      => 'datetime',
            'deactivated_at'    => 'datetime',
            'deleted_at'        => 'datetime',
        ];
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->is_confirmed === 'confirmed' && !is_null($this->confirmed_at);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'is_confirmed' => 'confirmed',
            'confirmed_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'admins_has_role',
            'id_user',
            'id_role'
        );
    }

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains(function ($role) use ($roleName) {
            return strtolower($role->role_name) === strtolower($roleName);
        });
    }
}