<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unir PDF - iLovePDF</title>
    <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
    <script src="https://unpkg.com/downloadjs@1.4.7"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Graphik', 'Arial', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Vista 1: Subida de archivos (EXACTA A LA IMAGEN) */
        .view-upload {
            width: 100%;
            max-width: 800px;
            padding: 60px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .header h1 {
            font-size: 42px;
            font-weight: 700;
            color: #000;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 18px;
            color: #5f6368;
            line-height: 1.5;
        }

        .upload-container {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 70px 50px;
            text-align: center;
            border: 2px dashed #dadce0;
            transition: all 0.3s;
        }

        .upload-container.dragover {
            border-color: #d93025;
            background-color: rgba(217, 48, 37, 0.05);
        }

        .upload-icon {
            font-size: 80px;
            color: #d93025;
            margin-bottom: 25px;
        }

        .upload-title {
            font-size: 24px;
            font-weight: 500;
            color: #202124;
            margin-bottom: 15px;
        }

        .upload-subtitle {
            font-size: 16px;
            color: #5f6368;
            margin-bottom: 35px;
        }

        .btn-select {
            width: 380px;
            height: 80px;
            margin-bottom: 12px;
            background-color: #d93025;
            color: white;
            border: none;
            border-radius: 15px;
            padding: 12px 32px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-select:hover {
            background-color: #c5221f;
        }

        .file-input {
            display: none;
        }

        .btn-merge {
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 30px;
            display: none;
        }

        .btn-merge:hover {
            background-color: #0d62d9;
        }

        .btn-merge:disabled {
            background-color: #dadce0;
            cursor: not-allowed;
        }

        /* Vista 2: Vista de Miniaturas en Grid (EXACTA A iLovePDF) */
        .view-thumbnails {
            width: 100%;
            max-width: 1200px;
            padding: 30px 20px;
            display: none;
            flex-direction: column;
        }

        .thumbnails-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #dadce0;
        }

        .thumbnails-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #202124;
        }

        .header-controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-back {
            background-color: transparent;
            color: #5f6368;
            border: 1px solid #dadce0;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background-color: #f8f9fa;
            border-color: #5f6368;
        }

        .btn-merge-thumbnails {
            background-color: #d93025;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-merge-thumbnails:hover {
            background-color: #c5221f;
        }

        .btn-merge-thumbnails:disabled {
            background-color: #dadce0;
            cursor: not-allowed;
        }

        /* Grid de miniaturas */
        .thumbnails-container {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .thumbnails-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            min-height: 400px;
        }

        /* Item de miniatura - EXACTO A iLovePDF */
        .thumbnail-item {
            background-color: #fff;
            border: 2px solid #e8eaed;
            border-radius: 8px;
            padding: 15px;
            cursor: move;
            transition: all 0.3s;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 250px;
            user-select: none;
            touch-action: none;
        }

        .thumbnail-item:hover {
            border-color: #d93025;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .thumbnail-item.dragging {
            opacity: 0.5;
            transform: scale(0.95);
            border: 2px dashed #d93025;
        }

        .thumbnail-item.drag-over {
            border: 2px dashed #1a73e8;
            background-color: rgba(26, 115, 232, 0.05);
            transform: scale(1.02);
        }

        .drag-ghost {
            position: fixed;
            z-index: 1000;
            opacity: 0.8;
            pointer-events: none;
            transform: rotate(5deg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Vista previa del PDF */
        .thumbnail-preview {
            width: 100%;
            height: 150px;
            background-color: #f5f5f5;
            border-radius: 4px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            pointer-events: none;
        }

        .thumbnail-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            pointer-events: none;
        }

        .pdf-page-count {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 10px;
            pointer-events: none;
        }

        .pdf-icon {
            font-size: 50px;
            color: #d93025;
            pointer-events: none;
        }

        /* Información del archivo */
        .thumbnail-info {
            width: 100%;
            text-align: center;
            pointer-events: none;
        }

        .thumbnail-name {
            font-size: 14px;
            font-weight: 500;
            color: #202124;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            pointer-events: none;
        }

        .thumbnail-size {
            font-size: 12px;
            color: #5f6368;
            pointer-events: none;
        }

        /* Controles de miniatura */
        .thumbnail-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: auto;
        }

        .thumbnail-item:hover .thumbnail-controls {
            opacity: 1;
        }

        .thumbnail-btn {
            background-color: white;
            border: 1px solid #dadce0;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            color: #5f6368;
            transition: all 0.3s;
            pointer-events: auto;
        }

        .thumbnail-btn:hover {
            background-color: #f8f9fa;
            color: #d93025;
            border-color: #d93025;
        }

        /* Número de orden */
        .thumbnail-order {
            position: absolute;
            top: -10px;
            left: -10px;
            background-color: #d93025;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            pointer-events: none;
        }

        /* Instrucciones */
        .thumbnails-instructions {
            text-align: center;
            margin-top: 20px;
            color: #5f6368;
            font-size: 14px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px dashed #dadce0;
        }

        /* Controles adicionales */
        .thumbnails-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dadce0;
        }

        .files-count {
            font-size: 14px;
            color: #5f6368;
        }

        .btn-clear-all {
            background-color: transparent;
            color: #d93025;
            border: 1px solid #d93025;
            border-radius: 4px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-clear-all:hover {
            background-color: rgba(217, 48, 37, 0.1);
        }

        /* Vista 3: Procesando */
        .view-processing {
            width: 100%;
            max-width: 800px;
            padding: 100px 20px;
            display: none;
            flex-direction: column;
            align-items: center;
        }

        .processing-container {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 60px;
            text-align: center;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #d93025;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: 0 auto 30px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .processing-title {
            font-size: 24px;
            font-weight: 500;
            color: #202124;
            margin-bottom: 15px;
        }

        .processing-subtitle {
            font-size: 16px;
            color: #5f6368;
            margin-bottom: 30px;
        }

        /* Vista 4: Resultado */
        .view-result {
            width: 100%;
            max-width: 800px;
            padding: 100px 20px;
            display: none;
            flex-direction: column;
            align-items: center;
        }

        .result-container {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 60px;
            text-align: center;
        }

        .result-icon {
            font-size: 80px;
            color: #34a853;
            margin-bottom: 25px;
        }

        .result-title {
            font-size: 28px;
            font-weight: 700;
            color: #202124;
            margin-bottom: 15px;
        }

        .result-info {
            font-size: 16px;
            color: #5f6368;
            margin-bottom: 30px;
        }

        .result-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-download {
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-download:hover {
            background-color: #0d62d9;
        }

        .btn-new {
            background-color: transparent;
            color: #5f6368;
            border: 1px solid #dadce0;
            border-radius: 4px;
            padding: 12px 32px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-new:hover {
            background-color: #f8f9fa;
            border-color: #5f6368;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .thumbnails-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .view-upload {
                padding: 30px 15px;
            }

            .upload-container {
                padding: 40px 20px;
            }

            .header h1 {
                font-size: 28px;
            }

            .header p {
                font-size: 16px;
            }

            .upload-title {
                font-size: 20px;
            }

            .thumbnails-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-controls {
                width: 100%;
                justify-content: center;
            }

            .thumbnails-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .thumbnail-item {
                height: 220px;
            }

            .thumbnail-preview {
                height: 130px;
            }

            .result-buttons {
                flex-direction: column;
            }

            .btn-download,
            .btn-new {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .thumbnails-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Vista 1: Subida de archivos (EXACTA A LA IMAGEN) -->
    <div class="view-upload" id="viewUpload">
        <div class="upload-container" id="uploadContainer">
            <div class="header">
                <h1>Unir archivos PDF</h1>
                <p>Une PDFs y ponlos en el orden que prefieras. ¡Rápido y fácil!</p>
            </div>
            <button class="btn-select" id="btnSelect">Seleccionar archivos PDF</button>
            <p class="upload-subtitle">o arrastra y suelta los PDF aquí</p>
        </div>

        <button class="btn-merge" id="btnMerge" disabled>Unir PDFs</button>
    </div>

    <!-- Vista 2: Vista de Miniaturas en Grid -->
    <div class="view-thumbnails" id="viewThumbnails">
        <div class="thumbnails-header">
            <h2>Ordenar archivos PDF</h2>
            <div class="header-controls">
                <button class="btn-back" id="btnBackThumbnails">Volver</button>
                <button class="btn-merge-thumbnails" id="btnMergeThumbnails" disabled>Unir PDFs</button>
            </div>
        </div>

        <div class="thumbnails-container">
            <div class="thumbnails-grid" id="thumbnailsGrid">
                <!-- Las miniaturas se agregarán aquí dinámicamente -->
            </div>

            <p class="thumbnails-instructions">
                <i class="fas fa-mouse-pointer"></i> Arrastra y suelta los PDFs para cambiar su orden.
                El PDF en la posición #1 será la primera página del documento combinado.
            </p>
        </div>

        <div class="thumbnails-controls">
            <div class="files-count" id="filesCount">0 archivos seleccionados</div>
            <button class="btn-clear-all" id="btnClearAll">Eliminar todos</button>
        </div>
    </div>

    <!-- Vista 3: Procesando -->
    <div class="view-processing" id="viewProcessing">
        <div class="processing-container">
            <div class="spinner"></div>
            <h2 class="processing-title">Uniendo PDFs</h2>
            <p class="processing-subtitle">Por favor espera mientras combinamos tus archivos PDF.</p>
        </div>
    </div>

    <!-- Vista 4: Resultado -->
    <div class="view-result" id="viewResult">
        <div class="result-container">
            <div class="result-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="result-title">¡PDFs unidos exitosamente!</h2>
            <p class="result-info">Tus archivos PDF han sido combinados en un solo documento.</p>

            <div class="result-buttons">
                <button class="btn-download" id="btnDownload">Descargar PDF</button>
                <button class="btn-new" id="btnNew">Unir más PDFs</button>
            </div>
        </div>
    </div>

    <input type="file" id="fileInput" class="file-input" accept=".pdf" multiple>

    <script>
        // Variables globales
        let pdfFiles = [];
        let mergedPdf = null;
        let dragSrcIndex = null;
        let dragSrcElement = null;
        let dragGhost = null;
        let isDragging = false;
        let dropTargetIndex = null;

        // Elementos DOM
        const viewUpload = document.getElementById('viewUpload');
        const viewThumbnails = document.getElementById('viewThumbnails');
        const viewProcessing = document.getElementById('viewProcessing');
        const viewResult = document.getElementById('viewResult');

        const uploadContainer = document.getElementById('uploadContainer');
        const fileInput = document.getElementById('fileInput');
        const btnSelect = document.getElementById('btnSelect');
        const btnMerge = document.getElementById('btnMerge');
        const btnBackThumbnails = document.getElementById('btnBackThumbnails');
        const thumbnailsGrid = document.getElementById('thumbnailsGrid');
        const btnMergeThumbnails = document.getElementById('btnMergeThumbnails');
        const btnClearAll = document.getElementById('btnClearAll');
        const filesCount = document.getElementById('filesCount');
        const btnDownload = document.getElementById('btnDownload');
        const btnNew = document.getElementById('btnNew');

        // Inicializar la aplicación
        document.addEventListener('DOMContentLoaded', initApp);

        function initApp() {
            // Configurar PDF.js
            pdfjsLib.GlobalWorkerOptions.workerSrc =
                'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

            // Event listeners para subir archivos
            btnSelect.addEventListener('click', () => fileInput.click());
            uploadContainer.addEventListener('click', (e) => {
                if (e.target === uploadContainer || e.target.classList.contains('upload-icon') ||
                    e.target.classList.contains('upload-title') || e.target.classList.contains('upload-subtitle')) {
                    fileInput.click();
                }
            });

            fileInput.setAttribute('multiple', 'true');
            fileInput.addEventListener('change', handleFileSelect);

            // Drag and drop para subida
            uploadContainer.addEventListener('dragover', handleDragOver);
            uploadContainer.addEventListener('dragleave', handleDragLeave);
            uploadContainer.addEventListener('drop', handleDrop);

            // Botones de navegación
            btnMerge.addEventListener('click', goToThumbnailsView);
            btnBackThumbnails.addEventListener('click', goToUploadView);
            btnClearAll.addEventListener('click', clearAllFiles);
            btnMergeThumbnails.addEventListener('click', mergePDFs);
            btnDownload.addEventListener('click', downloadMergedPDF);
            btnNew.addEventListener('click', resetApp);

            // Eventos globales de drag and drop
            initGlobalDragAndDrop();
        }

        // Manejar selección de archivos
        function handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                addFiles(files);
            }
        }

        // Agregar archivos a la lista
        async function addFiles(files) {
            let validFiles = 0;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Validar que sea PDF
                if (file.type !== 'application/pdf') {
                    alert(`"${file.name}" no es un archivo PDF válido. Solo se aceptan archivos PDF.`);
                    continue;
                }

                // Validar tamaño (30MB máximo)
                if (file.size > 30 * 1024 * 1024) {
                    alert(`"${file.name}" excede el límite de tamaño de 30MB.`);
                    continue;
                }

                // Verificar si el archivo ya existe
                const exists = pdfFiles.some(pdf => pdf.name === file.name && pdf.size === file.size);
                if (exists) {
                    alert(`"${file.name}" ya está en la lista.`);
                    continue;
                }

                // Obtener vista previa del PDF
                let thumbnailUrl = null;
                let pageCount = 0;

                try {
                    const arrayBuffer = await file.arrayBuffer();
                    const pdf = await pdfjsLib.getDocument({
                        data: arrayBuffer
                    }).promise;
                    pageCount = pdf.numPages;

                    // Obtener primera página para miniatura
                    const page = await pdf.getPage(1);
                    const viewport = page.getViewport({
                        scale: 0.3
                    });

                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;

                    thumbnailUrl = canvas.toDataURL();
                } catch (error) {
                    console.error('Error generando miniatura:', error);
                }

                // Agregar archivo a la lista
                pdfFiles.push({
                    id: Date.now() + i + Math.random(),
                    name: file.name,
                    size: formatFileSize(file.size),
                    fileObject: file,
                    arrayBuffer: null,
                    thumbnailUrl: thumbnailUrl,
                    pageCount: pageCount
                });

                validFiles++;
            }

            if (validFiles > 0) {
                updateUploadView();
                if (viewThumbnails.style.display === 'flex') {
                    renderThumbnails();
                }
            }
        }

        // Manejar drag and drop para subida
        function handleDragOver(e) {
            e.preventDefault();
            uploadContainer.classList.add('dragover');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            uploadContainer.classList.remove('dragover');
        }

        function handleDrop(e) {
            e.preventDefault();
            uploadContainer.classList.remove('dragover');

            const files = e.dataTransfer.files;
            addFiles(files);
        }

        // Actualizar vista de subida
        function updateUploadView() {
            btnMerge.disabled = pdfFiles.length < 2;
            btnMerge.style.display = pdfFiles.length >= 2 ? 'block' : 'none';

            const uploadTitle = uploadContainer.querySelector('.upload-title');
            const uploadSubtitle = uploadContainer.querySelector('.upload-subtitle');

            if (pdfFiles.length > 0) {
                uploadTitle.textContent = `${pdfFiles.length} archivo(s) PDF seleccionado(s)`;
                uploadSubtitle.textContent = 'Haz clic para agregar más archivos o arrastra y suelta';
            } else {
                uploadTitle.textContent = 'Seleccionar archivos PDF';
                uploadSubtitle.textContent = 'o arrastra y suelta los PDF aquí';
            }
        }

        // Ir a la vista de miniaturas
        function goToThumbnailsView() {
            if (pdfFiles.length < 2) return;

            viewUpload.style.display = 'none';
            viewThumbnails.style.display = 'flex';
            renderThumbnails();
        }

        // Volver a la vista de subida
        function goToUploadView() {
            viewThumbnails.style.display = 'none';
            viewUpload.style.display = 'flex';
        }

        // Renderizar miniaturas en grid
        function renderThumbnails() {
            thumbnailsGrid.innerHTML = '';

            filesCount.textContent =
                `${pdfFiles.length} archivo${pdfFiles.length !== 1 ? 's' : ''} seleccionado${pdfFiles.length !== 1 ? 's' : ''}`;
            btnMergeThumbnails.disabled = pdfFiles.length < 2;

            pdfFiles.forEach((file, index) => {
                const thumbnailItem = document.createElement('div');
                thumbnailItem.className = 'thumbnail-item';
                thumbnailItem.draggable = true;
                thumbnailItem.dataset.index = index;

                // CORRECCIÓN: Ahora todo el elemento es arrastrable
                thumbnailItem.style.cursor = 'grab';

                let previewHTML = '';
                if (file.thumbnailUrl) {
                    previewHTML = `
                        <img src="${file.thumbnailUrl}" alt="Vista previa PDF">
                        <div class="pdf-page-count">${file.pageCount} página${file.pageCount !== 1 ? 's' : ''}</div>
                    `;
                } else {
                    previewHTML = '<i class="fas fa-file-pdf pdf-icon"></i>';
                }

                thumbnailItem.innerHTML = `
                    <div class="thumbnail-order">${index + 1}</div>
                    <div class="thumbnail-preview">
                        ${previewHTML}
                    </div>
                    <div class="thumbnail-info">
                        <div class="thumbnail-name" title="${file.name}">${file.name}</div>
                        <div class="thumbnail-size">${file.size}</div>
                    </div>
                    <div class="thumbnail-controls">
                        <button class="thumbnail-btn" data-action="remove" title="Eliminar archivo">
                            <i class="fas fa-times"></i>
                        </button>
                        <button class="thumbnail-btn" data-action="view" title="Ver archivo">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                `;

                thumbnailsGrid.appendChild(thumbnailItem);

                // Agregar eventos a los botones (solo estos elementos tienen pointer-events: auto)
                const removeBtn = thumbnailItem.querySelector('[data-action="remove"]');
                const viewBtn = thumbnailItem.querySelector('[data-action="view"]');

                removeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    removeFile(index);
                });

                viewBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    viewFile(file);
                });

                // Eventos de drag and drop en TODO el elemento
                thumbnailItem.addEventListener('mousedown', (e) => {
                    // Solo iniciar drag con botón izquierdo, no en los controles
                    if (e.button === 0 && !e.target.closest('.thumbnail-btn')) {
                        thumbnailItem.style.cursor = 'grabbing';
                    }
                });

                thumbnailItem.addEventListener('mouseup', () => {
                    thumbnailItem.style.cursor = 'grab';
                });
            });
        }

        // Inicializar eventos globales de drag and drop
        function initGlobalDragAndDrop() {
            // Drag start - CORREGIDO: Se maneja en el contenedor grid
            thumbnailsGrid.addEventListener('dragstart', (e) => {
                const thumbnailItem = e.target.closest('.thumbnail-item');
                if (!thumbnailItem) return;

                e.stopPropagation();
                dragSrcIndex = parseInt(thumbnailItem.dataset.index);
                dragSrcElement = thumbnailItem;

                // Marcar como arrastrando
                thumbnailItem.classList.add('dragging');
                isDragging = true;

                // Crear fantasma para arrastrar
                createDragGhost(thumbnailItem, e.clientX, e.clientY);

                // Establecer datos de transferencia
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', dragSrcIndex.toString());

                // Usar un elemento fantasma personalizado
                const ghost = document.createElement('div');
                ghost.className = 'drag-ghost';
                ghost.style.width = thumbnailItem.offsetWidth + 'px';
                ghost.style.height = thumbnailItem.offsetHeight + 'px';
                ghost.style.background = 'white';
                ghost.style.borderRadius = '8px';
                ghost.style.border = '2px solid #d93025';
                ghost.innerHTML = `
                    <div style="padding: 10px; text-align: center;">
                        <div style="font-size: 12px; color: #d93025; font-weight: bold;">
                            Mover a nueva posición
                        </div>
                    </div>
                `;
                document.body.appendChild(ghost);

                // Esconder el elemento original y usar el fantasma
                e.dataTransfer.setDragImage(ghost, thumbnailItem.offsetWidth / 2, thumbnailItem.offsetHeight / 2);

                // Remover el fantasma después de un tiempo
                setTimeout(() => {
                    if (ghost.parentNode) {
                        ghost.parentNode.removeChild(ghost);
                    }
                }, 0);
            });

            // Drag over - CORREGIDO: Manejar en todo el documento
            document.addEventListener('dragover', (e) => {
                if (!isDragging) return;
                e.preventDefault();

                // Mover el fantasma con el cursor
                if (dragGhost) {
                    dragGhost.style.left = (e.clientX - dragGhost.offsetWidth / 2) + 'px';
                    dragGhost.style.top = (e.clientY - 20) + 'px';
                }

                // Encontrar el elemento sobre el que estamos
                const elements = document.elementsFromPoint(e.clientX, e.clientY);
                const thumbnailUnderCursor = elements.find(el => el.classList && el.classList.contains(
                    'thumbnail-item'));

                if (thumbnailUnderCursor && thumbnailUnderCursor !== dragSrcElement) {
                    const targetIndex = parseInt(thumbnailUnderCursor.dataset.index);

                    // Remover clase de todos
                    document.querySelectorAll('.thumbnail-item').forEach(item => {
                        item.classList.remove('drag-over');
                    });

                    // Agregar clase al objetivo
                    thumbnailUnderCursor.classList.add('drag-over');
                    dropTargetIndex = targetIndex;
                } else {
                    // Si no hay miniatura bajo el cursor, limpiar
                    document.querySelectorAll('.thumbnail-item').forEach(item => {
                        item.classList.remove('drag-over');
                    });
                    dropTargetIndex = null;
                }
            });

            // Drag enter - Prevenir comportamiento por defecto
            document.addEventListener('dragenter', (e) => {
                if (isDragging) {
                    e.preventDefault();
                }
            });

            // Drag leave - Limpiar cuando salimos del grid
            thumbnailsGrid.addEventListener('dragleave', (e) => {
                if (!thumbnailsGrid.contains(e.relatedTarget)) {
                    document.querySelectorAll('.thumbnail-item').forEach(item => {
                        item.classList.remove('drag-over');
                    });
                    dropTargetIndex = null;
                }
            });

            // Drop - CORREGIDO: Manejar en el documento
            document.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();

                if (!isDragging || dragSrcIndex === null) return;

                // Si tenemos un objetivo válido
                if (dropTargetIndex !== null && dropTargetIndex !== dragSrcIndex) {
                    // Reordenar los archivos
                    const [movedFile] = pdfFiles.splice(dragSrcIndex, 1);
                    pdfFiles.splice(dropTargetIndex, 0, movedFile);

                    // Actualizar la vista
                    renderThumbnails();
                }

                // Limpiar todo
                cleanupDrag();
            });

            // Drag end - Limpiar siempre
            document.addEventListener('dragend', (e) => {
                cleanupDrag();
            });
        }

        // Crear fantasma para arrastrar
        function createDragGhost(element, x, y) {
            // Si ya existe, removerlo
            if (dragGhost) {
                document.body.removeChild(dragGhost);
            }

            // Crear nuevo fantasma
            dragGhost = element.cloneNode(true);
            dragGhost.classList.add('drag-ghost');
            dragGhost.style.position = 'fixed';
            dragGhost.style.zIndex = '1000';
            dragGhost.style.opacity = '0.8';
            dragGhost.style.pointerEvents = 'none';
            dragGhost.style.transform = 'rotate(5deg)';
            dragGhost.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.2)';
            dragGhost.style.left = (x - element.offsetWidth / 2) + 'px';
            dragGhost.style.top = (y - 20) + 'px';

            document.body.appendChild(dragGhost);
        }

        // Limpiar después del drag
        function cleanupDrag() {
            // Remover el fantasma
            if (dragGhost && dragGhost.parentNode) {
                dragGhost.parentNode.removeChild(dragGhost);
                dragGhost = null;
            }

            // Limpiar clases
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('dragging');
                item.classList.remove('drag-over');
                item.style.cursor = 'grab';
            });

            // Resetear variables
            isDragging = false;
            dragSrcIndex = null;
            dragSrcElement = null;
            dropTargetIndex = null;
        }

        // Ver archivo
        function viewFile(file) {
            const url = URL.createObjectURL(file.fileObject);
            window.open(url, '_blank');
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        }

        // Eliminar archivo
        function removeFile(index) {
            pdfFiles.splice(index, 1);

            if (viewThumbnails.style.display === 'flex') {
                renderThumbnails();
                if (pdfFiles.length < 2) {
                    goToUploadView();
                }
            } else {
                updateUploadView();
            }
        }

        // Eliminar todos los archivos
        function clearAllFiles() {
            if (pdfFiles.length === 0) return;

            if (confirm('¿Estás seguro de que quieres eliminar todos los archivos?')) {
                pdfFiles = [];
                renderThumbnails();
                goToUploadView();
            }
        }

        // Unir PDFs
        async function mergePDFs() {
            try {
                viewThumbnails.style.display = 'none';
                viewProcessing.style.display = 'flex';

                for (let i = 0; i < pdfFiles.length; i++) {
                    const file = pdfFiles[i];
                    const arrayBuffer = await file.fileObject.arrayBuffer();
                    file.arrayBuffer = arrayBuffer;
                }

                const mergedPdfDoc = await PDFLib.PDFDocument.create();

                for (let i = 0; i < pdfFiles.length; i++) {
                    const pdfData = pdfFiles[i].arrayBuffer;
                    const pdfDoc = await PDFLib.PDFDocument.load(pdfData);

                    const pages = await mergedPdfDoc.copyPages(pdfDoc, pdfDoc.getPageIndices());
                    pages.forEach(page => {
                        mergedPdfDoc.addPage(page);
                    });
                }

                const mergedPdfBytes = await mergedPdfDoc.save();
                mergedPdf = mergedPdfBytes;

                setTimeout(() => {
                    viewProcessing.style.display = 'none';
                    viewResult.style.display = 'flex';
                }, 1000);

            } catch (error) {
                console.error('Error al unir PDFs:', error);
                alert('Error al unir los PDFs. Por favor, intenta de nuevo.');
                viewProcessing.style.display = 'none';
                viewThumbnails.style.display = 'flex';
            }
        }

        // Descargar PDF unido
        function downloadMergedPDF() {
            if (!mergedPdf) {
                alert('No hay PDF para descargar.');
                return;
            }

            const blob = new Blob([mergedPdf], {
                type: 'application/pdf'
            });
            const fileName = `pdfs-unidos-${Date.now()}.pdf`;

            download(blob, fileName, 'application/pdf');
        }

        // Reiniciar aplicación
        function resetApp() {
            pdfFiles = [];
            mergedPdf = null;

            viewResult.style.display = 'none';
            viewUpload.style.display = 'flex';
            updateUploadView();
        }

        // Formatear tamaño de archivo
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</body>

</html>