<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Keuangan Sekolah</title>
</head>
<body>
    <h2>Pengeluaran</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama Pengguna</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
        <th>Keterangan</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <?php $expenditures = $this->Expenditure_model->get_expenditures_by_user($user->id); ?>
        <?php foreach ($expenditures as $expenditure): ?>
        <tr>
            <td><?php echo $expenditure->id; ?></td>
            <td><?php echo $user->nama_lengkap; ?></td>
            <td><?php echo $expenditure->tanggal_pengeluaran; ?></td>
            <td><?php echo $expenditure->jumlah_pengeluaran; ?></td>
            <td><?php echo $expenditure->keterangan; ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>

</body>
</html>
