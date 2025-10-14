<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Sumber Anggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container py-4">
    <h2>Edit Sumber Anggaran</h2>
    <form action="<?= site_url('sumberanggaran/update/' . $item->id); ?>" method="post">
    <div class="mb-3">
        <label for="nama_sumber" class="form-label">Nama Sumber</label>
        <input type="text" id="nama_sumber" name="nama_sumber" class="form-control" value="<?= htmlspecialchars($item->nama_sumber); ?>" required />
    </div>
    <div class="mb-3">
        <label for="jumlah" class="form-label">Jumlah</label>
        <input type="number" id="jumlah" name="jumlah" class="form-control" step="0.01" value="<?= htmlspecialchars($item->jumlah); ?>" required />
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="<?= site_url('sumberanggaran'); ?>" class="btn btn-secondary">Batal</a>
</form>

</div>
</body>
</html>
