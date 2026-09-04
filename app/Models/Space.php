<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Space extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mod_spaces';

    protected $fillable = [
        'created_user',
        'title_spaces',
    ];

    // Tanpa 's' di akhir method
    public function subNote()
    {
        return $this->hasMany(SubNote::class, 'id_spaces', 'id');
    }

    public function subProject()
    {
        return $this->hasMany(SubProject::class, 'id_spaces', 'id');
    }

    public function subReminder()
    {
        return $this->hasMany(SubReminder::class, 'id_spaces', 'id');
    }
}