<?php

namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    protected $table = 'folders';

    protected $fillable = [
        'parent_id',
        'name',
    ];

    /**
     * Carpeta padre.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    /**
     * Carpetas hijas.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    /**
     * Archivos contenidos en la carpeta.
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'folder_id');
    }
}
