<!DOCTYPE html>
<html>
<head>
    <title>Ganti Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        form {
            max-width: 400px;
            margin: auto;
        }
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
            margin-bottom: 16px;
        }
        button {
            padding: 10px 20px;
            background: #28a745;
            border: none;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <h2>Ganti Password</h2>

    <form method="post" action="<?php echo site_url('profil/update_password'); ?>">
        <label>Password Lama:</label>
        <input type="password" name="password_lama" required>

        <label>Password Baru:</label>
        <input type="password" name="password_baru" required>

        <label>Konfirmasi Password Baru:</label>
        <input type="password" name="konfirmasi_password" required>

        <button type="submit">Simpan Password</button>
    </form>

</body>
</html>
