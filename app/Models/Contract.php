<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Contract extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
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
}
