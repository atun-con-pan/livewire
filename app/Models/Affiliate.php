<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Affiliate extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\AffiliateFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'name',
        'dpi',
        'no_affiliate',
        'project',
        'nog',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
