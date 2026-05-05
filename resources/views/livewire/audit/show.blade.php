<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Detalle de Auditoría</h2>

        <flux:button href="{{ route('audit.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
            Regresar
        </flux:button>
    </div>

    <!-- 🔹 1. INFORMACIÓN GENERAL -->
    <div class="p-4 bg-white dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <h3 class="font-semibold text-lg mb-3 border-b pb-1 text-gray-800 dark:text-gray-200">Información General</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-300">
            <p><strong>ID Auditoría:</strong> {{ $audit->id }}</p>

            <p>
                <strong>Tipo de evento:</strong>
                <span class="
                    @if($audit->event === 'created') text-green-600 dark:text-green-400
                    @elseif($audit->event === 'updated') text-yellow-600 dark:text-yellow-400
                    @elseif($audit->event === 'deleted') text-red-600 dark:text-red-400
                    @endif
                ">
                    {{ ucfirst($audit->event) }}
                </span>
            </p>

            <p><strong>Fecha:</strong> {{ $audit->created_at }}</p>
            <p><strong>Hace:</strong> {{ $audit->created_at?->diffForHumans() }}</p>
        </div>
    </div>

    <!-- 🔹 2. REGISTRO AFECTADO -->
    <div class="p-4 bg-white dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <h3 class="font-semibold text-lg mb-3 border-b pb-1 text-gray-800 dark:text-gray-200">Registro Afectado</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-300">
            <p><strong>Modelo:</strong> {{ class_basename($audit->auditable_type) }}</p>
            <p><strong>ID del registro:</strong> {{ $audit->auditable_id }}</p>
            <p><strong>Clase completa:</strong> {{ $audit->auditable_type }}</p>

            <p>
                <strong>Descripción:</strong>
                {{ $audit->auditable?->file_name
                    ?? $audit->auditable?->name
                    ?? $audit->auditable?->contract_name
                    ?? trim(
                        ($audit->auditable?->first_name ?? '') . ' ' .
                        ($audit->auditable?->first_surname ?? '')
                    )
                    ?: 'N/A'
                }}
            </p>
        </div>
    </div>

    <!-- 🔹 3. USUARIO -->
    <div class="p-4 bg-white dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <h3 class="font-semibold text-lg mb-3 border-b pb-1 text-gray-800 dark:text-gray-200">Usuario Responsable</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-300">
            <p><strong>ID:</strong> {{ $audit->user_id ?? 'Sistema' }}</p>
            <p><strong>Nombre:</strong> {{ $audit->user?->name ?? 'Sistema' }}</p>
            <p><strong>Email:</strong> {{ $audit->user?->email ?? 'N/A' }}</p>
            <p><strong>Tipo:</strong> {{ $audit->user_type ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- 🔹 4. DETALLE TÉCNICO -->
    <div class="p-4 bg-white dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <h3 class="font-semibold text-lg mb-3 border-b pb-1 text-gray-800 dark:text-gray-200">Detalle Técnico</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-300">
            <p><strong>IP:</strong> {{ $audit->ip_address ?? 'N/A' }}</p>
            <p><strong>URL:</strong> {{ $audit->url ?? 'N/A' }}</p>
            <p><strong>Método:</strong> {{ $audit->method ?? 'N/A' }}</p>
            <p><strong>Sesión:</strong> {{ $audit->session_id ?? 'N/A' }}</p>
        </div>

        <p class="mt-2 text-xs break-all text-gray-600 dark:text-gray-400">
            <strong>Navegador:</strong><br>
            {{ $audit->user_agent ?? 'N/A' }}
        </p>
    </div>

    @php
        $old = $audit->old_values ?? [];
        $new = $audit->new_values ?? [];

        $allKeys = collect(array_keys($old))
            ->merge(array_keys($new))
            ->unique();
    @endphp

    <!-- 🔹 5. CAMBIOS REALIZADOS -->
    <div class="p-4 bg-white dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <h3 class="font-semibold text-lg mb-3 border-b pb-1 text-gray-800 dark:text-gray-200">Cambios Realizados</h3>

        <div class="space-y-2 text-sm">

            @php $hayCambios = false; @endphp

            @foreach ($allKeys as $campo)
                @php
                    $oldValue = $old[$campo] ?? null;
                    $newValue = $new[$campo] ?? null;
                @endphp

                @if ($oldValue != $newValue)
                    @php $hayCambios = true; @endphp

                    <div class="p-2 rounded bg-gray-50 dark:bg-gray-800/60 flex flex-wrap gap-2 text-gray-700 dark:text-gray-300">
                        <span class="font-semibold capitalize">
                            {{ str_replace('_', ' ', $campo) }}:
                        </span>

                        <span class="text-red-500 dark:text-red-400 line-through">
                            {{ $oldValue ?? 'N/A' }}
                        </span>

                        <span>→</span>

                        <span class="text-green-600 dark:text-green-400 font-medium">
                            {{ $newValue ?? 'N/A' }}
                        </span>
                    </div>
                @endif
            @endforeach

            @if(!$hayCambios)
                <p class="text-gray-500 dark:text-gray-400">No se detectaron cambios visibles</p>
            @endif

        </div>
    </div>

    <!-- 🔹 6. TABLA COMPLETA -->
    <div class="p-4 bg-white dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <h3 class="font-semibold text-lg mb-3 border-b pb-1 text-gray-800 dark:text-gray-200">Valores del Registro</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-800 dark:text-gray-200">
                <thead class="text-left border-b rounded-radius border border-outline dark:border-outline-dark shadow-md">
                    <tr>
                        <th class="p-2">Campo</th>
                        <th class="p-2">Valor Anterior</th>
                        <th class="p-2">Valor Nuevo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allKeys as $campo)
                        @php
                            $oldValue = $old[$campo] ?? null;
                            $newValue = $new[$campo] ?? null;
                        @endphp

                        <tr class="border-t rounded-radius border border-outline dark:border-outline-dark shadow-md 
                            {{ $oldValue != $newValue ? 'bg-yellow-50 dark:bg-yellow-800/20' : 'dark:bg-gray-800/30' }}">
                            
                            <td class="p-2 font-medium capitalize">
                                {{ str_replace('_', ' ', $campo) }}
                            </td>

                            <td class="p-2 text-red-500 dark:text-red-400">
                                {{ $oldValue ?? '—' }}
                            </td>

                            <td class="p-2 text-green-600 dark:text-green-400">
                                {{ $newValue ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🔹 7. JSON -->
    <div x-data="{ open: false }" class="p-4 bg-white dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <h3 @click="open = !open"
            class="font-semibold text-lg mb-2 cursor-pointer flex justify-between items-center text-gray-800 dark:text-gray-200">
            Datos técnicos completos
            <span x-text="open ? '▲' : '▼'"></span>
        </h3>

        <div x-show="open" class="space-y-4 text-xs">

            <div>
                <strong>Old Values:</strong>
                <pre class="bg-gray-100 dark:bg-gray-900/80 p-2 rounded overflow-auto text-gray-800 dark:text-gray-300">
{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                </pre>
            </div>

            <div>
                <strong>New Values:</strong>
                <pre class="bg-gray-100 dark:bg-gray-900/80 p-2 rounded overflow-auto text-gray-800 dark:text-gray-300">
{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                </pre>
            </div>

            @if(!empty($audit->metadata))
                <div>
                    <strong>Metadata:</strong>
                    <pre class="bg-gray-100 dark:bg-gray-900/80 p-2 rounded overflow-auto text-gray-800 dark:text-gray-300">
{{ json_encode($audit->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                    </pre>
                </div>
            @endif

        </div>
    </div>

</div>