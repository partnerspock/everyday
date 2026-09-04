<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;

class Gnote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mod_general_notes';

    protected $fillable = [
        'title_notes',
        'description',
        'password',
        'created_user',
        'created_at',
        'updated_at',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_user');
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Hash::make($value) : null,
        );
    }
}