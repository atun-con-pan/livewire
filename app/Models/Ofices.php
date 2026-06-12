<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ofices extends Model
{
    /** @use HasFactory<\Database\Factories\OficesFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'date',
        'ofice',
        'option',
        'description',
        'notes',
        'file_name',
        'file_path',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
