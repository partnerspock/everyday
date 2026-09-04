<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class subNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mod_sub_notes';

        protected $fillable = [
            'id_spaces',
            'title_notes',
            'description',
        ];

    public function space()
    {
        return $this->belongsTo(Space::class, 'id_spaces');
    }
}
