<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Anggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container py-4">
    <h2>Edit Anggaran</h2>
    <form action="<?= site_url('anggaran/update/' . $item->id); ?>" method="post">
        <div class="mb-3">
            <label for="user_id" class="form-label">Pengguna</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Pilih Pengguna</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user->id; ?>" <?= ($user->id == $item->user_id) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($user->nama_lengkap); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah_anggaran" class="form-label">Jumlah Anggaran</label>
            <input type="number" name="jumlah_anggaran" id="jumlah_anggaran" step="0.01" class="form-control" value="<?= htmlspecialchars($item->jumlah_anggaran); ?>" required>
        </div>

        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="number" name="tahun" id="tahun" class="form-control" value="<?= htmlspecialchars($item->tahun); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="<?= site_url('anggaran'); ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
