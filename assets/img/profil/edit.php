<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ubah Profil</title>
    <style>
        body { font-family: Arial; margin: 40px; background-color: #f9f9f9; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="file"] {
            width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;
        }
        button {
            background-color: #007bff; color: white; padding: 10px 15px;
            border: none; border-radius: 4px; margin-top: 15px; cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Ubah Profil</h2>
    <form method="post" enctype="multipart/form-data" action="<?php echo site_url('profil/update'); ?>">
        <label for="nama">Nama Lengkap</label>
        <input type="text" name="nama" value="<?php echo $user->nama_lengkap; ?>" required>

        <label for="foto">Foto Profil</label>
        <input type="file" name="foto">

        <button type="submit">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>
