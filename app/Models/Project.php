<?php

namespace App\Models;

use App\Models\AffiliatePeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = ['nog', 'event', 'name', 'url', 'client', 'presentation_date', 'start_date', 'end_date', 'price', 'status'];

    protected $casts = [
        'presentation_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function files()
    {
        return $this->hasMany(FilesProject::class);
    }

    public function ofices()
    {
        return $this->hasMany(Ofices::class);
    }

    public function periods()
    {
        return $this->hasMany(AffiliatePeriod::class);
    }

    public function affiliates()
    {
        return $this->belongsToMany(Affiliate::class, 'affiliate_periods')
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }
}
