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
    private const ALLOWED_TYPES = [
        'Contratos',
        'Actas',
        'Fianzas',
        'Cartas',
        'Oficios',
        'Planos',
        'Facturas',
        'Recibos',
        'Planillas',
        'Cotizaciones',
        'Documentos ofertas',
        'Informes fotográficos',
        'Impuestos',
        'Otros'
    ];

    // Tipos MIME permitidos
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/png',
        'image/gif',
        'text/plain',
    ];

    // Extensiones permitidas
    private const ALLOWED_EXTENSIONS = [
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'jpg',
        'jpeg',
        'png',
        'gif',
        'txt'
    ];

    // Tamaño máximo en bytes
    private const MAX_FILE_SIZE = 536870912;

    /**
     * Sanitizar nombre de archivo
     */
    private function sanitizeFileName(string $fileName): string
    {
        $pathInfo = pathinfo($fileName);

        $name = $pathInfo['filename'] ?? 'file';
        $extension = $pathInfo['extension'] ?? '';

        $cleanName = preg_replace('/[^a-zA-Z0-9_-]/u', '', $name);
        $cleanName = preg_replace('/\.\./', '', $cleanName);
        $cleanName = substr($cleanName, 0, 100);

        if (empty($cleanName)) {
            $cleanName = 'documento_' . time();
        }

        $cleanExtension = preg_replace('/[^a-zA-Z0-9]/', '', $extension);

        return $cleanName . '.' . strtolower($cleanExtension);
    }

    /**
     * Validar MIME real
     */
    private function validateMimeType($file): bool
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mimeType = finfo_file($finfo, $file->getRealPath());

        finfo_close($finfo);

        return in_array($mimeType, self::ALLOWED_MIME_TYPES);
    }

    /**
     * Validar extensión
     */
    private function validateExtension(string $fileName): bool
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        return in_array($extension, self::ALLOWED_EXTENSIONS);
    }

    /**
     * Validar path traversal
     */
    private function validateNoPathTraversal(string $fileName): bool
    {
        if (strpos($fileName, '..') !== false) {
            return false;
        }

        if (
            strpos($fileName, '/') !== false ||
            strpos($fileName, '\\') !== false
        ) {
            return false;
        }

        return true;
    }

    /**
     * Escaneo básico de contenido malicioso
     */
    private function scanForMaliciousContent($file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $textExtensions = [
            'txt',
            'xml',
            'html',
            'svg',
            'js',
            'php',
            'asp',
            'jsp'
        ];

        if (!in_array($extension, $textExtensions)) {
            return true;
        }

        $content = file_get_contents($file->getRealPath());

        $lowerContent = strtolower($content);

        $dangerousPatterns = [
            '<?php',
            '<?=',
            '<script',
            'javascript:',
            'onload=',
            'onerror=',
            'eval(',
            'base64_decode',
            'system(',
            'exec(',
            'shell_exec',
            'passthru',
            'proc_open',
            'popen',
            'assert(',
            'create_function',
            '`',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (strpos($lowerContent, $pattern) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validación completa
     */
    private function validateFile($file, string $context = 'store'): ?string
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            return "El archivo excede el tamaño máximo de " .
                (self::MAX_FILE_SIZE / 1048576) .
                "MB.";
        }

        if (
            !$this->validateNoPathTraversal(
                $file->getClientOriginalName()
            )
        ) {
            return "Nombre de archivo inválido.";
        }

        if (
            !$this->validateExtension(
                $file->getClientOriginalName()
            )
        ) {
            $ext = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_EXTENSION
            );

            return "Extensión '{$ext}' no permitida. Permitidas: " .
                implode(', ', self::ALLOWED_EXTENSIONS);
        }

        if (!$this->validateMimeType($file)) {
            return "Tipo de archivo inválido o alterado.";
        }

        if (!$this->scanForMaliciousContent($file)) {
            return "El archivo contiene contenido potencialmente malicioso.";
        }

        return null;
    }

    public function store()
    {
        $this->validate([
            'type' => 'required|string|max:100',
            'file' => 'required|array',
            'file.*' => 'file',
        ]);

        if (!in_array($this->type, self::ALLOWED_TYPES)) {
            $this->addError('type', 'Tipo de documento no válido.');
            return;
        }

        $errors = [];

        $filesToStore = [];

        foreach ($this->file as $index => $uploadedFile) {

            $error = $this->validateFile($uploadedFile, 'store');

            if ($error) {
                $errors[] = "Archivo '{$uploadedFile->getClientOriginalName()}': {$error}";
                continue;
            }

            $originalName = $uploadedFile->getClientOriginalName();

            $safeName = $this->sanitizeFileName($originalName);

            $relativePath = 'documents/' . $this->type . '/' . $safeName;

            if (
                Document::where('file_path', $relativePath)->exists()
            ) {
                $errors[] = "El archivo '{$originalName}' ya existe en el sistema.";
                continue;
            }

            if (
                Storage::disk('public')->exists($relativePath)
            ) {
                $errors[] = "Ya existe un archivo físico con el nombre '{$safeName}'.";
                continue;
            }

            $filesToStore[] = [
                'file' => $uploadedFile,
                'safe_name' => $safeName,
                'original_name' => $originalName,
            ];
        }

        if (!empty($errors)) {

            foreach ($errors as $error) {
                $this->addError('file', $error);
            }

            return;
        }

        foreach ($filesToStore as $fileData) {

            Storage::disk('public')->makeDirectory(
                'documents/' . $this->type
            );

            $storedPath = $fileData['file']->storeAs(
                'documents/' . $this->type,
                $fileData['safe_name'],
                'public'
            );

            if (
                !$storedPath ||
                !Storage::disk('public')->exists($storedPath)
            ) {
                $this->addError(
                    'file',
                    "Error al guardar el archivo '{$fileData['original_name']}'"
                );

                return;
            }

            Document::create([
                'type' => $this->type,
                'file_name' => $fileData['original_name'],
                'file_path' => $storedPath,
            ]);
        }

        Flux::toast(
            variant: 'success',
            heading: 'Registro Creado.',
            text: "Los archivos se han creado exitosamente.",
            duration: 3000
        );

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
            'file' => 'nullable|file',
        ]);

        if (!in_array($this->type, self::ALLOWED_TYPES)) {
            $this->addError('type', 'Tipo de documento no válido.');
            return;
        }

        // NUEVO ARCHIVO
        if ($this->file) {

            $error = $this->validateFile($this->file, 'update');

            if ($error) {
                $this->addError('file', $error);
                return;
            }

            $originalName = $this->file->getClientOriginalName();

            $safeName = $this->sanitizeFileName($originalName);

            $newPath = 'documents/' . $this->type . '/' . $safeName;

            if (
                Document::where('file_path', $newPath)
                    ->where('id', '!=', $this->document->id)
                    ->exists()
            ) {
                $this->addError(
                    'file',
                    "El archivo '{$originalName}' ya existe en el sistema."
                );

                return;
            }

            if (
                Storage::disk('public')->exists($newPath)
            ) {
                $this->addError(
                    'file',
                    "Ya existe un archivo físico con ese nombre."
                );

                return;
            }

            Storage::disk('public')->makeDirectory(
                'documents/' . $this->type
            );

            if (
                $this->document->file_path &&
                Storage::disk('public')->exists(
                    $this->document->file_path
                )
            ) {
                Storage::disk('public')->delete(
                    $this->document->file_path
                );
            }

            $storedPath = $this->file->storeAs(
                'documents/' . $this->type,
                $safeName,
                'public'
            );

            if (
                !$storedPath ||
                !Storage::disk('public')->exists($storedPath)
            ) {
                $this->addError(
                    'file',
                    "Error al guardar el archivo."
                );

                return;
            }

            $this->document->fill([
                'type' => $this->type,
                'file_name' => $originalName,
                'file_path' => $storedPath,
            ])->save();
        }

        // SOLO CAMBIO DE TIPO
        else {

            if ($this->document->type !== $this->type) {

                $oldPath = $this->document->file_path;

                $oldFileName = $this->document->file_name;

                $safeName = $this->sanitizeFileName($oldFileName);

                $newPath = 'documents/' . $this->type . '/' . $safeName;

                if (
                    Document::where('file_path', $newPath)
                        ->where('id', '!=', $this->document->id)
                        ->exists()
                ) {
                    $this->addError(
                        'file',
                        "Ya existe un archivo con ese nombre en la nueva ubicación."
                    );

                    return;
                }

                Storage::disk('public')->makeDirectory(
                    'documents/' . $this->type
                );

                if (
                    Storage::disk('public')->exists($oldPath)
                ) {

                    if (
                        !Storage::disk('public')->move(
                            $oldPath,
                            $newPath
                        )
                    ) {
                        $this->addError(
                            'file',
                            "Error al mover el archivo a la nueva ubicación."
                        );

                        return;
                    }
                }

                $this->document->fill([
                    'type' => $this->type,
                    'file_path' => $newPath,
                ])->save();
            } else {

                $this->document->fill([
                    'type' => $this->type,
                ])->save();
            }
        }

        Flux::toast(
            variant: 'success',
            heading: 'Registro Actualizado.',
            text: "El registro se ha actualizado exitosamente.",
            duration: 3000
        );

        $this->reset(['file']);
    }
}