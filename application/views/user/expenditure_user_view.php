<h2 style="color:#007bff;font-weight:600;margin-bottom:20px;">
  <i class="fa fa-coins"></i> Pengeluaran Saya
</h2>

<!-- Flash Message -->
<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<!-- ===================== -->
<!-- FORM TAMBAH PENGELUARAN -->
<!-- ===================== -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form action="<?= site_url('user/expenditureuser/add'); ?>" method="post" class="row g-3 align-items-end">

      <div class="col-md-2">
        <label class="form-label fw-bold">Tanggal</label>
        <input type="date" name="tanggal_pengeluaran" class="form-control" required>
      </div>

      <div class="col-md-2">
        <label class="form-label fw-bold">Jumlah (Rp)</label>
        <input type="number" name="jumlah_pengeluaran" min="0" step="0.01" class="form-control" required>
      </div>

      <div class="col-md-2">
        <label class="form-label fw-bold">Sumber Anggaran</label>
        <select name="sumber_id" class="form-select" required>
          <option value="">-- Pilih --</option>
          <?php foreach ($sumber_anggaran as $sumber): ?>
            <option value="<?= $sumber->id; ?>"><?= htmlspecialchars($sumber->nama_sumber); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label fw-bold">Kategori</label>
        <select name="kategori_pengeluaran_id" class="form-select" required>
          <option value="">-- Pilih --</option>
          <?php foreach ($kategori_pengeluaran as $kategori): ?>
            <option value="<?= $kategori->id; ?>"><?= htmlspecialchars($kategori->nama_kategori); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label fw-bold">Kode Rekening</label>
        <select name="kode_rekening_id" class="form-select" required>
          <option value="">-- Pilih --</option>
          <?php foreach ($kode_rekening as $kode): ?>
            <option value="<?= $kode->id; ?>">
              <?= $kode->kode . ' - ' . $kode->nama_rekening; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label fw-bold">Keterangan</label>
        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
      </div>

      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-plus"></i> Tambah
        </button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
          <i class="fas fa-upload"></i> Import Excel
        </button>
        <a href="<?= base_url('assets/template_import_pengeluaran.xlsx'); ?>" class="btn btn-secondary">
          <i class="fas fa-download"></i> Unduh Template
        </a>
      </div>
    </form>
  </div>
</div>

<!-- ===================== -->
<!-- FILTER PENGELUARAN -->
<!-- ===================== -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form method="get" action="<?= site_url('user/expenditureuser'); ?>" class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="form-label fw-bold">Filter Sumber Anggaran:</label>
        <select name="sumber_id" class="form-select">
          <option value="">-- Semua Sumber --</option>
          <?php foreach ($sumber_anggaran as $sumber): ?>
            <option value="<?= $sumber->id; ?>" <?= ($filter_sumber == $sumber->id) ? 'selected' : ''; ?>>
              <?= htmlspecialchars($sumber->nama_sumber); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-bold">Filter Kategori Pengeluaran:</label>
        <select name="kategori_pengeluaran_id" class="form-select">
          <option value="">-- Semua Kategori --</option>
          <?php foreach ($kategori_pengeluaran as $kategori): ?>
            <option value="<?= $kategori->id; ?>" <?= ($filter_kategori == $kategori->id) ? 'selected' : ''; ?>>
              <?= htmlspecialchars($kategori->nama_kategori); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-filter"></i> Terapkan Filter
        </button>
        <a href="<?= site_url('user/expenditureuser'); ?>" class="btn btn-secondary">
          <i class="fa fa-undo"></i> Reset
        </a>
      </div>
    </form>
  </div>
</div>
<!-- ===================== -->
<!-- TABEL DAFTAR PENGELUARAN -->
<!-- ===================== -->
<div class="card shadow-sm" style="margin: 0 -150px;">
  <div class="card-body" style="padding: 1.5rem 2rem;">
    <?php if (empty($expenditures)): ?>
      <p>Belum ada data pengeluaran yang tercatat.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-primary">
            <tr class="text-center">
              <th>No</th>
              <th>Tanggal</th>
              <th>Jumlah (Rp)</th>
              <th>Sumber Anggaran</th>
              <th>Kategori</th>
              <th>Kode Rekening</th>
              <th>Nama Rekening</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <<?php
$no = $this->uri->segment(4) ? $this->uri->segment(4) + 1 : 1;
foreach ($expenditures as $ex):
?>
  <tr>
    <td class="text-center"><?= $no++; ?></td>

                <td><?= date('d-m-Y', strtotime($ex->tanggal_pengeluaran)); ?></td>
                <td>Rp <?= number_format($ex->jumlah_pengeluaran, 0, ',', '.'); ?></td>
                <td><?= !empty($ex->nama_sumber) ? htmlspecialchars($ex->nama_sumber) : '-'; ?></td>
                <td><?= !empty($ex->nama_kategori) ? htmlspecialchars($ex->nama_kategori) : '-'; ?></td>
                <td><?= !empty($ex->kode_rekening_kode) ? $ex->kode_rekening_kode : '-'; ?></td>
                <td><?= !empty($ex->nama_rekening) ? $ex->nama_rekening : '-'; ?></td>
                <td><?= !empty($ex->keterangan) ? htmlspecialchars($ex->keterangan) : '-'; ?></td>
                <td class="text-center">
                  <a href="<?= site_url('user/expenditureuser/edit/' . $ex->id); ?>" class="btn btn-sm btn-warning">
                    <i class="fa fa-edit"></i>
                  </a>
                  <a href="<?= site_url('user/expenditureuser/delete/' . $ex->id); ?>"
                     onclick="return confirm('Yakin ingin menghapus data ini?')"
                     class="btn btn-sm btn-danger">
                    <i class="fa fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
<div class="d-flex justify-content-center mt-3">
  <?= $pagination; ?>
</div>



<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= site_url('user/expenditureuser/import'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="importModalLabel">
            <i class="fa fa-file-excel"></i> Import Data Pengeluaran
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label"><strong>Pilih File Excel</strong></label>
          <input type="file" name="file_import" accept=".xls,.xlsx" class="form-control" required>
          <small class="text-muted">Gunakan template resmi dari sistem untuk format yang sesuai.</small>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">
            <i class="fa fa-upload"></i> Import Sekarang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
