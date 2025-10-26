<h2 style="color:#007bff;font-weight:600;margin-bottom:20px;">
  <i class="fa fa-edit"></i> Edit Data Pengeluaran
</h2>
<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>


<form action="<?= site_url('user/expenditureuser/update/'.$pengeluaran->id); ?>" method="post" class="pengeluaran-form">
  <div class="form-wrapper">

    <div class="form-group">
      <label><strong>Tanggal:</strong></label>
      <input type="date" name="tanggal_pengeluaran" value="<?= $pengeluaran->tanggal_pengeluaran; ?>" required>
    </div>

    <div class="form-group">
      <label><strong>Jumlah (Rp):</strong></label>
      <input type="number" name="jumlah_pengeluaran" min="0" step="0.01" value="<?= $pengeluaran->jumlah_pengeluaran; ?>" required>
    </div>

    <div class="form-group">
      <label><strong>Sumber Anggaran:</strong></label>
      <select name="sumber_id" required>
        <option value="">-- Pilih Sumber Anggaran --</option>
        <?php foreach ($sumber_anggaran as $sumber): ?>
          <option value="<?= $sumber->id; ?>" <?= $pengeluaran->sumber_id == $sumber->id ? 'selected' : ''; ?>>
            <?= htmlspecialchars($sumber->nama_sumber); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label><strong>Kategori Pengeluaran:</strong></label>
      <select name="kategori_pengeluaran_id" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategori_pengeluaran as $kategori): ?>
          <option value="<?= $kategori->id; ?>" <?= $pengeluaran->kategori_pengeluaran_id == $kategori->id ? 'selected' : ''; ?>>
            <?= htmlspecialchars($kategori->nama_kategori); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label><strong>Kode Rekening:</strong></label>
      <select name="kode_rekening_id" required>
        <option value="">-- Pilih Kode Rekening --</option>
        <?php foreach ($kode_rekening as $kode): ?>
          <option value="<?= $kode->id; ?>" <?= $pengeluaran->kode_rekening_id == $kode->id ? 'selected' : ''; ?>>
            <?= $kode->kode . ' - ' . $kode->nama_rekening; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label><strong>Keterangan:</strong></label>
      <input type="text" name="keterangan" value="<?= htmlspecialchars($pengeluaran->keterangan); ?>" placeholder="Opsional">
    </div>

    <div class="btn-group-action">
      <button type="submit" class="btn-tambah">
        <i class="fas fa-save"></i> Simpan Perubahan
      </button>
      <a href="<?= site_url('user/expenditureuser'); ?>" class="btn-template">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>

  </div>
</form>

<style>
  .pengeluaran-form {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-bottom: 25px;
  }

  .form-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: flex-end;
  }

  .form-group {
    display: flex;
    flex-direction: column;
  }

  .form-group input,
  .form-group select {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
    min-width: 180px;
  }

  .btn-group-action {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .btn-tambah {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
  }

  .btn-template {
    background: #6c757d;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
  }
</style>
