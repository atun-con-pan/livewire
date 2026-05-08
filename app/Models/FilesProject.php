<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class FilesProject extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\FilesProjectFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'file_name',
        'path',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
