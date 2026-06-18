<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Affiliate;
use App\Models\Project;

class AffiliatePeriod extends Model implements Auditable
{
    use AuditableTrait;

    protected $fillable = ['affiliate_id', 'project_id', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getHasConflictAttribute()
    {
        return static::query()
            ->where('id', '!=', $this->id)
            ->where('affiliate_id', $this->affiliate_id)
            ->whereDate('start_date', '<=', $this->end_date ?? '9999-12-31')
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $this->start_date);
            })
            ->exists();
    }
}
