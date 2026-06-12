<x-layouts::app :title="__('Dashboard')">

    @php
        $cards = [
            [
                'url' => 'https://google.com',
                'title' => 'Declaraciones Juradas',
                'desc' => 'Generar declaraciones juradas automáticamente.',
                'icon' => 'document-text',
            ],
            [
                'url' => 'https://google.com',
                'title' => 'Auténticas',
                'desc' => 'Crear auténticas a partir de plantillas.',
                'icon' => 'shield-check',
            ],
            [
                'url' => 'https://google.com',
                'title' => 'Reportes Fotográficos',
                'desc' => 'Generar reportes fotográficos profesionales.',
                'icon' => 'camera',
            ],
            [
                'url' => 'https://google.com',
                'title' => 'Contratos',
                'desc' => 'Gestión y registro de contratos.',
                'icon' => 'document-duplicate',
            ],
            [
                'url' => 'https://google.com',
                'title' => 'Solvencias',
                'desc' => 'Administración de solvencias laborales.',
                'icon' => 'check-badge',
            ],
            [
                'url' => 'https://google.com',
                'title' => 'Planillas IGSS',
                'desc' => 'Control de planillas electrónicas.',
                'icon' => 'users',
            ],
            [
                'url' => 'https://google.com',
                'title' => 'RGAE',
                'desc' => 'Registro General de Adquisiciones del Estado.',
                'icon' => 'building-office',
            ],
            [
                'url' => 'https://google.com',
                'title' => 'ILovePDF',
                'desc' => 'Herramientas para documentos PDF.',
                'icon' => 'folder',
            ],
        ];
    @endphp

    <div class="space-y-8">

        <!-- Encabezado -->
        <div>
            <h1 class="text-4xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Herramientas
            </h1>

            <p class="mt-2 text-zinc-500 dark:text-zinc-400">
                Acceso rápido a todas las utilidades del sistema.
            </p>
        </div>

        <!-- Grid -->
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">

            @foreach($cards as $card)

                <a href="{{ $card['url'] }}"
                   target="_blank"
                   class="group relative overflow-hidden rounded-3xl
                          bg-white dark:bg-zinc-900
                          shadow-sm hover:shadow-2xl
                          ring-1 ring-zinc-200 dark:ring-zinc-800
                          transition-all duration-300
                          hover:-translate-y-2">

                    <!-- Gradiente decorativo -->
                    <div class="absolute inset-x-0 top-0 h-1
                                bg-gradient-to-r
                                from-blue-500
                                via-cyan-500
                                to-indigo-500">
                    </div>

                    <div class="p-6">

                        <!-- Icono -->
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl
                                   bg-gradient-to-br
                                   from-blue-500
                                   to-indigo-600
                                   shadow-lg shadow-blue-500/20">

                            <flux:icon
                                name="{{ $card['icon'] }}"
                                class="size-8 text-white"
                            />

                        </div>

                        <!-- Título -->
                        <h2 class="mt-5 text-xl font-semibold text-zinc-900 dark:text-white">
                            {{ $card['title'] }}
                        </h2>

                        <!-- Descripción -->
                        <p class="mt-2 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                            {{ $card['desc'] }}
                        </p>

                        <!-- Footer -->
                        <div class="mt-6 flex items-center justify-between">

                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                Abrir herramienta
                            </span>

                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-full
                                       bg-zinc-100 dark:bg-zinc-800
                                       transition-all duration-300
                                       group-hover:bg-blue-600">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-4 w-4 text-zinc-600 dark:text-zinc-300
                                           group-hover:text-white">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>

                            </div>

                        </div>

                    </div>

                </a>

            @endforeach

        </div>

    </div>

</x-layouts::app>