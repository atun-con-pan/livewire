<?php

namespace App\Models;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class File extends Model implements Auditable
{
    use AuditableTrait;
    
    protected $table = 'files';

    protected $fillable = [
        'folder_id',
        'name',
        'physical_name',
        'extension',
        'mime_type',
        'size',
    ];

    /**
     * Carpeta a la que pertenece el archivo.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    /**
     * URL pública directa del archivo.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url('files/' . $this->physical_name);
    }

    /**
     * Tamaño legible para humanos (ej. "2.45 MB", "120 KB").
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) $this->size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}
