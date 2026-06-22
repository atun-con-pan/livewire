<?php

namespace App\Livewire\Documents;

use App\Livewire\Forms\FormDocument;
use App\Models\Document;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    public FormDocument $form;
    public Document $document;

    public function isCreated(): bool
    {
        return false;
    }

    public function isEdit(): bool
    {
        return true;
    }

    public function isShow(): bool
    {
        return false;
    }

    public function mount(Document $document)
    {
        $this->document = $document;
        $this->form->setDocument($document);
    }

    public function save()
    {
        $this->form->update();
        $this->redirectRoute('documents.index', navigate: true);
    }

    public function delete()
    {
        if ($this->document->file_path && Storage::disk('public')->exists($this->document->file_path)) {
            Storage::disk('public')->delete($this->document->file_path);
        }

        $this->document->delete();

        $this->redirectRoute('documents.index', navigate: true);

        Flux::toast(variant: 'success', text: 'Registro eliminado correctamente');
    }

    public function render()
    {
        return view('livewire.documents.create');
    }
}