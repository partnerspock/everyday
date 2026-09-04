<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubProjectFile extends Model
{
    protected $table = 'mod_sub_projects_files';

    protected $fillable = [
        'id_projects',
        'original_filename',
        'file_path',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(SubProject::class, 'id_projects');
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getUrlAttribute()
    {
        return \Illuminate\Support\Facades\Storage::url($this->file_path);
    }
}