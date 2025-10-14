<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manajemen Kode Rekening</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    td.nama-rekening {
      word-break: break-word;
      max-width: 400px;
    }
    @media (max-width: 768px) {
      .table-actions {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-primary m-0">📁 Manajemen Kode Rekening</h2>
      <a href="<?= site_url('dashboard/bendahara'); ?>" class="btn btn-outline-secondary">
        ← Kembali ke Dashboard
      </a>
    </div>

    <div class="row g-4">
      <!-- Form Tambah -->
      <div class="col-lg-4">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            Tambah Kode Rekening
          </div>
          <div class="card-body">
            <form action="<?= site_url('koderekening/add'); ?>" method="post">
              <div class="mb-3">
                <label for="kode" class="form-label">Kode Rekening</label>
                <input type="text" name="kode" id="kode" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="nama_rekening" class="form-label">Nama Rekening</label>
                <input type="text" name="nama_rekening" id="nama_rekening" class="form-control" required>
              </div>
              <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success w-100">+ Tambah</button>
              <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#importModal">
                ⬆️ Import
              </button>
            </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Tabel Daftar -->
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-header bg-secondary text-white">
            Daftar Kode Rekening
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th width="5%">ID</th>
                    <th width="15%">Kode</th>
                    <th width="55%">Nama Rekening</th>
                    <th width="25%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($kode_rekening)): ?>
                    <?php foreach ($kode_rekening as $kode): ?>
                      <tr>
                        <td><?= $kode->id; ?></td>
                        <td><?= $kode->kode; ?></td>
                        <td class="nama-rekening"><?= $kode->nama_rekening; ?></td>
                        <td>
                          <div class="d-flex gap-2 table-actions">
                            <a href="<?= site_url('koderekening/edit/' . $kode->id); ?>" class="btn btn-warning btn-sm">
                              ✏️ Edit
                            </a>
                            <form action="<?= site_url('koderekening/delete/' . $kode->id); ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                              <button type="submit" class="btn btn-danger btn-sm">
                                🗑️ Hapus
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="text-center text-muted">Belum ada data rekening.</td>
                    </tr>
                  <?php endif; ?>
                  <!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= site_url('koderekening/import'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="importModalLabel">Import Kode Rekening</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="file_import" class="form-label">Pilih File (CSV/Excel)</label>
            <input type="file" name="file_import" id="file_import" class="form-control" accept=".xls,.xlsx,.csv" required>
            <small class="text-muted">Format file: .xls, .xlsx, atau .csv dengan kolom: Kode, Nama Rekening</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Import</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
            </tbody>
              </table>
              </div>
</div>
<!-- Tampilkan Pagination -->
<div class="p-3">
  <?= $pagination; ?>
</div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
