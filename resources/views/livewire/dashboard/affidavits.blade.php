<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex" />
    <title>Declaraciones Juradas</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/x-icon" />
    <script src="https://cdn.jsdelivr.net/npm/pizzip@3.0.6/dist/pizzip.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/docxtemplater@3.36.0/build/docxtemplater.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    </script>
    <script src="https://cdn.tailwindcss.com">
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
            max-height: 260px;
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
    </style>
</head>

<body>

    <main class="w-full max-w-3xl mx-auto">
        <div class="glass-card rounded-3xl p-6 md:p-10">

            <h3 class="text-4xl md:text-5xl font-extrabold text-center mb-2 gradient-title tracking-tight">
                Declaraciones Juradas
            </h3>
            <p class="text-center text-slate-400 text-sm mb-8 font-medium tracking-wide">Completa los campos y genera tu documento</p>

            <form id="docForm" class="space-y-6">

                <!-- Plantilla -->
                <div>
                    <label for="templateSelect" class="label-styled">Selecciona plantilla</label>
                    <select id="templateSelect" class="form-input" required>
                        <option value="templates/template.docx" selected>Declaración Jurada Dipconsa</option>
                        <option value="templates/template2.docx">Declaración Jurada Construtotales</option>
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label for="fecha" class="label-styled">Fecha</label>
                    <input type="text" id="fecha" class="form-input" required
                        placeholder="uno (1) de enero de dos mil veinticinco (2025)" />
                </div>

                <!-- Hora -->
                <div>
                    <label for="hora" class="label-styled">Hora</label>
                    <input type="text" id="hora" class="form-input" required
                        placeholder="ocho horas con treinta minutos (08:30)" />
                </div>

                <!-- Tipo evento -->
                <div>
                    <label for="tipoEvento" class="label-styled">Tipo de evento (Cotización/Licitación)</label>
                    <input type="text" id="tipoEvento" class="form-input" required
                        placeholder="Ej: Cotización" />
                </div>

                <!-- Proyecto -->
                <div>
                    <label for="proyecto" class="label-styled">Proyecto</label>
                    <input type="text" id="proyecto" class="form-input" required
                        placeholder="Proyecto XYZ" />
                </div>

                <hr class="divider-custom" />

                <!-- Formato numeración -->
                <div>
                    <label for="formatSelect" class="label-styled">Formato de numeración</label>
                    <select id="formatSelect" class="form-input" required>
                        <option value="letters">Letras (a), b), c), etc.</option>
                        <option value="numbers">Números (1), 2), 3), etc.</option>
                        <option value="roman">Números romanos (I), II), III), etc.</option>
                    </select>
                </div>

                <!-- Items -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="label-styled mb-0">Items</span>
                        <span class="text-xs text-slate-500 font-medium" id="itemCounter">0 items</span>
                    </div>
                    <div id="itemsContainer" class="space-y-2.5 mb-3">
                        <div class="item-row">
                            <input type="text" class="item-input" placeholder="Item 1" required />
                            <button type="button" class="btn-remove" title="Eliminar este item" style="visibility:hidden;">
                                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="addItemBtn" class="btn-add-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Agregar item
                    </button>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-generate mt-4">
                    Generar documento
                </button>
            </form>
        </div>
    </main>

    <script>
        (function() {
            // ---------- FUNCIONES AUXILIARES ----------
            function createItemInput(index) {
                const row = document.createElement('div');
                row.className = 'item-row';

                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'item-input';
                input.placeholder = `Item ${index}`;
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
                    updateItemPlaceholders();
                    updateCounter();
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
                // Mostrar/ocultar botón eliminar (siempre mostrar a partir del segundo)
                const rows = document.querySelectorAll('#itemsContainer .item-row');
                rows.forEach((row, idx) => {
                    const btn = row.querySelector('.btn-remove');
                    if (btn) {
                        btn.style.visibility = (rows.length > 1) ? 'visible' : 'hidden';
                    }
                });
            }

            function updateCounter() {
                const inputs = document.querySelectorAll('#itemsContainer .item-input');
                const counter = document.getElementById('itemCounter');
                if (counter) {
                    counter.textContent = `${inputs.length} items`;
                }
            }

            // ---------- ROMAN ----------
            function toRoman(num) {
                const romanNumerals = [
                    { value: 1000, symbol: 'M' },
                    { value: 900, symbol: 'CM' },
                    { value: 500, symbol: 'D' },
                    { value: 400, symbol: 'CD' },
                    { value: 100, symbol: 'C' },
                    { value: 90, symbol: 'XC' },
                    { value: 50, symbol: 'L' },
                    { value: 40, symbol: 'XL' },
                    { value: 10, symbol: 'X' },
                    { value: 9, symbol: 'IX' },
                    { value: 5, symbol: 'V' },
                    { value: 4, symbol: 'IV' },
                    { value: 1, symbol: 'I' }
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

            // ---------- EVENTOS ----------
            const addBtn = document.getElementById('addItemBtn');
            addBtn.addEventListener('click', () => {
                const container = document.getElementById('itemsContainer');
                const count = container.querySelectorAll('.item-input').length + 1;
                const newItem = createItemInput(count);
                container.appendChild(newItem);
                updateItemPlaceholders();
                updateCounter();
                // scroll al final
                container.scrollTop = container.scrollHeight;
            });

            // Inicializar contador y visibilidad del botón eliminar
            updateItemPlaceholders();
            updateCounter();

            // ---------- SUBMIT ----------
            document.getElementById('docForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const fecha = document.getElementById('fecha').value.trim();
                const hora = document.getElementById('hora').value.trim();
                const tipoEvento = document.getElementById('tipoEvento').value.trim();
                const proyecto = document.getElementById('proyecto').value.trim();
                const plantillaSeleccionada = document.getElementById('templateSelect').value;
                const formatoNumeracion = document.getElementById('formatSelect').value;

                if (!fecha || !hora || !tipoEvento || !proyecto) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor completa fecha, hora, tipo de evento y proyecto.',
                        confirmButtonColor: '#22d3ee',
                        background: '#1e293b',
                        color: '#f1f5f9',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                const itemsInputs = document.querySelectorAll('.item-input');
                const itemsArray = [];
                for (const input of itemsInputs) {
                    const val = input.value.trim();
                    if (val) itemsArray.push(val);
                }
                if (itemsArray.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin items',
                        text: 'Por favor ingresa al menos un item.',
                        confirmButtonColor: '#22d3ee',
                        background: '#1e293b',
                        color: '#f1f5f9',
                        confirmButtonText: 'Ok'
                    });
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
                                delimiters: { start: '<<', end: '>>' },
                            });

                            const data = { fecha, hora, tipoEvento, proyecto, items: itemsConcatenados };
                            doc.render(data);

                            const out = doc.getZip().generate({
                                type: 'blob',
                                mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            });

                            saveAs(out, 'Declaración Jurada.docx');

                            Swal.fire({
                                icon: 'success',
                                title: 'Documento generado',
                                text: 'Por favor verificar la EDAD del propietario y el número de HOJAS en la Declaración generada.',
                                confirmButtonText: 'Aceptar',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonColor: '#22d3ee',
                                background: '#1e293b',
                                color: '#f1f5f9'
                            });

                        } catch (err) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al generar',
                                text: err.message,
                                confirmButtonColor: '#f87171',
                                background: '#1e293b',
                                color: '#f1f5f9'
                            });
                            console.error(err);
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al cargar plantilla',
                            text: err.message,
                            confirmButtonColor: '#f87171',
                            background: '#1e293b',
                            color: '#f1f5f9'
                        });
                        console.error(err);
                    });
            });
        })();
    </script>

</body>

</html>