<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Document extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'type',
        'file_name',
        'path',
    ];
}

// 'Contratos', 'Actas', 'Fianzas', 'Cartas', 'Oficios', 'Planos', 'Facturas', 'Recibos', 'Planillas', 'Cotizaciones', 'Documentos ofertas', 'Informes fotográficos', 'Impuestos', 'Otros'