<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Project extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'nog',
        'event',
        'name',
        'url',
        'client',
        'presentation_date',
        'start_date',
        'end_date',
        'price',
        'status'
    ];

    public function files()
    {
        return $this->hasMany(FilesProject::class);
    }
}