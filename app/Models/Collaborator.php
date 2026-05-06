<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Collaborator extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\CollaboratorFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'first_name',
        'middle_name',
        'first_surname',
        'second_last_name',
        'dpi',
        'birthdate',
        'marital_status',
        'residence',
        'phone',
        'email',
        'position',
        'start_date',
        'termination_date',
        'salary',
        'contract',
        'pattern',
        'bank_account',
        'bank',
        'bank_account_name',
        'no_igss'
    ];

    public function files()
    {
        return $this->hasMany(FilesCollaborator::class);
    }

}
