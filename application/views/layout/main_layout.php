<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIKEUSEK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 220px;
            background-color: #007bff;
            padding: 15px;
            color: white;
            height: 100vh;
            position: fixed;
        }
        .sidebar .app-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }
        .sidebar h1 {
            font-size: 18px;
            margin-top: 10px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #0056b3;
        }
        .sidebar .logout {
            margin-top: 20px;
            background-color: #dc3545;
            text-align: center;
            border-radius: 5px;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
        }
        #konten {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="app-header">
            <img src="<?= base_url('assets/img/logo.png'); ?>" class="logo" alt="Logo">
            <h1>SIKEUSEK</h1>
        </div>
        <a href="#" onclick="loadPage('dashboard/bendahara')"><i class="fas fa-home"></i> Dashboard</a>
        <a href="#" onclick="loadPage('usermanagement')"><i class="fas fa-users"></i> Manajemen Pengguna</a>
        <a href="#" onclick="loadPage('sumberanggaran')"><i class="fas fa-coins"></i> Sumber Anggaran</a>
        <a href="#" onclick="loadPage('anggaran')"><i class="fas fa-wallet"></i> Anggaran</a>
        <a href="#" onclick="loadPage('expenditure')"><i class="fas fa-money-bill-wave"></i> Pengeluaran</a>
        <a href="#" onclick="loadPage('report')"><i class="fas fa-chart-line"></i> Laporan</a>
        <a href="<?= site_url('auth/logout'); ?>" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Konten Dinamis -->
    <div class="main-content">
        <div id="konten">
            <h2>Selamat datang, <?= $this->session->userdata('nama_lengkap') ?: 'Pengguna'; ?>!</h2>
            <p>Silakan pilih menu di sidebar untuk memulai.</p>
        </div>
    </div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function loadPage(url) {
        $("#konten").load("<?= site_url(); ?>/" + url);
    }
</script>

</body>
</html>
