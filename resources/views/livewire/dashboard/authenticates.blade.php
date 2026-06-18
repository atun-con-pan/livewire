<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Generar Autentica</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com">
    </script>

    <!-- Librerías para docxtemplater -->
    <script src="https://unpkg.com/pizzip@3.0.6/dist/pizzip.min.js">
    </script>
    <script src="https://unpkg.com/docxtemplater@3.22.2/build/docxtemplater.js">
    </script>
    <script src="https://unpkg.com/file-saver@2.0.5/dist/FileSaver.min.js">
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap');

        body {
            background: linear-gradient(145deg, #0b1120 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        /* Card principal con vidrio y borde sutil */
        .glass-card {
            background: rgba(30, 41, 59, 0.70);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(100, 116, 139, 0.25);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
            transition: all 0.2s ease;
            width: 100%;
            max-width: 560px;
        }

        /* Título con gradiente */
        .gradient-title {
            background: linear-gradient(135deg, #67e8f9 0%, #06b6d4 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Inputs & selects con estilo neumórfico sutil */
        .form-input {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: #f1f5f9;
            padding: 0.65rem 1rem;
            border-radius: 0.75rem;
            width: 100%;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            outline: none;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .form-input:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.2), inset 0 2px 4px rgba(0, 0, 0, 0.3);
            background-color: #1e293b;
        }

        .form-input::placeholder {
            color: #64748b;
            font-weight: 400;
            opacity: 0.8;
        }

        /* Select personalizado */
        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.75rem;
            background-size: 1.15rem;
        }

        /* Labels */
        .label-styled {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.45rem;
        }

        /* Items container scroll mejorado */
        #itemsContainer {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 0.25rem;
            scrollbar-width: thin;
            scrollbar-color: #22d3ee transparent;
        }

        #itemsContainer::-webkit-scrollbar {
            width: 5px;
        }

        #itemsContainer::-webkit-scrollbar-track {
            background: transparent;
        }

        #itemsContainer::-webkit-scrollbar-thumb {
            background: #22d3ee;
            border-radius: 20px;
        }

        /* Item row */
        .item-row {
            background: #1e293b;
            border-radius: 0.75rem;
            padding: 0.3rem 0.3rem 0.3rem 1rem;
            border: 1px solid #334155;
            transition: border 0.15s;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .item-row:hover {
            border-color: #475569;
        }

        .item-row .item-input {
            background: transparent;
            border: none;
            padding: 0.6rem 0;
            color: #f1f5f9;
            width: 100%;
            outline: none;
            font-size: 0.95rem;
        }

        .item-row .item-input::placeholder {
            color: #64748b;
            font-weight: 400;
        }

        /* Botón eliminar con icono */
        .btn-remove {
            background: transparent;
            border: none;
            color: #f87171;
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.15s;
            font-size: 1rem;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-remove:hover {
            background: rgba(248, 113, 113, 0.12);
            color: #fca5a5;
            transform: scale(1.05);
        }

        .btn-remove svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        /* Botón agregar item */
        .btn-add-item {
            background: transparent;
            border: 1px dashed #22d3ee;
            color: #22d3ee;
            padding: 0.6rem 1.2rem;
            border-radius: 2rem;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-add-item:hover {
            background: rgba(34, 211, 238, 0.08);
            border-color: #67e8f9;
            color: #67e8f9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 211, 238, 0.15);
        }

        /* Botón generar */
        .btn-generate {
            background: linear-gradient(135deg, #22d3ee 0%, #0891b2 100%);
            border: none;
            color: #0f172a;
            font-weight: 700;
            padding: 0.9rem 1.8rem;
            border-radius: 1rem;
            transition: all 0.25s ease;
            box-shadow: 0 8px 18px -6px rgba(34, 211, 238, 0.25);
            letter-spacing: 0.3px;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
        }

        .btn-generate:hover {
            transform: scale(1.01) translateY(-2px);
            box-shadow: 0 12px 28px -8px rgba(34, 211, 238, 0.4);
            background: linear-gradient(135deg, #67e8f9 0%, #06b6d4 100%);
        }

        .btn-generate:active {
            transform: scale(0.98);
        }

        /* Separador */
        .divider-custom {
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #475569, transparent);
            margin: 1.5rem 0;
        }

        /* Responsive */
        @media (max-width: 640px) {
            body {
                padding: 0.75rem;
            }
            .glass-card {
                padding: 1.5rem !important;
            }
            .item-row {
                padding: 0.2rem 0.2rem 0.2rem 0.8rem;
            }
        }

        /* Animación sutil para items */
        .item-row {
            animation: fadeSlide 0.2s ease-out;
        }

        @keyframes fadeSlide {
            0% {
                opacity: 0;
                transform: translateY(-6px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Badge contador */
        .badge-counter {
            background: rgba(34, 211, 238, 0.12);
            color: #67e8f9;
            padding: 0.2rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            border: 1px solid rgba(34, 211, 238, 0.15);
        }
    </style>
</head>

<body>

    <main class="w-full max-w-lg mx-auto">
        <div class="glass-card rounded-3xl p-6 md:p-10">

            <h3 class="text-4xl md:text-5xl font-extrabold text-center mb-2 gradient-title tracking-tight">
                Autenticas
            </h3>
            <p class="text-center text-slate-400 text-sm mb-8 font-medium tracking-wide">Completa los campos y genera tu documento</p>

            <form id="autenticaForm" class="space-y-6">

                <!-- Fecha -->
                <div>
                    <label for="fecha" class="label-styled">Fecha</label>
                    <input type="text" id="fecha" name="fecha" required class="form-input"
                        placeholder="Ej: veintitrés (23) de mayo de dos mil veinticinco (2025)" />
                </div>

                <!-- Cantidad de Items -->
                <div>
                    <label for="cantidad" class="label-styled">Cantidad de Items</label>
                    <select id="cantidad" name="cantidad" class="form-input">
                        <option value="1">Una</option>
                        <option value="2">Varias</option>
                    </select>
                </div>

                <!-- Items -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="label-styled mb-0">Ítems</span>
                        <span class="badge-counter" id="itemCounter">1 item</span>
                    </div>
                    <div id="itemsContainer" class="space-y-2.5 mb-3">
                        <div class="item-row">
                            <input type="text" name="items[]" class="item-input" placeholder="Item 1" required />
                            <button type="button" class="btn-remove" title="Eliminar este item" style="visibility:hidden;">
                                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="addItemBtn" class="btn-add-item hidden">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Agregar ítem
                    </button>
                </div>

                <hr class="divider-custom" />

                <!-- Submit -->
                <button type="submit" class="btn-generate">
                    Generar Documento
                </button>
            </form>
        </div>
    </main>

    <script>
        (function() {
            const cantidadSelect = document.getElementById('cantidad');
            const addItemBtn = document.getElementById('addItemBtn');
            const itemsContainer = document.getElementById('itemsContainer');
            const itemCounter = document.getElementById('itemCounter');

            // ---------- FUNCIONES ----------
            function createItemInput(placeholder) {
                const row = document.createElement('div');
                row.className = 'item-row';

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'items[]';
                input.className = 'item-input';
                input.placeholder = placeholder || 'Item';
                input.required = true;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn-remove';
                btn.title = 'Eliminar este item';
                btn.innerHTML = `<svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>`;
                btn.addEventListener('click', () => {
                    row.remove();
                    updatePlaceholdersAndCounter();
                });

                row.appendChild(input);
                row.appendChild(btn);
                return row;
            }

            function updatePlaceholdersAndCounter() {
                const rows = itemsContainer.querySelectorAll('.item-row');
                const inputs = itemsContainer.querySelectorAll('.item-input');

                // Actualizar placeholders
                inputs.forEach((input, i) => {
                    input.placeholder = `Item ${i + 1}`;
                });

                // Actualizar contador
                if (itemCounter) {
                    itemCounter.textContent = `${inputs.length} item${inputs.length !== 1 ? 's' : ''}`;
                }

                // Mostrar/ocultar botón eliminar
                rows.forEach((row, idx) => {
                    const btn = row.querySelector('.btn-remove');
                    if (btn) {
                        btn.style.visibility = (rows.length > 1) ? 'visible' : 'hidden';
                    }
                });

                // Mostrar/ocultar botón agregar según cantidad
                const showAddBtn = cantidadSelect.value === '2';
                addItemBtn.classList.toggle('hidden', !showAddBtn);
            }

            function resetToSingleItem() {
                // Eliminar todos los items excepto el primero
                const rows = itemsContainer.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    if (index > 0) row.remove();
                });
                // Limpiar el valor del primer input
                const firstInput = itemsContainer.querySelector('.item-input');
                if (firstInput) firstInput.value = '';
                updatePlaceholdersAndCounter();
            }

            // ---------- EVENTOS ----------
            cantidadSelect.addEventListener('change', function() {
                if (this.value === '1') {
                    resetToSingleItem();
                } else {
                    // Mostrar botón agregar
                    addItemBtn.classList.remove('hidden');
                    // Si solo hay un item, añadir uno más para que se vea que se pueden agregar varios
                    const currentRows = itemsContainer.querySelectorAll('.item-row');
                    if (currentRows.length === 1) {
                        const newRow = createItemInput('Item 2');
                        itemsContainer.appendChild(newRow);
                        updatePlaceholdersAndCounter();
                    } else {
                        updatePlaceholdersAndCounter();
                    }
                }
            });

            addItemBtn.addEventListener('click', () => {
                const count = itemsContainer.querySelectorAll('.item-row').length + 1;
                const newRow = createItemInput(`Item ${count}`);
                itemsContainer.appendChild(newRow);
                updatePlaceholdersAndCounter();
                // Scroll al final
                itemsContainer.scrollTop = itemsContainer.scrollHeight;
            });

            // ---------- SUBMIT ----------
            document.getElementById('autenticaForm').addEventListener('submit', async (e) => {
                e.preventDefault();

                const form = e.target;
                const fecha = form.fecha.value.trim();
                const cantidadNum = parseInt(form.cantidad.value);
                const items = Array.from(form.querySelectorAll('input[name="items[]"]'))
                    .map(i => i.value.trim())
                    .filter(v => v !== '');

                if (!fecha) {
                    alert('Por favor ingresa una fecha válida.');
                    return;
                }

                if (items.length === 0) {
                    alert('Por favor ingresa al menos un ítem.');
                    return;
                }

                const textoCantidad = cantidadNum === 1 ?
                    'la hoja de fotocopia que antecede, es auténtica por haber sido reproducida de su original en mi presencia el día de hoy' :
                    'las hojas de fotocopia que anteceden, son auténticas por haber sido reproducidas de su original en mi presencia el día de hoy';

                const textoCantidades = cantidadNum === 1 ?
                    'la hoja de fotocopia que antecede' :
                    'las hojas de fotocopia que anteceden';

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
                    items: textoItems
                };

                try {
                    const blob = await createDocFromTemplate(data);
                    window.saveAs(blob, 'Autentica_generado.docx');
                } catch (err) {
                    alert('Error generando el documento.');
                    console.error(err);
                }
            });

            // ---------- FUNCIÓN PARA CREAR DOC ----------
            function createDocFromTemplate(data) {
                return fetch('/templates/Autentica.docx')
                    .then(res => {
                        if (!res.ok) throw new Error('No se pudo cargar la plantilla');
                        return res.arrayBuffer();
                    })
                    .then(content => {
                        const zip = new PizZip(content);
                        const doc = new window.docxtemplater(zip, {
                            delimiters: { start: '<<', end: '>>' }
                        });
                        doc.setData(data);
                        doc.render();
                        return doc.getZip().generate({ type: "blob" });
                    });
            }

            // Inicializar estado
            updatePlaceholdersAndCounter();

            // Si la cantidad inicial es "Varias", mostrar el botón y tener al menos 2 items
            if (cantidadSelect.value === '2') {
                addItemBtn.classList.remove('hidden');
                const currentRows = itemsContainer.querySelectorAll('.item-row');
                if (currentRows.length === 1) {
                    const newRow = createItemInput('Item 2');
                    itemsContainer.appendChild(newRow);
                    updatePlaceholdersAndCounter();
                }
            }
        })();
    </script>

</body>

</html>