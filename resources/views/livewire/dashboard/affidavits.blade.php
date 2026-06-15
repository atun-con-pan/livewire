<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex" />
    <title>Declaraciones Juradas</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/x-icon" />
    <script src="https://cdn.jsdelivr.net/npm/pizzip@3.0.6/dist/pizzip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docxtemplater@3.36.0/build/docxtemplater.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #0f172a;
            /* slate-900 */
            padding: 2rem;
            font-family: 'Inter', sans-serif;
            color: #cbd5e1;
            /* slate-300 */
        }

        /* Scrollbar for items container */
        #itemsContainer {
            max-height: 280px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #0ea5e9 transparent;
            /* cyan-500 */
        }

        #itemsContainer::-webkit-scrollbar {
            width: 8px;
        }

        #itemsContainer::-webkit-scrollbar-thumb {
            background-color: #0ea5e9;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <main class="max-w-3xl mx-auto bg-slate-800 rounded-lg shadow-lg p-8">
        <h3 class="text-3xl font-bold mb-8 text-center text-cyan-400">Generar Declaraciones Juradas</h3>

        <form id="docForm" class="space-y-6">
            <div>
                <label for="templateSelect" class="block mb-2 font-semibold text-slate-300">Selecciona plantilla</label>
                <select id="templateSelect"
                    class="w-full rounded bg-slate-900 border border-slate-700 text-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500" required>
                    <option value="templates/template.docx" selected>Declaración Jurada Dipconsa</option>
                    <option value="templates/template2.docx">Declaración Jurada Construtotales</option>
                </select>
            </div>

            <div>
                <label for="fecha" class="block mb-2 font-semibold text-slate-300">Fecha</label>
                <input type="text" id="fecha"
                    class="w-full rounded bg-slate-900 border border-slate-700 text-slate-200 px-3 py-2 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                    required placeholder="uno (1) de enero de dos mil veinticinco (2025)" />
            </div>

            <div>
                <label for="hora" class="block mb-2 font-semibold text-slate-300">Hora</label>
                <input type="text" id="hora"
                    class="w-full rounded bg-slate-900 border border-slate-700 text-slate-200 px-3 py-2 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                    required placeholder="ocho horas con treinta minutos (08:30)" />
            </div>

            <div>
                <label for="tipoEvento" class="block mb-2 font-semibold text-slate-300">Tipo de evento
                    (Cotización/Licitación)</label>
                <input type="text" id="tipoEvento"
                    class="w-full rounded bg-slate-900 border border-slate-700 text-slate-200 px-3 py-2 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                    required placeholder="Ej: Cotización" />
            </div>

            <div>
                <label for="proyecto" class="block mb-2 font-semibold text-slate-300">Proyecto</label>
                <input type="text" id="proyecto"
                    class="w-full rounded bg-slate-900 border border-slate-700 text-slate-200 px-3 py-2 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                    required placeholder="Proyecto XYZ" />
            </div>

            <hr class="border-slate-700" />

            <div>
                <label for="formatSelect" class="block mb-2 font-semibold text-slate-300">Formato de numeración</label>
                <select id="formatSelect"
                    class="w-full rounded bg-slate-900 border border-slate-700 text-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                    required>
                    <option value="letters">Letras (a), b), c), etc.</option>
                    <option value="numbers">Números (1), 2), 3), etc.</option>
                    <option value="roman">Números romanos (I), II), III), etc.</option>
                </select>
            </div>

            <label class="block mb-3 font-semibold text-slate-300">Items</label>
            <div id="itemsContainer" class="space-y-3 mb-4 max-h-72 overflow-auto">
                <div class="flex gap-3 items-start">
                    <input type="text"
                        class="flex-grow rounded bg-slate-900 border border-slate-700 text-slate-200 px-3 py-2 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 item-input"
                        placeholder="Item 1" required />
                </div>
            </div>

            <button type="button" id="addItemBtn"
                class="inline-block mb-6 rounded border border-cyan-500 text-cyan-500 px-4 py-1 hover:bg-cyan-500 hover:text-slate-900 transition">
                Agregar item
            </button>

            <button type="submit"
                class="w-full bg-cyan-500 hover:bg-cyan-600 transition font-bold py-3 rounded-lg shadow-lg">
                Generar documento
            </button>
        </form>
    </main>

    <script>
        function createItemInput(index) {
            const row = document.createElement('div');
            row.className = 'flex gap-3 items-start';

            const input = document.createElement('input');
            input.type = 'text';
            input.className =
                'flex-grow rounded bg-slate-900 border border-slate-700 text-slate-200 px-3 py-2 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 item-input';
            input.placeholder = `Item ${index}`;
            input.required = true;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className =
                'rounded border border-red-500 text-red-500 px-3 py-1 hover:bg-red-500 hover:text-white transition';
            btn.innerText = 'Eliminar';
            btn.title = 'Eliminar este item';
            btn.addEventListener('click', () => {
                row.remove();
                updateItemPlaceholders();
            });

            row.appendChild(input);
            row.appendChild(btn);

            return row;
        }

        function updateItemPlaceholders() {
            const inputs = document.querySelectorAll('#itemsContainer .item-input');
            inputs.forEach((input, i) => {
                input.placeholder = `Item ${i + 1}`;
            });
        }

        function toRoman(num) {
            const romanNumerals = [{
                    value: 1000,
                    symbol: 'M'
                },
                {
                    value: 900,
                    symbol: 'CM'
                },
                {
                    value: 500,
                    symbol: 'D'
                },
                {
                    value: 400,
                    symbol: 'CD'
                },
                {
                    value: 100,
                    symbol: 'C'
                },
                {
                    value: 90,
                    symbol: 'XC'
                },
                {
                    value: 50,
                    symbol: 'L'
                },
                {
                    value: 40,
                    symbol: 'XL'
                },
                {
                    value: 10,
                    symbol: 'X'
                },
                {
                    value: 9,
                    symbol: 'IX'
                },
                {
                    value: 5,
                    symbol: 'V'
                },
                {
                    value: 4,
                    symbol: 'IV'
                },
                {
                    value: 1,
                    symbol: 'I'
                }
            ];

            let result = '';
            for (const numeral of romanNumerals) {
                while (num >= numeral.value) {
                    result += numeral.symbol;
                    num -= numeral.value;
                }
            }
            return result;
        }

        function formatItems(itemsArray, formatType) {
            return itemsArray.map((item, i) => {
                switch (formatType) {
                    case 'letters':
                        return `${String.fromCharCode(97 + i)}) ${item}`;
                    case 'numbers':
                        return `${i + 1}) ${item}`;
                    case 'roman':
                        return `${toRoman(i + 1)}) ${item}`;
                    default:
                        return `${String.fromCharCode(97 + i)}) ${item}`;
                }
            }).join('; ');
        }

        document.getElementById('addItemBtn').addEventListener('click', () => {
            const container = document.getElementById('itemsContainer');
            const newIndex = container.querySelectorAll('.item-input').length + 1;
            const newItem = createItemInput(newIndex);
            container.appendChild(newItem);
        });

        document.getElementById('docForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fecha = document.getElementById('fecha').value.trim();
            const hora = document.getElementById('hora').value.trim();
            const tipoEvento = document.getElementById('tipoEvento').value.trim();
            const proyecto = document.getElementById('proyecto').value.trim();
            const plantillaSeleccionada = document.getElementById('templateSelect').value;
            const formatoNumeracion = document.getElementById('formatSelect').value;

            if (!fecha || !hora || !tipoEvento || !proyecto) {
                alert('Por favor completa fecha, hora, tipo de evento y proyecto.');
                return;
            }

            const itemsInputs = document.querySelectorAll('.item-input');
            const itemsArray = [];
            for (const input of itemsInputs) {
                const val = input.value.trim();
                if (val) itemsArray.push(val);
            }
            if (itemsArray.length === 0) {
                alert('Por favor ingresa al menos un item.');
                return;
            }

            const itemsConcatenados = formatItems(itemsArray, formatoNumeracion);

            fetch(plantillaSeleccionada)
                .then(res => {
                    if (!res.ok) throw new Error('No se pudo cargar la plantilla seleccionada');
                    return res.arrayBuffer();
                })
                .then(content => {
                    try {
                        const zip = new PizZip(content);
                        const doc = new window.docxtemplater(zip, {
                            paragraphLoop: true,
                            linebreaks: true,
                            delimiters: {
                                start: '<<',
                                end: '>>'
                            },
                        });

                        const data = {
                            fecha,
                            hora,
                            tipoEvento,
                            proyecto,
                            items: itemsConcatenados
                        };
                        doc.render(data);

                        const out = doc.getZip().generate({
                            type: 'blob',
                            mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        });

                        saveAs(out, 'Declaración Jurada.docx');

                        Swal.fire({
                            icon: 'success',
                            title: 'POR FAVOR LEER',
                            text: 'Por favor verificar la EDAD del propietario y el número de HOJAS en la Declaración generada.',
                            confirmButtonText: 'Aceptar',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });

                    } catch (err) {
                        alert('Error al generar el documento: ' + err.message);
                        console.error(err);
                    }
                })
                .catch(err => {
                    alert('Error al cargar la plantilla: ' + err.message);
                    console.error(err);
                });
        });
    </script>

</body>

</html>
