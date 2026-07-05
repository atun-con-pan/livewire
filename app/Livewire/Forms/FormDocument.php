<?php

namespace App\Livewire\Forms;

use App\Models\Document;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Form;

class FormDocument extends Form
{
    public ?Document $document = null;
    public $type = '';
    public $file;
    public $file_name;

    // Lista blanca de tipos permitidos
    private const ALLOWED_TYPES = ['Contratos', 'Actas', 'Fianzas', 'Cartas', 'Oficios', 'Planos', 'Facturas', 'Recibos', 'Planillas', 'Cotizaciones', 'Documentos ofertas', 'Informes fotográficos', 'Impuestos', 'Otros'];

    // Laravel valida archivos en kilobytes. 512 * 1024 = 512 MB.
    private const MAX_FILE_SIZE_KB = 512 * 1024;

    public function store()
    {
        $this->validate([
            'type' => ['required', 'string', 'max:100', Rule::in(self::ALLOWED_TYPES)],
            'file' => 'required|array',
            'file.*' => 'file|max:' . self::MAX_FILE_SIZE_KB,
        ]);

        $files = collect($this->file)->map(function ($file) {
            $file_name = $file->getClientOriginalName();
            $directory = "documents/{$this->type}";

            return [
                'file' => $file,
                'file_name' => $file_name,
                'directory' => $directory,
                'file_path' => "{$directory}/{$file_name}",
            ];
        });

        $uniqueFiles = $files->unique('file_path')->values();

        $existingPaths = Document::whereIn('file_path', $uniqueFiles->pluck('file_path'))
            ->pluck('file_path');

        $filesToStore = $uniqueFiles
            ->reject(fn ($uploadedFile) => $existingPaths->contains($uploadedFile['file_path']))
            ->values();

        if ($filesToStore->isEmpty()) {
            $this->addError('file', 'Todos los archivos seleccionados ya existen o están repetidos.');
            return;
        }

        foreach ($filesToStore as $uploadedFile) {
            $uploadedFile['file']->storeAs($uploadedFile['directory'], $uploadedFile['file_name'], 'public');

            // Guardar en BD
            Document::create([
                'type' => $this->type,
                'file_name' => $uploadedFile['file_name'],
                'file_path' => $uploadedFile['file_path'],
            ]);
        }

        $skippedFiles = $files->count() - $filesToStore->count();
        $message = 'Registro creado correctamente';

        if ($skippedFiles > 0) {
            $message .= " ({$skippedFiles} archivo" . ($skippedFiles === 1 ? '' : 's') . ' omitido' . ($skippedFiles === 1 ? '' : 's') . ' por duplicado)';
        }

        Flux::toast(variant: 'success', text: $message);

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
            'type' => ['required', 'string', 'max:100', Rule::in(self::ALLOWED_TYPES)],
            'file' => 'nullable|file|max:' . self::MAX_FILE_SIZE_KB,
        ]);

        // Registro actual
        $document = $this->document;

        if (! $document) {
            $this->addError('file', 'No se encontró el documento que deseas editar.');
            return;
        }

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

            // Guardar nuevo archivo
            $this->file->storeAs($directory, $file_name, 'public');

            // Actualizar BD
            $document->update([
                'type' => $this->type,
                'file_name' => $file_name,
                'file_path' => $new_path,
            ]);

            // Borrar archivo anterior solo después de guardar el nuevo y actualizar la BD.
            if ($old_path !== $new_path && Storage::disk('public')->exists($old_path)) {
                Storage::disk('public')->delete($old_path);
            }
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

            if ($old_path !== $new_path) {
                if (! Storage::disk('public')->exists($old_path)) {
                    $this->addError('file', 'No se encontró el archivo actual para moverlo.');
                    return;
                }

                if (! Storage::disk('public')->move($old_path, $new_path)) {
                    $this->addError('file', 'No se pudo mover el archivo al nuevo tipo.');
                    return;
                }
            }

            // Actualizar BD
            $document->update([
                'type' => $this->type,
                'file_path' => $new_path,
            ]);
        }

        Flux::toast(variant: 'success', text: 'Registro editado correctamente');

        $this->reset(['file']);
    }
}
