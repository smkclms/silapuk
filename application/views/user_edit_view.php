<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
</head>
<body>
    <h1>Edit Pengguna</h1>
    <form action="<?php echo site_url('usermanagement/update/' . $user->id); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo $user->username; ?>" required><br>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="bendahara" <?php echo $user->role == 'bendahara' ? 'selected' : ''; ?>>Bendahara</option>
            <option value="operator" <?php echo $user->role == 'operator' ? 'selected' : ''; ?>>Operator</option>
            <option value="pengguna" <?php echo $user->role == 'pengguna' ? 'selected' : ''; ?>>Pengguna</option>
        </select><br>

        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" value="<?php echo $user->nama_lengkap; ?>" required><br>

        <input type="submit" value="Update Pengguna">
    </form>
</body>
</html>
