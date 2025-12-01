<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $document->document_name }} - Halte Document Viewer</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .viewer-header {
            background: rgba(255, 255, 255, 0.95);
            padding: 1.5rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .doc-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .doc-title i {
            font-size: 1.8rem;
            color: #667eea;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-custom {
            padding: 0.6rem 1.8rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-download {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-close-viewer {
            background-color: #6c757d;
            color: white;
        }

        .btn-close-viewer:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .viewer-content {
            padding: 2.5rem 0;
            min-height: calc(100vh - 100px);
        }

        .halte-info-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .document-container {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .document-preview {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
        }

        .pdf-viewer {
            width: 100%;
            height: 80vh;
            border: none;
            border-radius: 10px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
        }

        .no-preview {
            padding: 4rem 2rem;
            text-align: center;
        }

        .no-preview i {
            font-size: 6rem;
            color: #667eea;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .badge-simbada {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="viewer-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h1 class="doc-title">
                    <i class="{{ $document->icon_class }}"></i>
                    <span>{{ $document->document_name }}</span>
                </h1>
                <div class="action-buttons">
                    <a href="{{ route('admin.haltes.documents.download', $document->id) }}"
                       class="btn btn-custom btn-download">
                        <i class="fas fa-download me-2"></i>Download
                    </a>
                    <button onclick="window.close()" class="btn btn-custom btn-close-viewer">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="viewer-content">
        <div class="container">
            <!-- Halte Info Box -->
            <div class="halte-info-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6>
                            <i class="fas fa-bus me-2"></i>
                            Dokumen Halte {{ $document->isSimbadaDocument() ? 'SIMBADA' : '' }}
                        </h6>
                        <p class="mb-0">
                            <strong>{{ $document->halte->name }}</strong>
                        </p>
                        @if($document->halte->address)
                        <small>{{ $document->halte->address }}</small>
                        @endif
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        @if($document->isSimbadaDocument())
                        <div class="badge bg-white text-dark fs-6">
                            <i class="fas fa-database me-1"></i>
                            SIMBADA
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Document Viewer -->
            <div class="document-container">
                @if($document->isPdf())
                    <!-- PDF Viewer - Using serve route to avoid IDM -->
                    <iframe src="{{ route('admin.haltes.documents.serve', $document->id) }}"
                            class="pdf-viewer"
                            title="{{ $document->document_name }}">
                    </iframe>
                @elseif($document->isImage())
                    <!-- Image Viewer - Using serve route -->
                    <img src="{{ route('admin.haltes.documents.serve', $document->id) }}"
                         alt="{{ $document->document_name }}"
                         class="document-preview">
                @else
                    <!-- No Preview Available -->
                    <div class="no-preview">
                        <i class="{{ $document->icon_class }}"></i>
                        <h3 class="mb-3">Preview Tidak Tersedia</h3>
                        <p class="text-muted mb-2">Tipe file <strong>{{ strtoupper($document->file_type) }}</strong> tidak dapat ditampilkan di browser.</p>
                        <p class="text-muted mb-4">Silakan download file untuk melihat isinya.</p>
                        <a href="{{ route('admin.haltes.documents.download', $document->id) }}"
                           class="btn btn-custom btn-download btn-lg">
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // ESC to close
            if (e.key === 'Escape') {
                window.close();
            }
            // Ctrl/Cmd + D to download
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                window.location.href = '{{ route("admin.haltes.documents.download", $document->id) }}';
            }
        });
    </script>
</body>
</html>
