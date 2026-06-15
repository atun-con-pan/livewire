<x-layouts::app :title="__('Dashboard')">

    @php
        $cards = [
            [
                'url' => route('affidavits'),
                'title' => 'Declaraciones Juradas',
                'desc' => 'Generar declaraciones juradas automáticamente.',
                'icon' => 'document-text',
            ],
            [
                'url' => route('authenticates'),
                'title' => 'Auténticas',
                'desc' => 'Crear auténticas a partir de plantillas.',
                'icon' => 'shield-check',
            ],
            [
                'url' => route('reports'),
                'title' => 'Reportes Fotográficos',
                'desc' => 'Generar reportes fotográficos profesionales.',
                'icon' => 'camera',
            ],
            [
                'url' => route('ilovepdf'),
                'title' => 'ILovePDF',
                'desc' => 'Herramientas para documentos PDF.',
                'icon' => 'folder',
            ],
            [
                'url' => 'https://recit.mintrabajo.gob.gt/login',
                'title' => 'Contratos Trabajadores',
                'desc' => 'Gestión y registro de contratos.',
                'extra' => 'Lucero-2026',
                'icon' => 'document-duplicate',
            ],
            [
                'url' => 'https://solvencias.mintrabajo.gob.gt',
                'title' => 'Solvencias de Trabajo',
                'desc' => 'Administración de solvencias laborales.',
                'extra' => '61f4206f-66c',
                'icon' => 'check-badge',
            ],
            [
                'url' => 'https://servicios.igssgt.org/login.aspx?ReturnUrl=%2fSistema%2fdefault.aspx',
                'title' => 'Planillas IGSS',
                'desc' => 'Control de planillas electrónicas.',
                'icon' => 'users',
            ],
            [
                'url' => 'https://sso.minfin.gob.gt/Portal/Default/Credenciales/Login?ReturnUrl=%2f',
                'title' => 'RGAE',
                'desc' => 'Registro General de Adquisiciones del Estado.',
                'extra' => 'Alejandro1991',
                'icon' => 'building-office',
            ],
        ];
    @endphp

    <div class="space-y-10">

        <!-- Encabezado -->
        <div>
            <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                Herramientas
            </h1>

            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                Acceso rápido a todas las herramientas del sistema.
            </p>
        </div>

        <!-- Grid -->
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

            @foreach ($cards as $card)

                <a href="{{ $card['url'] }}"
                   target="_blank"
                   class="group flex flex-col rounded-2xl border border-zinc-200
                          bg-white p-6 transition-all duration-200
                          hover:border-zinc-300 hover:shadow-md
                          dark:border-zinc-800 dark:bg-zinc-900
                          dark:hover:border-zinc-700">

                    <!-- Icono -->
                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-zinc-100
                                dark:bg-zinc-800">

                        <flux:icon
                            name="{{ $card['icon'] }}"
                            class="size-6 text-zinc-700 dark:text-zinc-300" />

                    </div>

                    <!-- Contenido -->
                    <div class="mt-5 flex-1">

                        <h2 class="text-lg font-medium text-zinc-900 dark:text-white">
                            {{ $card['title'] }}
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                            {{ $card['desc'] }}
                        </p>

                        @isset($card['extra'])
                            <div class="mt-3 inline-flex items-center rounded-lg
                                        bg-zinc-100 px-2.5 py-1 text-xs
                                        font-medium text-zinc-600
                                        dark:bg-zinc-800 dark:text-zinc-300">

                                Contraseña: {{ $card['extra'] }}

                            </div>
                        @endisset

                    </div>

                    <!-- Footer -->
                    <div class="mt-6 flex items-center justify-between">

                        <span class="text-sm font-medium text-zinc-600
                                     transition-colors group-hover:text-zinc-900
                                     dark:text-zinc-400 dark:group-hover:text-white">

                            Abrir

                        </span>

                        <flux:icon
                            name="arrow-up-right"
                            class="size-5 text-zinc-400 transition-transform
                                   duration-200 group-hover:translate-x-0.5
                                   group-hover:-translate-y-0.5" />

                    </div>

                </a>

            @endforeach

        </div>

    </div>

</x-layouts::app>