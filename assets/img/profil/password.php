<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ganti Password</title>
    <style>
        body { font-family: Arial; margin: 40px; background-color: #f9f9f9; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        label { display: block; margin-top: 10px; }
        input[type="password"] {
            width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;
        }
        button {
            background-color: #28a745; color: white; padding: 10px 15px;
            border: none; border-radius: 4px; margin-top: 15px; cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Ganti Password</h2>
    <form method="post" action="<?php echo site_url('profil/update_password'); ?>">
        <label for="password_lama">Password Lama</label>
        <input type="password" name="password_lama" required>

        <label for="password_baru">Password Baru</label>
        <input type="password" name="password_baru" required>

        <label for="konfirmasi_password">Konfirmasi Password Baru</label>
        <input type="password" name="konfirmasi_password" required>

        <button type="submit">Ganti Password</button>
    </form>
</div>
</body>
</html>
