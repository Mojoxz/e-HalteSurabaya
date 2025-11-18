<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal Success Delete - Halte Dihapus</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            text-align: center;
            font-size: 14px;
            background-color: #DBE3E6;
            font-family: Arial, sans-serif;
        }

        h1 {
            font-family: 'Pacifico', cursive;
            font-size: 6em;
            color: #252E40;
            line-height: 2.0em;
            margin-top: 50px;
        }

        button {
            background-color: #F44336;
            border: none;
            padding: 15px 20px;
            color: white;
            font-size: 2em;
            margin-top: 40px;
            transition: 0.3s;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
    <h1>Hapus Halte</h1>
    <button id="btnHapusHalte">Hapus Halte</button>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#btnHapusHalte").click(function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Berhasil Dihapus!',
                    text: 'Halte berhasil dihapus',
                    confirmButtonColor: '#F44336',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
</body>
</html>
