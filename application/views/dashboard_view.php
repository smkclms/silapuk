<?php
echo "Tahun aktif: " . $this->session->userdata('tahun_anggaran');
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, #0062cc, #004fa3);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .sidebar .logo img {
            width: 40px;
            margin-right: 10px;
        }

        .sidebar .logo h2 {
            font-size: 18px;
            margin: 0;
            font-weight: 600;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
            display: block;
            border-radius: 5px;
            transition: 0.2s;
        }

        .sidebar a i {
            margin-right: 8px;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.15);
        }

        .logout {
            margin-top: auto;
            background-color: #dc3545;
            text-align: center;
        }

        .logout:hover {
            background-color: #c82333;
        }

        /* === MAIN CONTENT === */
        .main-content {
            flex: 1;
            padding: 30px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        h2 {
            margin-top: 40px;
            color: #333;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }

        .stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .stat-box {
            flex: 1;
            min-width: 220px;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .stat-box h4 {
            margin: 10px 0 5px;
            color: #555;
        }

        .stat-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        table th, table td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                flex-direction: row;
                overflow-x: auto;
            }
            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo">
            <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="Logo">
            <h2>SIKEUSEK</h2>
        </div>
        <a href="<?php echo site_url('sumberanggaran'); ?>"><i class="fas fa-coins"></i> Sumber Anggaran</a>
        <a href="<?php echo site_url('anggaran'); ?>"><i class="fas fa-file-invoice-dollar"></i> Anggaran</a>
        <a href="<?php echo site_url('expenditure'); ?>"><i class="fas fa-wallet"></i> Pengeluaran</a>
        <a href="<?php echo site_url('report'); ?>"><i class="fas fa-chart-bar"></i> Laporan</a>
        <a href="<?= site_url('laporanpenggunaan'); ?>"><i class="fas fa-file-alt"></i> <span>Laporan Penggunaan</span></a>
        <a href="<?php echo site_url('auth/logout'); ?>" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main-content">
        <h1>Dashboard Pengguna Anggaran</h1>
        <p>Selamat datang, <strong><?php echo $this->session->userdata('nama_lengkap'); ?></strong>!</p>

        <?php 
            $user_id = $this->session->userdata('user_id');
            $user = $this->User_model->get_user_by_id($user_id);
            $anggaran = $this->Anggaran_model->get_anggaran_by_user($user_id);
            $total_anggaran = !empty($anggaran) ? $anggaran[0]->jumlah_anggaran : 0;

            $expenditures_user = $this->Expenditure_model->get_expenditures_by_user($user_id);
            $total_pengeluaran = 0;
            foreach ($expenditures_user as $ex) {
                $total_pengeluaran += $ex->jumlah_pengeluaran;
            }

            $sisa_anggaran = $total_anggaran - $total_pengeluaran;
        ?>

        <div class="stats">
            <div class="stat-box">
                <h4>Total Anggaran</h4>
                <div class="value">Rp <?= number_format($total_anggaran, 0, ',', '.'); ?></div>
            </div>
            <div class="stat-box">
                <h4>Total Pengeluaran</h4>
                <div class="value text-danger">Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></div>
            </div>
            <div class="stat-box">
                <h4>Sisa Anggaran</h4>
                <div class="value text-success">Rp <?= number_format($sisa_anggaran, 0, ',', '.'); ?></div>
            </div>
        </div>

        <div class="card">
            <h3><?= $user->nama_lengkap; ?></h3>
            <p><strong>Jabatan:</strong> <?= $user->role; ?></p>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
    <h2>📊 Daftar Pengeluaran</h2>
    <form action="<?= site_url('laporanpenggunaan/cetak_pdf'); ?>" method="post" target="_blank" style="margin: 80px 0 0 0;">
    <button type="submit" style="
        background-color: #d9534f; 
        color: white; 
        border: none; 
        padding: 8px 16px; 
        border-radius: 4px; 
        font-size: 16px; 
        display: flex; 
        align-items: center; 
        gap: 8px;
        cursor: pointer;
    ">
        <i class="fas fa-file-pdf" style="font-size: 18px;"></i> Cetak PDF
    </button>
</form>

</div>


<table>
    <tr>
        <th>ID</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
        <th>Keterangan</th>
    </tr>
    <?php if (empty($expenditures_user)): ?>
        <tr><td colspan="4" style="text-align:center;">Belum ada pengeluaran.</td></tr>
    <?php else: ?>
        <?php foreach ($expenditures_user as $expenditure): ?>
            <tr>
                <td><?= $expenditure->id; ?></td>
                <td><?= $expenditure->tanggal_pengeluaran; ?></td>
                <td>Rp <?= number_format($expenditure->jumlah_pengeluaran, 0, ',', '.'); ?></td>
                <td><?= $expenditure->keterangan; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
    </div>
</div>
</body>
</html>
