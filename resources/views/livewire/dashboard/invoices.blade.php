<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Generar Factura Proforma</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/pizzip@3.0.6/dist/pizzip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docxtemplater@3.36.0/build/docxtemplater.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>

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

        .glass-card {
            background: rgba(30, 41, 59, 0.70);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(100, 116, 139, 0.25);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
            transition: all 0.2s ease;
            width: 100%;
            max-width: 700px;
        }

        .gradient-title {
            background: linear-gradient(135deg, #67e8f9 0%, #06b6d4 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

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
        }

        .form-input::placeholder {
            color: #64748b;
            font-weight: 400;
            opacity: 0.8;
        }

        .form-input[readonly] {
            cursor: default;
            opacity: 0.9;
        }

        .label-styled {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.45rem;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        #itemsContainer {
            max-height: 220px;
            overflow-y: auto;
            padding-right: 0.25rem;
            scrollbar-width: thin;
            scrollbar-color: #22d3ee transparent;
        }

        #itemsContainer::-webkit-scrollbar {
            width: 5px;
        }

        #itemsContainer::-webkit-scrollbar-thumb {
            background: #22d3ee;
            border-radius: 20px;
        }

        .item-row {
            background: #1e293b;
            border-radius: 0.75rem;
            padding: 0.3rem 0.3rem 0.3rem 1rem;
            border: 1px solid #334155;
            display: flex;
            align-items: center;
            gap: 0.6rem;
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
        }

        .item-row .item-input-desc {
            flex: 2;
        }

        .item-row .item-input-cantidad {
            width: 70px;
            text-align: center;
        }

        .item-row .item-input-precio {
            width: 100px;
            text-align: right;
        }

        .btn-remove {
            background: transparent;
            border: none;
            color: #f87171;
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-remove:hover {
            background: rgba(248, 113, 113, 0.12);
            color: #fca5a5;
        }

        .btn-remove svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

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

        .badge-counter {
            background: rgba(34, 211, 238, 0.12);
            color: #67e8f9;
            padding: 0.2rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            border: 1px solid rgba(34, 211, 238, 0.15);
        }

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

        .btn-generate:hover:not(:disabled) {
            transform: scale(1.01) translateY(-2px);
            box-shadow: 0 12px 28px -8px rgba(34, 211, 238, 0.4);
            background: linear-gradient(135deg, #67e8f9 0%, #06b6d4 100%);
        }

        .btn-generate:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .divider-custom {
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #475569, transparent);
            margin: 1.5rem 0;
        }

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

        @media (max-width: 640px) {
            body {
                padding: 0.75rem;
            }

            .glass-card {
                padding: 1.5rem !important;
            }

            .form-grid-2 {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .item-row .item-input-desc {
                flex: 1;
            }

            .item-row .item-input-cantidad {
                width: 55px;
            }

            .item-row .item-input-precio {
                width: 80px;
            }
        }
    </style>
</head>

<body>

    <main class="w-full max-w-2xl mx-auto">
        <div class="glass-card rounded-3xl p-6 md:p-10">

            <h3 class="text-4xl md:text-5xl font-extrabold text-center mb-2 gradient-title tracking-tight">
                Factura Proforma
            </h3>
            <p class="text-center text-slate-400 text-sm mb-8 font-medium tracking-wide">
                Genera facturas proforma profesionales en Word
            </p>

            <form id="facturaForm" class="space-y-6">

                <!-- Datos del cliente -->
                <div class="form-grid-2">
                    <div>
                        <label for="cliente" class="label-styled">Cliente</label>
                        <input type="text" id="cliente" class="form-input" required
                            placeholder="Nombre del cliente" />
                    </div>
                    <div>
                        <label for="nit" class="label-styled">NIT</label>
                        <input type="text" id="nit" class="form-input" required placeholder="Número de NIT" />
                    </div>
                    <div>
                        <label for="direccion" class="label-styled">Dirección</label>
                        <input type="text" id="direccion" class="form-input" required
                            placeholder="Dirección completa" />
                    </div>
                    <div>
                        <label for="fechaEmision" class="label-styled">Fecha de Emisión</label>
                        <input type="date" id="fechaEmision" class="form-input" required />
                    </div>
                </div>

                <hr class="divider-custom" />

                <!-- Items -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="label-styled mb-0">Productos / Servicios</span>
                        <span class="badge-counter" id="itemCounter">0 items</span>
                    </div>

                    <div
                        class="flex items-center gap-3 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                        <span class="flex-1">Descripción</span>
                        <span class="w-[70px] text-center">Cant.</span>
                        <span class="w-[100px] text-right">Precio</span>
                        <span class="w-10"></span>
                    </div>

                    <div id="itemsContainer" class="space-y-2.5 mb-3">
                        <div class="item-row">
                            <input type="text" class="item-input item-input-desc"
                                placeholder="Descripción del producto/servicio" required />
                            <input type="number" class="item-input item-input-cantidad" placeholder="1" min="1"
                                value="1" required />
                            <input type="number" class="item-input item-input-precio" placeholder="0.00" step="0.01"
                                min="0" required />
                            <button type="button" class="btn-remove" style="visibility:hidden;">
                                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="button" id="addItemBtn" class="btn-add-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Agregar producto
                    </button>
                </div>

                <!-- Totales -->
                <div>
                    <div class="form-grid-2">
                        <div>
                            <label for="total" class="label-styled">Total (Q)</label>
                            <input type="text" id="total" class="form-input font-bold text-cyan-400"
                                value="Q 0.00" readonly />
                        </div>
                        <div>
                            <label for="iva" class="label-styled">IVA (Q)</label>
                            <input type="text" id="iva" class="form-input" value="Q 0.00" readonly />
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div>
                    <label for="observaciones" class="label-styled">Observaciones (opcional)</label>
                    <textarea id="observaciones" rows="2" class="form-input" placeholder="Condiciones de pago, garantía, etc."></textarea>
                </div>

                <hr class="divider-custom" />

                <button type="submit" class="btn-generate">
                    Generar Factura Proforma
                </button>
            </form>
        </div>
    </main>

    <script>
        (function() {
            // ---------- CREAR ITEM ----------
            function createItemInput() {
                const row = document.createElement('div');
                row.className = 'item-row';

                const desc = document.createElement('input');
                desc.type = 'text';
                desc.className = 'item-input item-input-desc';
                desc.placeholder = 'Descripción del producto/servicio';
                desc.required = true;

                const cantidad = document.createElement('input');
                cantidad.type = 'number';
                cantidad.className = 'item-input item-input-cantidad';
                cantidad.placeholder = '1';
                cantidad.min = '1';
                cantidad.value = '1';
                cantidad.required = true;

                const precio = document.createElement('input');
                precio.type = 'number';
                precio.className = 'item-input item-input-precio';
                precio.placeholder = '0.00';
                precio.step = '0.01';
                precio.min = '0';
                precio.required = true;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn-remove';
                btn.title = 'Eliminar';
                btn.innerHTML = `<svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>`;
                btn.addEventListener('click', () => {
                    row.remove();
                    updateTotales();
                    updateCounter();
                    updateRemoveButtons();
                });

                // Event listeners para actualizar totales
                [cantidad, precio].forEach(input => {
                    input.addEventListener('input', updateTotales);
                });

                row.appendChild(desc);
                row.appendChild(cantidad);
                row.appendChild(precio);
                row.appendChild(btn);

                return row;
            }

            // ---------- ACTUALIZAR CONTADOR ----------
            function updateCounter() {
                const rows = document.querySelectorAll('#itemsContainer .item-row');
                const counter = document.getElementById('itemCounter');
                if (counter) {
                    counter.textContent = `${rows.length} item${rows.length !== 1 ? 's' : ''}`;
                }
            }

            // ---------- ACTUALIZAR BOTONES ELIMINAR ----------
            function updateRemoveButtons() {
                const rows = document.querySelectorAll('#itemsContainer .item-row');
                rows.forEach((row, idx) => {
                    const btn = row.querySelector('.btn-remove');
                    if (btn) {
                        btn.style.visibility = (rows.length > 1) ? 'visible' : 'hidden';
                    }
                });
            }

            // ---------- CALCULAR TOTALES ----------
            function updateTotales() {
                const rows = document.querySelectorAll('#itemsContainer .item-row');
                let total = 0;

                rows.forEach(row => {
                    const cantidad = parseFloat(row.querySelector('.item-input-cantidad').value) || 0;
                    const precio = parseFloat(row.querySelector('.item-input-precio').value) || 0;
                    total += cantidad * precio;
                });

                // Calcular IVA: Total / 1.12 * 0.12
                const iva = total / 1.12 * 0.12;

                document.getElementById('total').value = `Q ${total.toFixed(2)}`;
                document.getElementById('iva').value = `Q ${iva.toFixed(2)}`;
            }

            // ---------- EVENTOS ----------
            document.getElementById('addItemBtn').addEventListener('click', () => {
                const container = document.getElementById('itemsContainer');
                const newItem = createItemInput();
                container.appendChild(newItem);
                updateTotales();
                updateCounter();
                updateRemoveButtons();
                container.scrollTop = container.scrollHeight;
            });

            // ---------- SUBMIT ----------
            document.getElementById('facturaForm').addEventListener('submit', async (e) => {
                e.preventDefault();

                const cliente = document.getElementById('cliente').value.trim();
                const nit = document.getElementById('nit').value.trim();
                const direccion = document.getElementById('direccion').value.trim();
                const fechaEmision = document.getElementById('fechaEmision').value;
                const observaciones = document.getElementById('observaciones').value.trim();

                if (!cliente || !nit || !direccion || !fechaEmision) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor completa todos los campos obligatorios.',
                        confirmButtonColor: '#22d3ee',
                        background: '#1e293b',
                        color: '#f1f5f9'
                    });
                    return;
                }

                // Obtener items
                const rows = document.querySelectorAll('#itemsContainer .item-row');
                const itemsArray = [];
                let total = 0;

                rows.forEach(row => {
                    const desc = row.querySelector('.item-input-desc').value.trim();
                    const cantidad = parseFloat(row.querySelector('.item-input-cantidad').value) ||
                        0;
                    const precio = parseFloat(row.querySelector('.item-input-precio').value) || 0;

                    if (desc && cantidad > 0 && precio >= 0) {
                        itemsArray.push({
                            desc,
                            cantidad,
                            precio,
                            totalItem: cantidad * precio
                        });
                        total += cantidad * precio;
                    }
                });

                if (itemsArray.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin productos',
                        text: 'Por favor agrega al menos un producto o servicio.',
                        confirmButtonColor: '#22d3ee',
                        background: '#1e293b',
                        color: '#f1f5f9'
                    });
                    return;
                }

                const iva = total / 1.12 * 0.12;

                // Formatear fecha
                const fechaObj = new Date(fechaEmision);
                const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                // Crear tabla de items para el documento
                let tablaItems = '';
                itemsArray.forEach((item, index) => {
                    tablaItems +=
                        `${index + 1}\t${item.desc}\t${item.cantidad}\tQ ${item.precio.toFixed(2)}\tQ ${item.totalItem.toFixed(2)}\n`;
                });

                const data = {
                    cliente,
                    nit,
                    direccion,
                    fecha: fechaFormateada,
                    items: tablaItems,
                    total: `Q ${total.toFixed(2)}`,
                    iva: `Q ${iva.toFixed(2)}`,
                    observaciones: observaciones || 'Sin observaciones'
                };

                // Cargar plantilla y generar
                try {
                    const response = await fetch('templates/factura_proforma.docx');
                    if (!response.ok) throw new Error('No se pudo cargar la plantilla');

                    const content = await response.arrayBuffer();
                    const zip = new PizZip(content);
                    const doc = new window.docxtemplater(zip, {
                        paragraphLoop: true,
                        linebreaks: true,
                        delimiters: {
                            start: '<<',
                            end: '>>'
                        }
                    });

                    doc.render(data);

                    const out = doc.getZip().generate({
                        type: 'blob',
                        mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    });

                    saveAs(out, `Factura_Proforma_${fechaFormateada.replace(/\//g, '-')}.docx`);

                    Swal.fire({
                        icon: 'success',
                        title: 'Factura generada exitosamente',
                        text: `Se generó la factura para ${cliente} por Q ${total.toFixed(2)}`,
                        confirmButtonColor: '#22d3ee',
                        background: '#1e293b',
                        color: '#f1f5f9',
                        timer: 3000,
                        timerProgressBar: true
                    });

                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al generar',
                        text: err.message || 'Ocurrió un error inesperado',
                        confirmButtonColor: '#f87171',
                        background: '#1e293b',
                        color: '#f1f5f9'
                    });
                }
            });

            // ---------- INICIALIZAR ----------
            updateTotales();
            updateCounter();
            updateRemoveButtons();

            // ---------- FECHA POR DEFECTO ----------
            document.getElementById('fechaEmision').valueAsDate = new Date();

        })();
    </script>

</body>

</html>
