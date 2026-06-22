<?php

namespace App\Models;

use App\Models\AffiliatePeriod;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Affiliate extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = ['name', 'no_affiliate'];

    public function periods()
    {
        return $this->hasMany(AffiliatePeriod::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'affiliate_periods')
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }

    public function hasConflictInRange($start, $end): bool
    {
        $end = $end ?: '9999-12-31';

        return $this->periods()
            ->whereDate('start_date', '<=', $end)
            ->where(function ($q) use ($start) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $start);
            })
            ->exists();
    }

    public function isCurrentlyInUse(): bool
    {
        return $this->periods()
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', now());
            })
            ->exists();
    }
}
