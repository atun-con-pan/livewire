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
        'Contratos', 'Actas', 'Fianzas', 'Cartas', 'Oficios', 'Planos', 'Facturas', 'Recibos', 'Planillas', 'Cotizaciones', 'Documentos ofertas', 'Informes fotográficos', 'Impuestos', 'Otros'
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
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'txt'
    ];
    
    // Tamaño máximo en bytes (5MB es más seguro)
    private const MAX_FILE_SIZE = 536870912;
    
    /**
     * Sanitizar nombre de archivo - Elimina caracteres peligrosos
     */
    private function sanitizeFileName(string $fileName): string
    {
        // Separar nombre y extensión
        $pathInfo = pathinfo($fileName);
        $name = $pathInfo['filename'] ?? 'file';
        $extension = $pathInfo['extension'] ?? '';
        
        // Eliminar caracteres peligrosos y espacios (solo letras, números, guiones, guiones bajos)
        $cleanName = preg_replace('/[^a-zA-Z0-9_-]/u', '', $name);
        $cleanName = preg_replace('/\.\./', '', $cleanName); // Prevenir path traversal
        $cleanName = substr($cleanName, 0, 100); // Limitar longitud
        
        // Si quedó vacío, asignar nombre por defecto
        if (empty($cleanName)) {
            $cleanName = 'documento_' . time();
        }
        
        // Devolver con extensión limpia
        $cleanExtension = preg_replace('/[^a-zA-Z0-9]/', '', $extension);
        return $cleanName . '.' . strtolower($cleanExtension);
    }
    
    /**
     * Validar tipo MIME real del archivo (no confiar en el cliente)
     */
    private function validateMimeType($file): bool
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);
        
        return in_array($mimeType, self::ALLOWED_MIME_TYPES);
    }
    
    /**
     * Validar que la extensión sea permitida
     */
    private function validateExtension(string $fileName): bool
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return in_array($extension, self::ALLOWED_EXTENSIONS);
    }
    
    /**
     * Validar que el nombre no contenga path traversal
     */
    private function validateNoPathTraversal(string $fileName): bool
    {
        // Rechazar si contiene ../ o ..\
        if (strpos($fileName, '..') !== false) {
            return false;
        }
        
        // Rechazar si contiene barras o backslashes
        if (strpos($fileName, '/') !== false || strpos($fileName, '\\') !== false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Escanear archivo en busca de contenido malicioso básico
     */
    private function scanForMaliciousContent($file): bool
    {
        // Solo escanear archivos de texto, PDFs y otros que puedan contener scripts
        $extension = strtolower($file->getClientOriginalExtension());
        $textExtensions = ['txt', 'xml', 'html', 'svg', 'js', 'php', 'asp', 'jsp'];
        
        if (!in_array($extension, $textExtensions)) {
            return true; // No escaneamos binarios profundamente
        }
        
        $content = file_get_contents($file->getRealPath());
        $lowerContent = strtolower($content);
        
        // Patrones peligrosos
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
            '`',  // backticks para ejecución
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            if (strpos($lowerContent, $pattern) !== false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validación completa de un archivo
     */
    private function validateFile($file, string $context = 'store'): ?string
    {
        // Validar tamaño
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            return "El archivo excede el tamaño máximo de " . (self::MAX_FILE_SIZE / 1048576) . "MB.";
        }
        
        // Validar path traversal
        if (!$this->validateNoPathTraversal($file->getClientOriginalName())) {
            return "Nombre de archivo inválido.";
        }
        
        // Validar extensión
        if (!$this->validateExtension($file->getClientOriginalName())) {
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            return "Extensión '{$ext}' no permitida. Permitidas: " . implode(', ', self::ALLOWED_EXTENSIONS);
        }
        
        // Validar MIME real
        if (!$this->validateMimeType($file)) {
            return "Tipo de archivo inválido o alterado.";
        }
        
        // Escanear contenido malicioso
        if (!$this->scanForMaliciousContent($file)) {
            return "El archivo contiene contenido potencialmente malicioso.";
        }
        
        return null; // Sin errores
    }
    
    public function store()
    {
        $this->validate([
            'type' => 'required|string|max:100',
            'file' => 'required|array',
            'file.*' => 'file',
        ]);
        
        // Validar tipo contra lista blanca
        if (!in_array($this->type, self::ALLOWED_TYPES)) {
            $this->addError('type', 'Tipo de documento no válido.');
            return;
        }
        
        $errors = [];
        $filesToStore = [];
        
        // Validar todos los archivos ANTES de guardar
        foreach ($this->file as $index => $uploadedFile) {
            // Validar seguridad del archivo
            $error = $this->validateFile($uploadedFile, 'store');
            if ($error) {
                $errors[] = "Archivo '{$uploadedFile->getClientOriginalName()}': {$error}";
                continue;
            }
            
            $originalName = $uploadedFile->getClientOriginalName();
            $safeName = $this->sanitizeFileName($originalName);
            $relativePath = 'documents/' . $this->type . '/' . $safeName;
            
            // Verificar si ya existe en BD
            if (Document::where('path', $relativePath)->exists()) {
                $errors[] = "El archivo '{$originalName}' ya existe en el sistema.";
                continue;
            }
            
            // Verificar si existe físicamente (doble verificación)
            if (Storage::disk('public')->exists($relativePath)) {
                $errors[] = "Ya existe un archivo físico con el nombre '{$safeName}'.";
                continue;
            }
            
            $filesToStore[] = [
                'file' => $uploadedFile,
                'safe_name' => $safeName,
                'original_name' => $originalName,
            ];
        }
        
        // Si hay errores, detener todo
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->addError('file', $error);
            }
            return;
        }
        
        // Guardar todos los archivos validados
        foreach ($filesToStore as $fileData) {
            // Crear directorio con permisos seguros
            Storage::disk('public')->makeDirectory('documents/' . $this->type);
            
            // Guardar archivo
            $storedPath = $fileData['file']->storeAs(
                'documents/' . $this->type,
                $fileData['safe_name'],
                'public'
            );
            
            // Verificar que se guardó correctamente
            if (!$storedPath || !Storage::disk('public')->exists($storedPath)) {
                $this->addError('file', "Error al guardar el archivo '{$fileData['original_name']}'");
                return;
            }
            
            Document::create([
                'type' => $this->type,
                'file_name' => $fileData['original_name'],
                'path' => $storedPath,
            ]);
        }
        
        Flux::toast(
            variant: 'success',
            heading: 'Registro Creado.',
            text: "Los archivos se han creado exitosamente.",
            duration: 3000
        );
        
        // Limpiar después de guardar
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
        
        // Validar tipo contra lista blanca
        if (!in_array($this->type, self::ALLOWED_TYPES)) {
            $this->addError('type', 'Tipo de documento no válido.');
            return;
        }
        
        // CASO 1: Sube nuevo archivo
        if ($this->file) {
            // Validar el nuevo archivo
            $error = $this->validateFile($this->file, 'update');
            if ($error) {
                $this->addError('file', $error);
                return;
            }
            
            $originalName = $this->file->getClientOriginalName();
            $safeName = $this->sanitizeFileName($originalName);
            $newPath = 'documents/' . $this->type . '/' . $safeName;
            
            // Verificar duplicado (excluyendo el documento actual)
            if (Document::where('path', $newPath)
                    ->where('id', '!=', $this->document->id)
                    ->exists()
            ) {
                $this->addError('file', "El archivo '{$originalName}' ya existe en el sistema.");
                return;
            }
            
            // Verificar si existe físicamente
            if (Storage::disk('public')->exists($newPath)) {
                $this->addError('file', "Ya existe un archivo físico con ese nombre.");
                return;
            }
            
            // Crear directorio si no existe
            Storage::disk('public')->makeDirectory('documents/' . $this->type);
            
            // Eliminar archivo anterior si existe
            if ($this->document->path && Storage::disk('public')->exists($this->document->path)) {
                Storage::disk('public')->delete($this->document->path);
            }
            
            // Guardar nuevo archivo
            $storedPath = $this->file->storeAs(
                'documents/' . $this->type,
                $safeName,
                'public'
            );
            
            if (!$storedPath || !Storage::disk('public')->exists($storedPath)) {
                $this->addError('file', "Error al guardar el archivo.");
                return;
            }
            
            $this->document->fill([
                'type' => $this->type,
                'file_name' => $originalName,
                'path' => $storedPath,
            ])->save();
            
        } 
        // CASO 2: Solo cambia el tipo
        else {
            if ($this->document->type !== $this->type) {
                $oldPath = $this->document->path;
                $oldFileName = $this->document->file_name;
                
                // Sanitizar el nombre existente
                $safeName = $this->sanitizeFileName($oldFileName);
                $newPath = 'documents/' . $this->type . '/' . $safeName;
                
                // Verificar que no exista en nuevo destino
                if (Document::where('path', $newPath)
                        ->where('id', '!=', $this->document->id)
                        ->exists()
                ) {
                    $this->addError('file', "Ya existe un archivo con ese nombre en la nueva ubicación.");
                    return;
                }
                
                // Crear nuevo directorio
                Storage::disk('public')->makeDirectory('documents/' . $this->type);
                
                // Mover archivo físicamente si existe
                if (Storage::disk('public')->exists($oldPath)) {
                    if (!Storage::disk('public')->move($oldPath, $newPath)) {
                        $this->addError('file', "Error al mover el archivo a la nueva ubicación.");
                        return;
                    }
                }
                
                // Actualizar en BD
                $this->document->fill([
                    'type' => $this->type,
                    'path' => $newPath,
                ])->save();
                
            } else {
                // Sin cambios en tipo ni archivo
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