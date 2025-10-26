<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran Bulanan Saya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 25px; }
        h1 { color: #007bff; margin-bottom: 20px; text-align: center; }
        form { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-bottom: 20px; }
        input, select, button { padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .actions { display: flex; justify-content: center; gap: 10px; margin-top: 20px; flex-wrap: wrap; }
        .actions button { padding: 8px 14px; border-radius: 6px; font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>

<h1><i class="fas fa-chart-line"></i> Laporan Pengeluaran Bulanan Saya</h1>

<!-- FILTER -->
<form method="post" action="<?= site_url('user/reportuser/generate'); ?>">
    <label for="year"><strong>Tahun:</strong></label>
    <input type="number" name="year" id="year" value="<?= isset($year) ? $year : date('Y'); ?>" required>

    <label for="month"><strong>Bulan:</strong></label>
    <select name="month" id="month" required>
        <option value="">--Pilih Bulan--</option>
        <?php
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        foreach ($bulan as $num => $nama): ?>
            <option value="<?= $num; ?>" <?= isset($month) && $month == $num ? 'selected' : ''; ?>>
                <?= $nama; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">
        <i class="fas fa-search"></i> Tampilkan
    </button>
</form>

<!-- HASIL LAPORAN -->
<?php if (isset($monthly_expenditure) && !empty($monthly_expenditure)): ?>
<hr>

<h3 style="text-align:center; margin-top:30px;">Grafik Pengeluaran</h3>
<canvas id="expenditureChart" height="100"></canvas>

<h3 style="margin-top:30px;">Rincian Pengeluaran</h3>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Total Pengeluaran (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $grand_total = 0;
        foreach ($monthly_expenditure as $tgl => $total): 
            $grand_total += $total;
        ?>
        <tr>
            <td><?= htmlspecialchars($tgl); ?></td>
            <td>Rp <?= number_format($total, 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr style="background:#f2f2f2;font-weight:bold;">
            <td>Total</td>
            <td>Rp <?= number_format($grand_total, 0, ',', '.'); ?></td>
        </tr>
    </tbody>
</table>

<!-- AKSI CETAK -->
<div class="actions">
    <form method="post" action="<?= site_url('user/reportuser/pdf'); ?>" target="_blank">
        <input type="hidden" name="year" value="<?= $year; ?>">
        <input type="hidden" name="month" value="<?= $month; ?>">
        <button type="submit" style="background:#dc3545;">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </button>
    </form>

    <form method="post" action="<?= site_url('user/reportuser/excel'); ?>">
        <input type="hidden" name="year" value="<?= $year; ?>">
        <input type="hidden" name="month" value="<?= $month; ?>">
        <button type="submit" style="background:#198754;">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
    </form>
</div>
<?php endif; ?>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php if (isset($monthly_expenditure) && !empty($monthly_expenditure)): ?>
<script>
const ctx = document.getElementById('expenditureChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($monthly_expenditure)); ?>,
        datasets: [{
            label: 'Total Pengeluaran (Rp)',
            data: <?= json_encode(array_values($monthly_expenditure)); ?>,
            backgroundColor: 'rgba(0, 123, 255, 0.7)',
            borderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        }
    }
});
</script>
<?php endif; ?>

</body>
</html>
