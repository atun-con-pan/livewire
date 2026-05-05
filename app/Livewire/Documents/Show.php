<?php

namespace App\Livewire\Documents;

use App\Livewire\Forms\FormDocument;
use App\Models\Document;
use Livewire\Component;

class Show extends Component
{    
    public FormDocument $form;
    public Document $document;

    public function isCreated(): bool
    {
        return false;
    }

    public function isEdit(): bool
    {
        return false;
    }

    public function isShow(): bool
    {
        return true;
    }

    public function save()
    {
        abort(403);
    }

    public function showFile()
    {
        return redirect()->route('documents.file', $this->document->id);
    }

    public function downloadFile()
    {
        return redirect()->route('documents.download', $this->document->id);
    }

    public function mount(Document $document)
    {
        $this->document = $document;
        $this->form->setDocument($document);
    }

    public function render()
    {
        return view('livewire.documents.create');
    }
}
