<?php

namespace App\Livewire\Audit;

use Livewire\Component;
use Livewire\WithPagination;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = $this->search;

        $audits = Audit::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    // 🔥 columnas del audit
                    $q->where('event', 'like', "%$search%")
                        ->orWhere('auditable_type', 'like', "%$search%")
                        ->orWhere('auditable_id', 'like', "%$search%")
                        ->orWhere('user_id', 'like', "%$search%")
                        ->orWhere('ip_address', 'like', "%$search%")
                        ->orWhere('user_agent', 'like', "%$search%")
                        ->orWhere('tags', 'like', "%$search%")
                        ->orWhere('old_values', 'like', "%$search%")
                        ->orWhere('new_values', 'like', "%$search%");
                })

                // 🔥 búsqueda por nombre del usuario
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(9);

        return view('livewire.audit.index', compact('audits'));
    }

    public function getAuditName($audit)
    {
        $values = $audit->new_values ?: $audit->old_values;

        if (!$values) {
            return 'N/A';
        }

        // 👤 Colaboradores → nombre completo
        if (isset($values['first_name'])) {
            return implode(' ', array_filter([
                $values['first_name'] ?? null,
                $values['middle_name'] ?? null,
                $values['first_surname'] ?? null,
                $values['second_last_name'] ?? null,
            ]));
        }

        // 📄 Archivos / documentos
        if (isset($values['file_name'])) {
            return $values['file_name'];
        }

        // 📁 Proyectos
        if (isset($values['name'])) {
            return $values['name'];
        }

        if (isset($values['nog'])) {
            return 'NOG: ' . $values['nog'];
        }

        // 📜 Contratos
        if (isset($values['contract_name'])) {
            return $values['contract_name'];
        }

        if (isset($values['no_contract'])) {
            return 'Contrato #' . $values['no_contract'];
        }

        // 🔥 fallback inteligente (por si algo no coincide)
        return collect($values)
            ->filter()
            ->first() ?? 'N/A';
    }

    // 🔥 Helper para modelo bonito
    public function getModelName($audit)
    {
        return Str::headline(class_basename($audit->auditable_type));
    }
}