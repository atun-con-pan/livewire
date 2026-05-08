<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class FilesContract extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\FilesContractFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'contract_id',
        'file_name',
        'path',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
