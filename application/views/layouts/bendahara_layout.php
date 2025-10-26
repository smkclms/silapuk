<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : 'Dashboard'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome + Poppins -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 220px;
            background-color: #007bff;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 5px 0;
        }
        .sidebar a:hover {
            background: #005dc1;
            border-radius: 5px;
        }
        .main-content {
            margin-left: 240px;
            padding: 25px;
            flex: 1;
        }
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SIKEUSEK</h2>
        <a href="<?= site_url('dashboard/bendahara'); ?>"><i class="fas fa-home"></i> Dashboard</a>
        <a href="<?= site_url('usermanagement'); ?>"><i class="fas fa-users"></i> Manajemen Pengguna</a>
        <a href="<?= site_url('koderekening'); ?>"><i class="fas fa-file-invoice"></i> Kode Rekening</a>
        <a href="<?= site_url('sumberanggaran'); ?>"><i class="fas fa-coins"></i> Sumber Anggaran</a>
        <a href="<?= site_url('anggaran'); ?>"><i class="fas fa-wallet"></i> Anggaran</a>
        <a href="<?= site_url('expenditure'); ?>"><i class="fas fa-money-bill-wave"></i> Pengeluaran</a>
        <a href="<?= site_url('report'); ?>"><i class="fas fa-chart-line"></i> Laporan</a>
        <a href="<?= site_url('laporanpenggunaan'); ?>"><i class="fas fa-file-alt"></i> Laporan Penggunaan</a>
        <a href="<?= site_url('auth/logout'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Konten Halaman -->
    <div class="main-content">
        <div class="content-box">
            <?php 
                // ini penting: load view asli kamu di tengah layout
                if (isset($content_view)) {
                    $this->load->view($content_view, isset($content_data) ? $content_data : []);
                }
            ?>
        </div>
    </div>

</body>
</html>
