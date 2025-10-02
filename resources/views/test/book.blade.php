@extends('layout')

@section('title', 'Employee Handbook')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employee Handbook</h1>
    </div>
    <ul class='p-2'>
        <!-- PDF Viewer Section -->
        <div id="pdf-viewer">
            <canvas id="pdf-canvas"></canvas>
        </div>
        <div class="nav-buttons text-center">
            <button id="prev-page" class="btn btn-primary">Previous Page</button>
            <button id="next-page" class="btn btn-primary">Next Page</button>
        </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.3.122/pdf.min.js"></script>
<script>
    // Path to the PDF file (adjust the path to fit your public directory structure)
    const url = '{{ asset('Employee Handbook.v.6 (1).pdf') }}';

    // Set up PDF.js
    const pdfjsLib = window['pdfjs-dist/build/pdf'];

    // Asynchronously load the PDF
    pdfjsLib.getDocument(url).promise.then(pdf => {
        let currentPage = 1;

        function renderPage(num) {
            pdf.getPage(num).then(page => {
                const scale = 1.5;
                const viewport = page.getViewport({ scale });

                const canvas = document.getElementById('pdf-canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        }

        renderPage(currentPage);

        document.getElementById('prev-page').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        document.getElementById('next-page').addEventListener('click', () => {
            if (currentPage < pdf.numPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });
    }).catch(error => {
        console.error('Error loading PDF:', error);
    });
</script>
@endsection