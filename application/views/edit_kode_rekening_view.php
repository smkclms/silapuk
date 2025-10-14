<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Kode Rekening</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-primary">Edit Kode Rekening</h2>
      <a href="<?= site_url('koderekening'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>

    <div class="row">
      <!-- Form Edit -->
      <div class="col-md-5 mb-4">
        <div class="card">
          <div class="card-header bg-warning text-white">
            Form Edit
          </div>
          <div class="card-body">
            <form action="<?= site_url('koderekening/update/'.$kode_rekening->id); ?>" method="post">
              <div class="mb-3">
                <label for="kode" class="form-label">Kode Rekening</label>
                <input type="text" name="kode" id="kode" class="form-control" required
                       value="<?= $kode_rekening->kode; ?>" />
              </div>
              <div class="mb-3">
                <label for="nama_rekening" class="form-label">Nama Rekening</label>
                <input type="text" name="nama_rekening" id="nama_rekening" class="form-control" required
                       value="<?= $kode_rekening->nama_rekening; ?>" />
              </div>
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Preview -->
      <div class="col-md-7">
        <div class="card">
          <div class="card-header bg-info text-white">
            Data Saat Ini
          </div>
          <div class="card-body">
            <table class="table table-bordered">
              <tr>
                <th width="30%">ID</th>
                <td><?= $kode_rekening->id; ?></td>
              </tr>
              <tr>
                <th>Kode</th>
                <td><?= $kode_rekening->kode; ?></td>
              </tr>
              <tr>
                <th>Nama Rekening</th>
                <td><?= $kode_rekening->nama_rekening; ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
