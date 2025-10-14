<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Sumber Anggaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-custom {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .form-section {
            flex: 1 1 300px;
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .table-section {
            flex: 2 1 600px;
        }

        .form-label i {
            margin-right: 5px;
        }

        .btn {
            border-radius: 6px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-donate"></i> Manajemen Sumber Anggaran</h2>

    <div class="container-custom">
        <!-- Form Tambah -->
        <div class="form-section">
            <h5 class="mb-3">Tambah Sumber Anggaran</h5>
            <form action="<?= site_url('sumberanggaran/add'); ?>" method="post">
                <div class="mb-3">
                    <label for="nama_sumber" class="form-label"><i class="fas fa-pencil-alt"></i> Nama Sumber</label>
                    <input type="text" name="nama_sumber" id="nama_sumber" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label"><i class="fas fa-money-bill-wave"></i> Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" step="0.01" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</button>
                    <a href="<?= site_url('dashboard/bendahara'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
<h6 class="text-muted mb-3">
    <i class="fas fa-calendar-alt"></i> Tahun Anggaran Aktif: 
    <strong><?= isset($tahun_aktif) ? $tahun_aktif : '-' ?></strong>
</h6>

        <!-- Tabel -->
        <div class="table-section">
            <h5 class="mb-3">Daftar Sumber Anggaran</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Sumber</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                   <tbody>
    <?php if (!empty($sumber)): ?>
        <?php foreach ($sumber as $item): ?>
        <tr>
            <td><?= $item->id; ?></td>
            <td><?= $item->nama_sumber; ?></td>
            <td>Rp <?= number_format($item->jumlah, 0, ',', '.'); ?></td>
            <td><?= date('d-m-Y', strtotime($item->created_at)); ?></td>
            <td>
                <!-- Tombol Edit -->
                <a href="<?= site_url('sumberanggaran/edit/' . $item->id); ?>" class="btn btn-sm btn-warning" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>

                <!-- Tombol Delete dengan konfirmasi -->
                <form action="<?= site_url('sumberanggaran/delete/' . $item->id); ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus sumber anggaran ini?');">
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center">Belum ada data sumber anggaran.</td>
        </tr>
    <?php endif; ?>
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
