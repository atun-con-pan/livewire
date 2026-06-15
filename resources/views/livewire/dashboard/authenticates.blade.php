<!DOCTYPE html>
<html lang="es" class="min-h-screen bg-slate-900 text-white font-sans flex items-center justify-center">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Generar Autentica</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Librerías para docxtemplater -->
    <script src="https://unpkg.com/pizzip@3.0.6/dist/pizzip.min.js"></script>
    <script src="https://unpkg.com/docxtemplater@3.22.2/build/docxtemplater.js"></script>
    <script src="https://unpkg.com/file-saver@2.0.5/dist/FileSaver.min.js"></script>

    <script>
        function createDocFromTemplate(data) {
            return fetch('/templates/Autentica.docx')
                .then(res => res.arrayBuffer())
                .then(content => {
                    const zip = new PizZip(content);
                    const doc = new window.docxtemplater(zip, {
                        delimiters: {
                            start: '<<',
                            end: '>>'
                        }
                    });
                    doc.setData(data);
                    doc.render();
                    return doc.getZip().generate({ type: "blob" });
                });
        }
    </script>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen p-4">

    <form id="autenticaForm" class="bg-slate-800 rounded-lg shadow-lg p-8 max-w-lg w-full space-y-6">
        <h1 class="text-3xl font-bold mb-6 text-center">Generar documento de Autentica</h1>

        <div>
            <label class="block mb-2 font-semibold" for="fecha">Fecha</label>
            <input type="text" id="fecha" name="fecha" required
                class="w-full rounded px-3 py-2 bg-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-cyan-400" />
        </div>

        <div>
            <label class="block mb-2 font-semibold" for="cantidad">Cantidad de Items</label>
            <select id="cantidad" name="cantidad"
                class="w-full rounded px-3 py-2 bg-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-cyan-400">
                <option value="1">Una</option>
                <option value="2">Varias</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 font-semibold">Ítems</label>
            <div id="items" class="space-y-2">
                <input type="text" name="items[]" required
                    class="w-full rounded px-3 py-2 bg-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-cyan-400" />
            </div>
            <button type="button" id="addItemBtn"
                class="mt-2 bg-cyan-600 hover:bg-cyan-700 px-4 py-2 rounded font-semibold hidden">Agregar ítem</button>
        </div>

        <button type="submit"
            class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded font-bold text-white transition w-full">Generar
            Documento</button>
    </form>

    <script>
        const cantidadSelect = document.getElementById('cantidad');
        const addItemBtn = document.getElementById('addItemBtn');
        const itemsDiv = document.getElementById('items');

        function updateItemsInput() {
            if (cantidadSelect.value === '2') {
                // Mostrar botón agregar ítem
                addItemBtn.classList.remove('hidden');
            } else {
                // Ocultar botón agregar ítem
                addItemBtn.classList.add('hidden');
                // Dejar solo 1 input
                const inputs = itemsDiv.querySelectorAll('input[name="items[]"]');
                inputs.forEach((input, index) => {
                    if (index > 0) input.remove();
                });
            }
        }

        cantidadSelect.addEventListener('change', updateItemsInput);

        addItemBtn.addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'items[]';
            input.required = true;
            input.className = 'w-full rounded px-3 py-2 bg-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-cyan-400';
            itemsDiv.appendChild(input);
        });

        // Inicializar estado inicial
        updateItemsInput();

        document.getElementById('autenticaForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const form = e.target;
            const fecha = form.fecha.value.trim();
            const cantidadNum = parseInt(form.cantidad.value);
            const items = Array.from(form.querySelectorAll('input[name="items[]"]'))
                .map(i => i.value.trim())
                .filter(v => v !== '');

            if (!fecha || items.length === 0) {
                alert('Por favor ingresa fecha e ítems válidos.');
                return;
            }

            const textoCantidad = cantidadNum === 1
                ? 'la hoja de fotocopia que antecede, es auténtica por haber sido reproducida de su original en mi presencia el día de hoy'
                : 'las hojas de fotocopia que anteceden, son auténticas por haber sido reproducidas de su original en mi presencia el día de hoy';

            const textoCantidades = cantidadNum === 1
                ? 'la hoja de fotocopia que antecede'
                : 'las hojas de fotocopia que anteceden';

            let textoItems = '';
            if (items.length === 1) {
                textoItems = items[0];
            } else {
                const letras = 'abcdefghijklmnopqrstuvwxyz';
                textoItems = items.map((item, i) => `${letras[i]}) ${item}`).join('; ');
            }

            const data = {
                fecha: fecha,
                cantidad: textoCantidad,
                cantidades: textoCantidades,
                items: textoItems  // Se respeta el nombre exacto que dijiste: <<items>>
            };

            try {
                const blob = await createDocFromTemplate(data);
                window.saveAs(blob, 'Autentica_generado.docx');
            } catch (err) {
                alert('Error generando el documento.');
                console.error(err);
            }
        });
    </script>

</body>
</html>