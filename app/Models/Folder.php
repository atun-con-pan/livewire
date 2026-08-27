<?php

namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Folder extends Model implements Auditable
{
    use AuditableTrait;
    
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

    /**
     * Obtiene la ruta jerárquica legible de la carpeta (ej. "Documentos / 2024 / Reportes").
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $current = $this->parent;

        while ($current) {
            array_unshift($path, $current->name);
            $current = $current->parent;
        }

        return implode(' / ', $path);
    }
}

