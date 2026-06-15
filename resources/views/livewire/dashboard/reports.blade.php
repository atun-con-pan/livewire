<!DOCTYPE html>
<html lang="es" class="min-h-screen bg-slate-900 text-white">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Generar Informes</title>
  <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/x-icon" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/pdf-lib/dist/pdf-lib.min.js"></script>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

  <div class="bg-slate-800 rounded-3xl shadow-xl max-w-lg w-full p-8 sm:p-10">
    <h1 class="text-3xl font-extrabold text-cyan-400 text-center mb-8">Generar Informes PDF</h1>

    <label for="templateSelect" class="block mb-2 font-semibold text-gray-300">Selecciona plantilla:</label>
    <select
      id="templateSelect"
      class="w-full mb-6 rounded-lg bg-slate-700 border border-slate-600 text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400"
    >
      <option value="carta" selected>Plantilla Tamaño Carta (4 fotos 2x2)</option>
      <option value="oficio">Plantilla Tamaño Oficio (6 fotos 2x3)</option>
    </select>

    <label for="inputImages" class="block mb-2 font-semibold text-gray-300">Carga imágenes (múltiples):</label>
    <input
      type="file"
      id="inputImages"
      accept="image/*"
      multiple
      class="w-full mb-8 rounded-lg bg-slate-700 border border-slate-600 text-white px-4 py-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-cyan-400"
    />

    <button
      id="generate"
      class="w-full bg-cyan-500 hover:bg-cyan-600 transition font-bold py-3 rounded-lg shadow-lg"
    >
      Generar PDF
    </button>

    <div id="progressContainer" class="mt-6" style="display:none;">
      <label class="block mb-2 font-semibold text-gray-300">Generando PDF...</label>
      <div class="w-full bg-slate-700 rounded-full h-6 overflow-hidden shadow-inner">
        <div
          id="progressBar"
          class="h-6 bg-cyan-400 text-black font-semibold text-center leading-6"
          style="width: 0%"
        >
          0%
        </div>
      </div>
    </div>
  </div>

  <script>
    const { PDFDocument } = PDFLib;

    const templates = {
      carta: 'templates/plantilla.pdf',
      oficio: 'templates/plantilla_oficio.pdf'
    };

    const layouts = {
      carta: {
        headerHeight: 100,
        footerHeight: 50,
        cols: 2,
        rows: 2
      },
      oficio: {
        headerHeight: 120,
        footerHeight: 60,
        cols: 2,
        rows: 3
      }
    };

    const generateBtn = document.getElementById('generate');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');

    generateBtn.onclick = async () => {
      const plantillaSeleccionada = document.getElementById('templateSelect').value;
      const imageFiles = Array.from(document.getElementById('inputImages').files);

      if (imageFiles.length === 0) {
        alert('Por favor selecciona al menos una imagen.');
        return;
      }

      generateBtn.disabled = true;
      progressContainer.style.display = 'block';
      progressBar.style.width = '0%';
      progressBar.textContent = '0%';

      let pdfBytes;
      try {
        const response = await fetch(templates[plantillaSeleccionada]);
        if (!response.ok) throw new Error('No se pudo cargar la plantilla PDF');
        pdfBytes = await response.arrayBuffer();
      } catch (e) {
        alert('Error cargando plantilla: ' + e.message);
        generateBtn.disabled = false;
        progressContainer.style.display = 'none';
        return;
      }

      const pdfDoc = await PDFDocument.load(pdfBytes);

      const { headerHeight, footerHeight, cols, rows } = layouts[plantillaSeleccionada];
      const templatePage = pdfDoc.getPage(0);
      const { width, height } = templatePage.getSize();

      const margin = 20;
      const usableHeight = height - headerHeight - footerHeight;
      const imageWidth = (width - margin * (cols + 1)) / cols;
      const imageHeight = (usableHeight - margin * (rows + 1)) / rows;

      const fotosPorPagina = cols * rows;

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
            alert('Tipo de imagen no soportado: ' + imgFile.type);
            continue;
          }

          const col = j % cols;
          const row = Math.floor(j / cols);

          const x = margin + col * (imageWidth + margin);
          const y = height - headerHeight - margin - (row + 1) * imageHeight - row * margin;

          page.drawImage(img, {
            x,
            y,
            width: imageWidth,
            height: imageHeight
          });
        }

        const progressPercent = Math.min(100, Math.round(((i + fotosPorPagina) / imageFiles.length) * 100));
        progressBar.style.width = progressPercent + '%';
        progressBar.textContent = progressPercent + '%';

        await new Promise(resolve => setTimeout(resolve, 100));
      }

      pdfDoc.removePage(0);

      const pdfFinal = await pdfDoc.save();
      const blob = new Blob([pdfFinal], { type: 'application/pdf' });
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'Informe Fotográfico.pdf';
      a.click();

      progressBar.style.width = '100%';
      progressBar.textContent = '¡Completado!';

      setTimeout(() => {
        progressContainer.style.display = 'none';
        generateBtn.disabled = false;
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
      }, 2000);
    };
  </script>
</body>
</html>