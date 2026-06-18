<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Generar Informes</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com">
    </script>
    <script src="https://unpkg.com/pdf-lib/dist/pdf-lib.min.js">
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

        /* Subtítulo */
        .subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.3px;
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
            cursor: pointer;
        }

        /* File input personalizado */
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background-color: #1e293b;
            border: 2px dashed #334155;
            border-radius: 0.75rem;
            padding: 1.5rem 1rem;
            color: #94a3b8;
            font-weight: 500;
            transition: all 0.2s ease;
            text-align: center;
            min-height: 80px;
        }

        .file-input-label:hover {
            border-color: #22d3ee;
            background-color: rgba(34, 211, 238, 0.05);
            color: #e2e8f0;
        }

        .file-input-label.has-files {
            border-color: #22d3ee;
            border-style: solid;
            background-color: rgba(34, 211, 238, 0.08);
            color: #67e8f9;
        }

        .file-input-label svg {
            width: 28px;
            height: 28px;
            stroke: #64748b;
            flex-shrink: 0;
        }

        .file-input-label.has-files svg {
            stroke: #22d3ee;
        }

        .file-count-badge {
            background: rgba(34, 211, 238, 0.15);
            color: #67e8f9;
            padding: 0.2rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid rgba(34, 211, 238, 0.2);
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

        .btn-generate:hover:not(:disabled) {
            transform: scale(1.01) translateY(-2px);
            box-shadow: 0 12px 28px -8px rgba(34, 211, 238, 0.4);
            background: linear-gradient(135deg, #67e8f9 0%, #06b6d4 100%);
        }

        .btn-generate:active:not(:disabled) {
            transform: scale(0.98);
        }

        .btn-generate:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Separador */
        .divider-custom {
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #475569, transparent);
            margin: 1.5rem 0;
        }

        /* Progress bar mejorada */
        #progressContainer {
            margin-top: 1.5rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #94a3b8;
        }

        .progress-track {
            width: 100%;
            background-color: #1e293b;
            border-radius: 9999px;
            height: 0.75rem;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
            border: 1px solid #334155;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #22d3ee, #06b6d4);
            border-radius: 9999px;
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 640px) {
            body {
                padding: 0.75rem;
            }
            .glass-card {
                padding: 1.5rem !important;
            }
            .file-input-label {
                padding: 1rem;
                min-height: 60px;
                font-size: 0.9rem;
            }
        }

        /* Animación de pulso para el botón cuando está procesando */
        @keyframes pulse-glow {
            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(34, 211, 238, 0.3);
            }
            50% {
                box-shadow: 0 0 20px 6px rgba(34, 211, 238, 0.15);
            }
        }

        .btn-generate.processing {
            animation: pulse-glow 1.5s ease-in-out infinite;
        }

        /* Estilo para la descripción de la plantilla */
        .template-description {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.25rem;
            font-weight: 400;
        }
    </style>
</head>

<body>

    <main class="w-full max-w-lg mx-auto">
        <div class="glass-card rounded-3xl p-6 md:p-10">

            <h3 class="text-4xl md:text-5xl font-extrabold text-center mb-2 gradient-title tracking-tight">
                Informes PDF
            </h3>
            <p class="text-center text-slate-400 text-sm mb-8 font-medium tracking-wide">
                Genera informes fotográficos con tus imágenes
            </p>

            <form id="pdfForm" class="space-y-6">

                <!-- Plantilla -->
                <div>
                    <label for="templateSelect" class="label-styled">Selecciona plantilla</label>
                    <select id="templateSelect" class="form-input">
                        <option value="carta">📄 Tamaño Carta (4 fotos 2×2)</option>
                        <option value="oficio">📄 Tamaño Oficio (6 fotos 2×3)</option>
                        <option value="1x2">📄 Tamaño Carta (2 fotos 1×2)</option>
                        <option value="oficio1x3">📄 Tamaño Oficio (3 fotos 1×3)</option>
                    </select>
                    <p id="templateDescription" class="template-description">
                        Distribución: 2 columnas × 2 filas
                    </p>
                </div>

                <!-- Carga de imágenes -->
                <div>
                    <label class="label-styled">Carga imágenes (múltiples)</label>
                    <div class="file-input-wrapper">
                        <input
                        type="file"
                        id="inputImages"
                        accept="image/*"
                        multiple
                        />
                        <div id="fileInputLabel" class="file-input-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                            <span id="fileLabelText">Selecciona imágenes o arrastra aquí</span>
                            <span id="fileCountBadge" class="file-count-badge" style="display:none;">0</span>
                        </div>
                    </div>
                    <p id="fileList" class="text-xs text-slate-500 mt-2 truncate"></p>
                </div>

                <hr class="divider-custom" />

                <!-- Botón Generar -->
                <button type="submit" id="generate" class="btn-generate">
                    Generar PDF
                </button>

                <!-- Progress -->
                <div id="progressContainer" style="display:none;">
                    <div class="progress-label">
                        <span>Procesando imágenes...</span>
                        <span id="progressText">0%</span>
                    </div>
                    <div class="progress-track">
                        <div id="progressBar" class="progress-fill" style="width:0%;"></div>
                    </div>
                </div>

            </form>
        </div>
    </main>

    <script>
        (function() {
            const { PDFDocument } = PDFLib;

            const templates = {
                carta: 'templates/plantilla.pdf',
                oficio: 'templates/plantilla_oficio.pdf',
                '1x2': 'templates/plantilla1x2.pdf',
                'oficio1x3': 'templates/plantilla_oficio1x3.pdf'
            };

            const layouts = {
                carta: { headerHeight: 100, footerHeight: 50, cols: 2, rows: 2, label: '2×2', size: 'carta' },
                oficio: { headerHeight: 120, footerHeight: 60, cols: 2, rows: 3, label: '2×3', size: 'oficio' },
                '1x2': { headerHeight: 100, footerHeight: 50, cols: 1, rows: 2, label: '1×2', size: 'carta' },
                'oficio1x3': { headerHeight: 120, footerHeight: 60, cols: 1, rows: 3, label: '1×3', size: 'oficio' }
            };

            const descriptions = {
                carta: 'Distribución: 2 columnas × 2 filas (4 fotos por página) - Tamaño Carta',
                oficio: 'Distribución: 2 columnas × 3 filas (6 fotos por página) - Tamaño Oficio',
                '1x2': 'Distribución: 1 columna × 2 filas (2 fotos por página) - Tamaño Carta',
                'oficio1x3': 'Distribución: 1 columna × 3 filas (3 fotos por página) - Tamaño Oficio'
            };

            // Elementos DOM
            const fileInput = document.getElementById('inputImages');
            const fileLabel = document.getElementById('fileInputLabel');
            const fileLabelText = document.getElementById('fileLabelText');
            const fileCountBadge = document.getElementById('fileCountBadge');
            const fileList = document.getElementById('fileList');
            const generateBtn = document.getElementById('generate');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const templateSelect = document.getElementById('templateSelect');
            const templateDescription = document.getElementById('templateDescription');

            // ---------- ACTUALIZAR DESCRIPCIÓN ----------
            templateSelect.addEventListener('change', function() {
                const value = this.value;
                templateDescription.textContent = descriptions[value] || 'Selecciona una plantilla';
            });

            // ---------- MANEJO DE ARCHIVOS ----------
            fileInput.addEventListener('change', function() {
                const files = this.files;
                const count = files.length;

                if (count > 0) {
                    fileLabel.classList.add('has-files');
                    fileLabelText.textContent = `${count} imagen${count > 1 ? 'es' : ''} seleccionada${count > 1 ? 's' : ''}`;
                    fileCountBadge.style.display = 'inline-block';
                    fileCountBadge.textContent = count;

                    const names = Array.from(files).map(f => f.name).join(', ');
                    fileList.textContent = names.length > 60 ? names.substring(0, 60) + '...' : names;
                } else {
                    fileLabel.classList.remove('has-files');
                    fileLabelText.textContent = 'Selecciona imágenes o arrastra aquí';
                    fileCountBadge.style.display = 'none';
                    fileList.textContent = '';
                }
            });

            // ---------- DRAG & DROP ----------
            const dropZone = fileLabel;
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            dropZone.addEventListener('dragover', () => {
                dropZone.classList.add('border-cyan-400', 'bg-cyan-400/5');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-cyan-400', 'bg-cyan-400/5');
            });

            dropZone.addEventListener('drop', (e) => {
                dropZone.classList.remove('border-cyan-400', 'bg-cyan-400/5');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });

            // ---------- GENERAR PDF ----------
            document.getElementById('pdfForm').addEventListener('submit', async (e) => {
                e.preventDefault();

                const plantillaSeleccionada = templateSelect.value;
                const imageFiles = Array.from(fileInput.files);

                if (imageFiles.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin imágenes',
                        text: 'Por favor selecciona al menos una imagen.',
                        confirmButtonColor: '#22d3ee',
                        background: '#1e293b',
                        color: '#f1f5f9',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                // Validar tipos de archivo
                const invalidFiles = imageFiles.filter(f =>
                    !['image/jpeg', 'image/jpg', 'image/png'].includes(f.type)
                );
                if (invalidFiles.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Formato no soportado',
                        text: `Los siguientes archivos no son JPG o PNG: ${invalidFiles.map(f => f.name).join(', ')}`,
                        confirmButtonColor: '#f87171',
                        background: '#1e293b',
                        color: '#f1f5f9'
                    });
                    return;
                }

                generateBtn.disabled = true;
                generateBtn.classList.add('processing');
                generateBtn.textContent = 'Generando...';
                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';
                progressText.textContent = '0%';

                let pdfBytes;
                try {
                    const response = await fetch(templates[plantillaSeleccionada]);
                    if (!response.ok) throw new Error('No se pudo cargar la plantilla PDF');
                    pdfBytes = await response.arrayBuffer();
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error cargando plantilla: ' + e.message,
                        confirmButtonColor: '#f87171',
                        background: '#1e293b',
                        color: '#f1f5f9'
                    });
                    resetUI();
                    return;
                }

                try {
                    const pdfDoc = await PDFDocument.load(pdfBytes);
                    const { headerHeight, footerHeight, cols, rows } = layouts[plantillaSeleccionada];
                    const templatePage = pdfDoc.getPage(0);
                    const { width, height } = templatePage.getSize();

                    const margin = 20;
                    const usableHeight = height - headerHeight - footerHeight;
                    const imageWidth = (width - margin * (cols + 1)) / cols;
                    const imageHeight = (usableHeight - margin * (rows + 1)) / rows;
                    const fotosPorPagina = cols * rows;

                    let processed = 0;
                    const total = imageFiles.length;

                    for (let i = 0; i < imageFiles.length; i += fotosPorPagina) {
                        const [templatePageCopied] = await pdfDoc.copyPages(pdfDoc, [0]);
                        const page = pdfDoc.addPage(templatePageCopied);

                        for (let j = 0; j < fotosPorPagina; j++) {
                            const imgIndex = i + j;
                            if (imgIndex >= imageFiles.length) break;

                            const imgFile = imageFiles[imgIndex];
                            const imgBytes = await imgFile.arrayBuffer();

                            let img;
                            if (imgFile.type === 'image/jpeg' || imgFile.type === 'image/jpg') {
                                img = await pdfDoc.embedJpg(imgBytes);
                            } else if (imgFile.type === 'image/png') {
                                img = await pdfDoc.embedPng(imgBytes);
                            } else {
                                continue;
                            }

                            const col = j % cols;
                            const row = Math.floor(j / cols);
                            const x = margin + col * (imageWidth + margin);
                            const y = height - headerHeight - margin - (row + 1) * imageHeight - row * margin;

                            page.drawImage(img, { x, y, width: imageWidth, height: imageHeight });
                            processed++;
                        }

                        const progressPercent = Math.min(100, Math.round((processed / total) * 100));
                        progressBar.style.width = progressPercent + '%';
                        progressText.textContent = progressPercent + '%';

                        await new Promise(resolve => setTimeout(resolve, 50));
                    }

                    pdfDoc.removePage(0);
                    const pdfFinal = await pdfDoc.save();
                    const blob = new Blob([pdfFinal], { type: 'application/pdf' });
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = 'Informe Fotográfico.pdf';
                    a.click();

                    progressBar.style.width = '100%';
                    progressText.textContent = '¡Completado!';

                    Swal.fire({
                        icon: 'success',
                        title: 'PDF generado exitosamente',
                        text: `Se procesaron ${total} imagen${total > 1 ? 'es' : ''} con plantilla ${layouts[plantillaSeleccionada].label} (${layouts[plantillaSeleccionada].size})`,
                        confirmButtonColor: '#22d3ee',
                        background: '#1e293b',
                        color: '#f1f5f9',
                        timer: 2500,
                        timerProgressBar: true
                    });

                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al generar el PDF',
                        text: err.message || 'Ocurrió un error inesperado',
                        confirmButtonColor: '#f87171',
                        background: '#1e293b',
                        color: '#f1f5f9'
                    });
                }

                resetUI();
            });

            function resetUI() {
                generateBtn.disabled = false;
                generateBtn.classList.remove('processing');
                generateBtn.textContent = 'Generar PDF';
                setTimeout(() => {
                    progressContainer.style.display = 'none';
                    progressBar.style.width = '0%';
                    progressText.textContent = '0%';
                }, 800);
            }

            // Si no hay SweetAlert2, cargarlo
            if (typeof Swal === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                document.head.appendChild(script);
            }

            // Inicializar descripción
            templateDescription.textContent = descriptions['carta'];
        })();
    </script>

</body>

</html>