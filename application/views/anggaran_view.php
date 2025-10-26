<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Anggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container-custom {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .form-section {
            flex: 1 1 300px;
            max-width: 400px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .table-section {
            flex: 2 1 600px;
            overflow-x: auto;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-3"><i class="fas fa-money-bill-wave"></i> Manajemen Anggaran</h2>

    <div class="container-custom">
        <!-- Form Tambah Anggaran -->
        <div class="form-section">
            <h4>Tambah Anggaran</h4>
            <form action="<?php echo site_url('anggaran/add'); ?>" method="post">
                <div class="mb-3">
                    <label for="user_id" class="form-label">Pengguna</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Pilih Pengguna</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user->id; ?>"><?php echo $user->nama_lengkap; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jumlah_anggaran" class="form-label">Jumlah Anggaran</label>
                    <input type="number" name="jumlah_anggaran" step="0.01" class="form-control" required>
                </div>

                <div class="mb-3">
    <label for="tahun" class="form-label">Tahun</label>
    <input type="number" name="tahun" class="form-control" 
           value="<?= isset($tahun_aktif) ? $tahun_aktif : date('Y'); ?>" required>
</div>


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Anggaran
                    </button>
                    <?php
$role = strtolower($this->session->userdata('role'));
if ($role == 'bendahara') {
    $back_url = site_url('dashboard/bendahara');
} elseif ($role == 'admin') {
    $back_url = site_url('dashboard/admin');
} else {
    $back_url = site_url('dashboard/view'); // khusus user biasa
}
?>
<a href="<?php echo $back_url; ?>" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Anggaran -->
        <div class="table-section">
            <h4 class="mb-3">Daftar Anggaran</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
    <thead class="table-primary">
        <tr>
            <th>Nomor</th>
            <th>Pengguna</th>
            <th>Jumlah Anggaran</th>
            <th>Tahun</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach ($anggaran as $item): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $this->User_model->get_user_by_id($item->user_id)->nama_lengkap; ?></td>
            <td>Rp <?= number_format($item->jumlah_anggaran, 2, ',', '.'); ?></td>
            <td><?= $item->tahun; ?></td>
            <td>
                <a href="<?= site_url('anggaran/edit/' . $item->id); ?>" class="btn btn-sm btn-warning" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="<?= site_url('anggaran/delete/' . $item->id); ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus anggaran ini?');">
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            </div>
        </div>
    </div>
</div>

<!-- Font Awesome untuk ikon -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
