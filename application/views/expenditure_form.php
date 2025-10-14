<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f7f7f7;
        }

        h1, h2 {
            color: #007bff;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="date"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
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
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <h1>Dashboard</h1>
    <p>Selamat datang, <strong><?php echo $this->session->userdata('role'); ?></strong>!</p>

    <h2>Form Pengeluaran</h2>
    <form action="<?php echo site_url('expenditure/add'); ?>" method="post">

        <?php if ($this->session->userdata('role') === 'bendahara'): ?>
            <label for="user_id">Pengguna:</label>
            <select name="user_id" id="user_id" required>
                <?php foreach ($users as $user): ?>
                    <?php if (!in_array(strtolower($user->role), ['bendahara', 'superadmin'])): ?>
                        <option value="<?= $user->id; ?>"><?= $user->nama_lengkap; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <p><strong>Pengguna:</strong> <?= $this->session->userdata('nama'); ?></p>
            <input type="hidden" name="user_id" value="<?= $this->session->userdata('user_id'); ?>">
        <?php endif; ?>

        <label for="tanggal_pengeluaran">Tanggal Pengeluaran:</label>
        <input type="date" name="tanggal_pengeluaran" required>

        <label for="jumlah_pengeluaran">Jumlah Pengeluaran:</label>
        <input type="number" name="jumlah_pengeluaran" step="0.01" required>

        <label for="keterangan">Keterangan:</label>
        <input type="text" name="keterangan" required>

        <label for="kode_rekening_id">Kode Rekening:</label>
        <select name="kode_rekening_id" id="kode_rekening_id" required>
            <?php foreach ($kode_rekening as $kode): ?>
                <option value="<?php echo $kode->id; ?>">
                    <?php echo $kode->kode . ' - ' . $kode->nama_rekening; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Tambah Pengeluaran">
    </form>

    <h2>Daftar Pengeluaran</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pengguna</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($expenditures)): ?>
                <tr><td colspan="5">Tidak ada data pengeluaran.</td></tr>
            <?php else: ?>
                <?php foreach ($expenditures as $expenditure): ?>
                    <?php $user = $this->User_model->get_user_by_id($expenditure->user_id); ?>
                    <tr>
                        <td><?php echo $expenditure->id; ?></td>
                        <td><?php echo $user ? $user->nama_lengkap : 'Tidak ditemukan'; ?></td>
                        <td><?php echo $expenditure->tanggal_pengeluaran; ?></td>
                        <td>Rp <?php echo number_format($expenditure->jumlah_pengeluaran, 0, ',', '.'); ?></td>
                        <td><?php echo $expenditure->keterangan; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a class="logout" href="<?php echo site_url('auth/logout'); ?>">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>

</body>
</html>
