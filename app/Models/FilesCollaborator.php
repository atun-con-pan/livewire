<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class FilesCollaborator extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\FilesCollaboratorFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'file_name',
        'path',
        'collaborator_id'
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
