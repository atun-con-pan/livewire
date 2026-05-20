<?php

namespace App\Livewire\Forms;

use App\Models\Document;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Form;

class FormDocument extends Form
{
    public ?Document $document;
    public $type = '';
    public $file;
    public $file_name;

    // Lista blanca de tipos permitidos
    private const ALLOWED_TYPES = ['Contratos', 'Actas', 'Fianzas', 'Cartas', 'Oficios', 'Planos', 'Facturas', 'Recibos', 'Planillas', 'Cotizaciones', 'Documentos ofertas', 'Informes fotográficos', 'Impuestos', 'Otros'];

    // Tamaño máximo en bytes
    private const MAX_FILE_SIZE = 512 * (1024 );

    public function store()
    {
        $this->validate([
            'type' => 'required|string|max:100',
            'file' => 'required|array',
            'file.*' => 'file|max:' . (self::MAX_FILE_SIZE),
        ]);

        if (!in_array($this->type, self::ALLOWED_TYPES)) {
            $this->addError('type', 'Tipo de documento no válido.');
            return;
        }

        foreach ($this->file as $f) {
            $file_name = $f->getClientOriginalName();
            $directory = "documents/{$this->type}";
            $file_path = "{$directory}/{$file_name}";

            if (Document::where('file_path', $file_path)->exists()) {
                $this->addError('file', "El archivo {$file_name} ya existe.");
                continue;
            }

            $f->storeAs($directory, $file_name, 'public');

            // Guardar en BD
            Document::create([
                'type' => $this->type,
                'file_name' => $file_name,
                'file_path' => $file_path,
            ]);
        }

        Flux::toast(variant: 'success', heading: 'Registro Creado.', text: 'Los archivos se han creado exitosamente.', duration: 3000);

        $this->reset(['file']);
    }

    public function setDocument(Document $document)
    {
        $this->document = $document;
        $this->type = $document->type;
        $this->file_name = $document->file_name;
        $this->file = null;
    }

    public function update()
    {
        $this->validate([
            'type' => 'required|string|max:100',
            'file' => 'nullable|file|max:' . (self::MAX_FILE_SIZE),
        ]);

        if (!in_array($this->type, self::ALLOWED_TYPES)) {
            $this->addError('type', 'Tipo de documento no válido.');
            return;
        }

        // Registro actual
        $document = $this->document;

        $old_path = $document->file_path;

        /*
    |--------------------------------------------------------------------------
    | SI SE SUBE NUEVO ARCHIVO
    |--------------------------------------------------------------------------
    */

        if ($this->file) {
            $file_name = $this->file->getClientOriginalName();
            $directory = "documents/{$this->type}";
            $new_path = "{$directory}/{$file_name}";

            // Verificar duplicados
            if (Document::where('file_path', $new_path)->where('id', '!=', $document->id)->exists()) {
                $this->addError('file', "El archivo {$file_name} ya existe.");
                return;
            }

            // Borrar archivo anterior
            if (Storage::disk('public')->exists($old_path)) {
                Storage::disk('public')->delete($old_path);
            }

            // Guardar nuevo archivo
            $this->file->storeAs($directory, $file_name, 'public');

            // Actualizar BD
            $document->update([
                'type' => $this->type,
                'file_name' => $file_name,
                'file_path' => $new_path,
            ]);
        }
        /*
    |--------------------------------------------------------------------------
    | SOLO CAMBIÓ EL TIPO
    |--------------------------------------------------------------------------
    */ else {
            // Nombre actual
            $file_name = $document->file_name;
            $new_directory = "documents/{$this->type}";
            $new_path = "{$new_directory}/{$file_name}";

            // Verificar duplicados
            if (Document::where('file_path', $new_path)->where('id', '!=', $document->id)->exists()) {
                $this->addError('type', 'Ya existe un archivo con ese nombre en el nuevo tipo.');
                return;
            }

            // Mover archivo
            Storage::disk('public')->move($old_path, $new_path);

            // Actualizar BD
            $document->update([
                'type' => $this->type,
                'file_path' => $new_path,
            ]);
        }

        Flux::toast(variant: 'success', heading: 'Registro Actualizado.', text: 'El registro se ha actualizado exitosamente.', duration: 3000);

        $this->reset(['file']);
    }
}