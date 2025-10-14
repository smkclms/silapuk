<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Laporan Penggunaan</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container py-4">
  <h2><i class="fas fa-file-alt"></i> Laporan Penggunaan</h2>
<div class="mb-3">
  <a href="<?= site_url('dashboard/bendahara'); ?>" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
  </a>
</div>


  <form id="filterForm" method="get" action="<?= site_url('laporanpenggunaan'); ?>" class="row g-3 align-items-center mb-4">

    <div class="col-auto">
      <label for="start_date" class="col-form-label">Dari Tanggal</label>
      <input type="date" id="start_date" name="start_date" class="form-control" value="<?= isset($start_date) ? $start_date : '' ?>" />
    </div>

    <div class="col-auto">
      <label for="end_date" class="col-form-label">Sampai Tanggal</label>
      <input type="date" id="end_date" name="end_date" class="form-control" value="<?= isset($end_date) ? $end_date : '' ?>" />
    </div>

    <div class="col-auto">
      <label for="user_id" class="col-form-label">Pengguna</label>
      <select id="user_id" name="user_id" class="form-select">
        <option value="">-- Semua Pengguna --</option>
        <?php foreach ($users as $user): ?>
          <?php if (!in_array(strtolower($user->role), ['bendahara', 'superadmin'])): ?>
            <option value="<?= $user->id ?>" <?= (isset($user_id) && $user_id == $user->id) ? 'selected' : '' ?>>
              <?= htmlspecialchars($user->nama_lengkap) ?>
            </option>
          <?php endif; ?>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-auto align-self-end">
      <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
    </div>

  </form>

  <div class="mb-3 d-flex gap-2">
    <form id="pdfForm" method="post" action="<?= site_url('laporanpenggunaan/cetak_pdf'); ?>" target="_blank">
      <input type="hidden" name="start_date" value="<?= isset($start_date) ? $start_date : '' ?>" />
      <input type="hidden" name="end_date" value="<?= isset($end_date) ? $end_date : '' ?>" />
      <input type="hidden" name="user_id" value="<?= isset($user_id) ? $user_id : '' ?>" />
      <button type="submit" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Cetak PDF</button>
    </form>

    <form id="excelForm" method="post" action="<?= site_url('laporanpenggunaan/export_excel'); ?>">
      <input type="hidden" name="start_date" value="<?= isset($start_date) ? $start_date : '' ?>" />
      <input type="hidden" name="end_date" value="<?= isset($end_date) ? $end_date : '' ?>" />
      <input type="hidden" name="user_id" value="<?= isset($user_id) ? $user_id : '' ?>" />
      <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</button>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Nomor</th>
      <th>Nama Pengguna</th>
      <th>Tanggal Pengeluaran</th>
      <th>Jumlah Pengeluaran</th>
      <th>Kode Rekening</th> <!-- Tambahkan header ini -->
      <th>Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($expenditures)): ?>
      <tr><td colspan="6" class="text-center">Tidak ada data pengeluaran.</td></tr>
    <?php else: ?>
      <?php $no = 1; ?>
      <?php foreach ($expenditures as $ex): ?>
        <?php $user = $this->User_model->get_user_by_id($ex->user_id); ?>
        <tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($user ? $user->nama_lengkap : '-'); ?></td>
    <td><?= date('d-m-Y', strtotime($ex->tanggal_pengeluaran)); ?></td>
    <td>Rp <?= number_format($ex->jumlah_pengeluaran, 0, ',', '.'); ?></td>
    <td><?= htmlspecialchars($ex->kode_rekening_kode . ' - ' . $ex->nama_rekening); ?></td>
    <td><?= htmlspecialchars($ex->keterangan); ?></td>
</tr>

      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>


  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
