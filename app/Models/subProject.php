<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubProject extends Model
{
    use SoftDeletes;

    protected $table = 'mod_sub_projects';

    protected $fillable = [
        'id_spaces',
        'pro_status',
        'pro_title',
        'pro_start_at',
        'pro_end_at',
        'pro_description',
    ];

    protected $casts = [
        'pro_status'   => ProjectStatus::class,
        'pro_start_at' => 'date',
        'pro_end_at'   => 'date',
    ];

    public function files()
    {
        return $this->hasMany(SubProjectFile::class, 'id_projects');
    }
}