<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $document->document_name }} - Document Viewer</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .viewer-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .viewer-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .doc-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .doc-title i {
            font-size: 1.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-custom {
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-download {
            background-color: #28a745;
            color: white;
        }

        .btn-download:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-close-viewer {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-close-viewer:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .viewer-content {
            padding: 2rem 0;
            min-height: calc(100vh - 80px);
        }

        .doc-info {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .doc-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.5rem;
        }

        .doc-info-item i {
            color: #667eea;
            width: 20px;
        }

        .document-container {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }

        .document-preview {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .pdf-viewer {
            width: 100%;
            height: 80vh;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .no-preview {
            padding: 3rem;
            text-align: center;
        }

        .no-preview i {
            font-size: 5rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .viewer-header .container {
                flex-direction: column;
                gap: 1rem;
            }

            .doc-title {
                font-size: 1rem;
                text-align: center;
            }

            .action-buttons {
                width: 100%;
                justify-content: center;
            }

            .btn-custom {
                padding: 0.4rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="viewer-header">
        <div class="container">
            <h1 class="doc-title">
                <i class="{{ $document->icon_class }}"></i>
                <span>{{ $document->document_name }}</span>
            </h1>
            <div class="action-buttons">
                <a href="{{ route('admin.' . $type . 's.documents.download', $document->id) }}"
                   class="btn btn-custom btn-download">
                    <i class="fas fa-download me-2"></i>Download
                </a>
                <button onclick="window.close()" class="btn btn-custom btn-close-viewer">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="viewer-content">
        <div class="container">
            <!-- Document Info -->
            <div class="doc-info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="doc-info-item">
                            <i class="fas fa-file"></i>
                            <strong>Nama File:</strong>
                            <span>{{ $document->document_name }}</span>
                        </div>
                        <div class="doc-info-item">
                            <i class="fas fa-file-code"></i>
                            <strong>Tipe:</strong>
                            <span>{{ strtoupper($document->file_type) }}</span>
                        </div>
                        <div class="doc-info-item">
                            <i class="fas fa-hdd"></i>
                            <strong>Ukuran:</strong>
                            <span>{{ $document->formatted_file_size }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($document->description)
                        <div class="doc-info-item">
                            <i class="fas fa-info-circle"></i>
                            <strong>Deskripsi:</strong>
                            <span>{{ $document->description }}</span>
                        </div>
                        @endif
                        <div class="doc-info-item">
                            <i class="fas fa-user"></i>
                            <strong>Diupload oleh:</strong>
                            <span>{{ $document->uploader->name ?? 'System' }}</span>
                        </div>
                        <div class="doc-info-item">
                            <i class="fas fa-calendar"></i>
                            <strong>Tanggal Upload:</strong>
                            <span>{{ $document->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Viewer -->
            <div class="document-container">
                @if($document->isPdf())
                    <!-- PDF Viewer -->
                    <iframe src="{{ asset('storage/' . $document->document_path) }}"
                            class="pdf-viewer"
                            title="{{ $document->document_name }}">
                    </iframe>
                @elseif($document->isImage())
                    <!-- Image Viewer -->
                    <img src="{{ asset('storage/' . $document->document_path) }}"
                         alt="{{ $document->document_name }}"
                         class="document-preview">
                @else
                    <!-- No Preview Available -->
                    <div class="no-preview">
                        <i class="{{ $document->icon_class }}"></i>
                        <h4>Preview Tidak Tersedia</h4>
                        <p class="text-muted">Tipe file ini tidak dapat ditampilkan di browser.</p>
                        <p class="text-muted mb-3">Silakan download file untuk melihat isinya.</p>
                        <a href="{{ route('admin.' . $type . 's.documents.download', $document->id) }}"
                           class="btn btn-primary btn-lg">
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
                window.location.href = '{{ route("admin." . $type . "s.documents.download", $document->id) }}';
            }
        });

        // Print functionality
        function printDocument() {
            window.print();
        }
    </script>
</body>
</html>
