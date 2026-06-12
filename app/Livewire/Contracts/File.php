<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\FilesContract;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class File extends Component
{
    use WithFileUploads, WithPagination;

    public Contract $contract;
    public $file = [];

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function store()
    {
        $this->validate([
            'file' => 'required|array',
            'file.*' => 'file|max:102400',
        ]);

        foreach ($this->file as $uploadedFile) {

            $file_name = $uploadedFile->getClientOriginalName();
            $directory = 'projects/' . $this->contract->id . '/';
            $file_path = "{$directory}/{$file_name}";

            // 🔥 Validar duplicado SOLO para este proyecto
            if (FilesContract::where('file_path', $file_path)
                ->where('contract_id', $this->contract->id)
                ->exists()) {

                $this->addError('file', "El archivo '{$file_name}' ya existe.");
                continue;
            }

            // Guardar archivo
            $uploadedFile->storeAs($directory, $file_name, 'public');

            // Guardar en BD
            FilesContract::create([
                'file_name' => $file_name,
                'file_path' => $file_path,
                'contract_id' => $this->contract->id, // 🔥 clave
            ]);
        }

        $this->reset('file');
        
        Flux::toast(
            variant: 'success',
            heading: 'Registro Creado.',
            text: "El registro se ha creado exitosamente.",
            duration: 3000
        );
    }

    public function delete(FilesContract $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        Flux::toast(
            variant: 'danger',
            heading: 'Registro Eliminado.',
            text: "El registro se ha eliminado exitosamente.",
            duration: 3000,
        );
    }
    
    public function render()
    {
        return view('livewire.contracts.file', [
            'files' => FilesContract::latest()->where('contract_id', $this->contract->id)
                ->Paginate(10),
        ]);
    }
}
