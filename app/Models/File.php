<?php

namespace App\Models;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
