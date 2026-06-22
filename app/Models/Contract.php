<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Contract extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'project_id',
        'no_contract',
        'contract_registration_date',
        'contract_subscription_date',
        'start_date_activities',
        'final_date_activities',
        'nog_contract',
        'contract_name',
        'execution_address',
        'number_workers',
        'salary_amount',
        'status',
        'filial',
        'person_charge',
    ];

    public function files()
    {
        return $this->hasMany(FilesContract::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
