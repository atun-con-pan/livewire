<x-layouts::app :title="__('Dashboard')">

    @php
    $cards = [
        ['title' => 'Producto 1', 'desc' => 'Descripción del producto 1', 'img' => 'https://picsum.photos/400/200?1'],
        ['title' => 'Producto 2', 'desc' => 'Descripción del producto 2', 'img' => 'https://picsum.photos/400/200?2'],
        ['title' => 'Producto 3', 'desc' => 'Descripción del producto 3', 'img' => 'https://picsum.photos/400/200?3'],
        ['title' => 'Producto 4', 'desc' => 'Descripción del producto 4', 'img' => 'https://picsum.photos/400/200?4'],
        ['title' => 'Producto 5', 'desc' => 'Descripción del producto 5', 'img' => 'https://picsum.photos/400/200?5'],
        ['title' => 'Producto 6', 'desc' => 'Descripción del producto 6', 'img' => 'https://picsum.photos/400/200?6'],
        ['title' => 'Producto 7', 'desc' => 'Descripción del producto 7', 'img' => 'https://picsum.photos/400/200?7'],
        ['title' => 'Producto 8', 'desc' => 'Descripción del producto 8', 'img' => 'https://picsum.photos/400/200?8'],
        ['title' => 'Producto 9', 'desc' => 'Descripción del producto 9', 'img' => 'https://picsum.photos/400/200?9'],
        ['title' => 'Producto 10', 'desc' => 'Descripción del producto 10', 'img' => 'https://picsum.photos/400/200?10'],
        ['title' => 'Producto 11', 'desc' => 'Descripción del producto 11', 'img' => 'https://picsum.photos/400/200?11'],
        ['title' => 'Producto 12', 'desc' => 'Descripción del producto 12', 'img' => 'https://picsum.photos/400/200?12'],
    ];
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="grid auto-rows-min gap-4 md:grid-cols-4">

            @foreach($cards as $card)
                <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm hover:shadow-lg transition">

                    <!-- Imagen -->
                    <img src="{{ $card['img'] }}" 
                        alt="{{ $card['title'] }}" 
                        class="w-full h-30 object-cover">

                    <!-- Contenido -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ $card['title'] }}
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                            {{ $card['desc'] }}
                        </p>
                    </div>

                </div>
            @endforeach

        </div>

    </div>

</x-layouts::app>