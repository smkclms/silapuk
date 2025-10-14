<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background-color: #007bff;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .sidebar .logo img {
            width: 40px;
            margin-right: 10px;
        }

        .sidebar .logo h2 {
            font-size: 18px;
            margin: 0;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            width: 100%;
            border-radius: 4px;
        }

        .sidebar a i {
            margin-right: 8px;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        h1 {
            color: #333;
        }

        .card {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 10px;
        }

        td {
            padding: 10px;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .logout {
            margin-top: auto;
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            width: 100%;
            text-align: center;
        }

        .logout:hover {
            background-color: #c82333;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
            .sidebar .logo {
                margin-bottom: 0;
            }
            .sidebar a {
                width: auto;
                padding: 10px;
                font-size: 14px;
            }
            .logout {
                width: auto;
                margin-top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="<?php echo base_url('assets/img/logo.png'); ?>" class="logo" alt="Logo">
                <h2>SIKEUSEK</h2>
            </div>
            <a href="<?php echo site_url('expenditure'); ?>"><i class="fas fa-wallet"></i> Pengeluaran</a>
           <a href="<?= site_url('dashboard/laporan_penggunaan_user'); ?>"><i class="fas fa-file-alt"></i> Laporan Penggunaan</a>

            <a href="<?php echo site_url('auth/logout'); ?>" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            
        </div>
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
                    $total_pengeluaran += is_array($ex) ? $ex['jumlah_pengeluaran'] : $ex->jumlah_pengeluaran;
                }

                $sisa_anggaran = $total_anggaran - $total_pengeluaran;
            ?>

            <div class="card">
                <h3><?php echo $user->nama_lengkap; ?></h3>
                <p><strong>Jumlah Anggaran:</strong> Rp <?php echo number_format($total_anggaran, 0, ',', '.'); ?></p>
                <p><strong>Sisa Anggaran:</strong> Rp <?php echo number_format($sisa_anggaran, 0, ',', '.'); ?></p>
            </div>
            

            <h2>Pengeluaran Saya</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
                <?php if (empty($expenditures_user)): ?>
                    <tr><td colspan="4">Belum ada pengeluaran.</td></tr>
                <?php else: ?>
                    <?php foreach ($expenditures_user as $expenditure): ?>
                        <tr>
                            <td><?= $expenditure['id']; ?></td>
                            <td><?= $expenditure['tanggal_pengeluaran']; ?></td>
                            <td>Rp <?= number_format($expenditure['jumlah_pengeluaran'], 0, ',', '.'); ?></td>
                            <td><?= $expenditure['keterangan']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>
