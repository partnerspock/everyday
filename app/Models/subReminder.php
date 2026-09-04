<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubReminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mod_sub_reminder';

    protected $fillable = [
        'id_spaces',
        'row_title',
        'row_description',
        'row_status',
        'row_order',
    ];
}