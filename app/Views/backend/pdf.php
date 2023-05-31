<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PDF.js Example</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.viewer.min.css">
  </head>
  <body>
    <div id="pdfViewer"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
      // Load the PDF document
      pdfjsLib.getDocument('path/to/pdf/document.pdf').then(function(pdf) {
        // Get the first page
        pdf.getPage(1).then(function(page) {
          // Create a canvas element to render the page
          var canvas = document.createElement('canvas');
          var context = canvas.getContext('2d');

          // Set the canvas dimensions
          var viewport = page.getViewport({ scale: 1 });
          canvas.width = viewport.width;
          canvas.height = viewport.height;

          // Render the page
          page.render({
            canvasContext: context,
            viewport: viewport
          }).promise.then(function() {
            // Add the canvas to the page
            var pdfViewer = document.getElementById('pdfViewer');
            pdfViewer.appendChild(canvas);
          });
        });
      });
    </script>
  </body>
</html>
