<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengelolaan Pengguna</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        h2, h4 {
            color: #333;
            margin-bottom: 15px;
        }
        .container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .form-section {
            flex: 1 1 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .table-section {
            flex: 2 1 600px;
            overflow-x: auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <h2><i class="fas fa-users"></i> Pengelolaan Pengguna</h2>
    <div class="container">

        <!-- Form Tambah -->
        <div class="form-section">
            <h4>Tambah Pengguna Baru</h4>
            <form id="form-tambah-user">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Role:</label>
                    <select name="role" class="form-select" required>
                        <option value="bendahara">Bendahara</option>
                        <option value="operator">Operator</option>
                        <option value="pengguna">Pengguna</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama_lengkap" class="form-control" required />
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Pengguna
                    </button>
                    <button type="button" onclick="history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Pengguna -->
        <div class="table-section">
            <h4>Daftar Pengguna</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Nama Lengkap</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-user">
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user->id; ?></td>
                        <td><?= htmlspecialchars($user->username); ?></td>
                        <td><?= ucfirst(htmlspecialchars($user->role)); ?></td>
                        <td><?= htmlspecialchars($user->nama_lengkap); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-user" data-id="<?= $user->id; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-user" data-id="<?= $user->id; ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="form-edit-user" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditUserLabel">Edit Pengguna</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="id" id="edit-user-id" />
              <div class="mb-3">
                  <label>Username</label>
                  <input type="text" name="username" id="edit-username" class="form-control" required />
              </div>
              <div class="mb-3">
                  <label>Password <small>(kosongkan jika tidak ingin diubah)</small></label>
                  <input type="password" name="password" id="edit-password" class="form-control" />
              </div>
              <div class="mb-3">
                  <label>Role</label>
                  <select name="role" id="edit-role" class="form-select" required>
                      <option value="bendahara">Bendahara</option>
                      <option value="operator">Operator</option>
                      <option value="pengguna">Pengguna</option>
                  </select>
              </div>
              <div class="mb-3">
                  <label>Nama Lengkap</label>
                  <input type="text" name="nama_lengkap" id="edit-nama_lengkap" class="form-control" required />
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Script AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function(){
        // Submit tambah user
        $('#form-tambah-user').on('submit', function(e){
            e.preventDefault();
            $.ajax({
                url: '<?= site_url("usermanagement/add_ajax"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response){
                    if(response.status === 'success'){
                        alert('Pengguna berhasil ditambahkan');
                        location.reload();
                    } else {
                        alert('Gagal menambahkan pengguna: ' + (response.message || 'Error'));
                    }
                },
                error: function(){
                    alert('Terjadi kesalahan saat menambahkan pengguna');
                }
            });
        });

        // Hapus user
        $('.delete-user').click(function(){
            const id = $(this).data('id');
            if(confirm('Yakin ingin menghapus pengguna ini?')) {
                $.post('<?= site_url("usermanagement/delete_ajax"); ?>', {id: id}, function(res){
                    if(res.status === 'success'){
                        alert('Pengguna berhasil dihapus');
                        location.reload();
                    } else {
                        alert('Gagal menghapus pengguna');
                    }
                }, 'json');
            }
        });

        // Tampilkan modal edit dan isi data user
        $('.edit-user').click(function(){
            const id = $(this).data('id');
            $.getJSON('<?= site_url("usermanagement/get_user"); ?>/' + id, function(data){
                if(data) {
                    $('#edit-user-id').val(data.id);
                    $('#edit-username').val(data.username);
                    $('#edit-role').val(data.role);
                    $('#edit-nama_lengkap').val(data.nama_lengkap);
                    $('#edit-password').val('');
                    var modal = new bootstrap.Modal(document.getElementById('modalEditUser'));
                    modal.show();
                } else {
                    alert('Data pengguna tidak ditemukan');
                }
            });
        });

        // Submit form edit user
        $('#form-edit-user').submit(function(e){
            e.preventDefault();
            $.post('<?= site_url("usermanagement/edit_ajax"); ?>', $(this).serialize(), function(res){
                if(res.status === 'success') {
                    alert('Data pengguna berhasil diupdate');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat update');
                }
            }, 'json');
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
