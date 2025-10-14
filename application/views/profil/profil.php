<!DOCTYPE html>
<html>
<head>
    <title>Ubah Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        form {
            max-width: 500px;
            margin: auto;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
            margin-bottom: 16px;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            border: none;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <h2>Ubah Profil</h2>

    <form method="post" action="<?php echo site_url('profil/update'); ?>" enctype="multipart/form-data">
        <label>Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" value="<?php echo $user->nama_lengkap; ?>" required>

        <label>Foto Profil:</label><br>
        <?php if (!empty($user->foto)): ?>
            <img src="<?php echo base_url('assets/img/profil/' . $user->foto); ?>" width="100" style="margin-bottom: 10px;">
        <?php endif; ?>
        <input type="file" name="foto">

        <button type="submit">Simpan Perubahan</button>
    </form>

</body>
</html>
