<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container py-4">
    <h2>Edit Pengeluaran</h2>
    <form action="<?= site_url('expenditure/update/' . $expenditure->id); ?>" method="post">
        <div class="mb-3">
            <label for="user_id" class="form-label">Pengguna</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user->id; ?>" <?= $user->id == $expenditure->user_id ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($user->nama_lengkap); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_pengeluaran" class="form-label">Tanggal Pengeluaran</label>
            <input type="date" id="tanggal_pengeluaran" name="tanggal_pengeluaran" class="form-control" value="<?= $expenditure->tanggal_pengeluaran; ?>" required>
        </div>

        <div class="mb-3">
            <label for="jumlah_pengeluaran" class="form-label">Jumlah Pengeluaran</label>
            <input type="number" id="jumlah_pengeluaran" name="jumlah_pengeluaran" class="form-control" step="0.01" value="<?= $expenditure->jumlah_pengeluaran; ?>" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" id="keterangan" name="keterangan" class="form-control" value="<?= htmlspecialchars($expenditure->keterangan); ?>" required>
        </div>

        <div class="mb-3">
            <label for="kode_rekening_id" class="form-label">Kode Rekening</label>
            <select name="kode_rekening_id" id="kode_rekening_id" class="form-select" required>
                <?php foreach ($kode_rekening as $kode): ?>
                    <option value="<?= $kode->id; ?>" <?= $kode->id == $expenditure->kode_rekening_id ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($kode->kode . ' - ' . $kode->nama_rekening); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('expenditure'); ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
