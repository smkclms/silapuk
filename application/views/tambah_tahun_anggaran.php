<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tahun Anggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Tambah Tahun Anggaran</h2>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <form action="<?= site_url('anggaran/simpan_tahun'); ?>" method="post">
        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun Anggaran</label>
            <input type="number" name="tahun" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
</body>
</html>
