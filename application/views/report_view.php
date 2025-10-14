<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            background: #fff;
            margin: auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        form {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        label {
            font-weight: bold;
        }

        select, input[type="number"], button {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 120px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #0056b3;
        }

        canvas {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 10px;
        }

        td {
            padding: 10px;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fas fa-chart-line"></i> Laporan Pengeluaran Bulanan</h1>

    <!-- Filter -->
    <form action="<?= site_url('report/generate'); ?>" method="post">
        <div>
            <label for="year">Tahun:</label><br>
            <input type="number" name="year" id="year" value="<?= isset($year) ? $year : date('Y'); ?>" required>
        </div>

        <div>
            <label for="month">Bulan:</label><br>
            <select name="month" id="month" required>
                <option value="">--Pilih Bulan--</option>
                <?php 
                $bulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                foreach ($bulan as $num => $nama):
                ?>
                    <option value="<?= $num; ?>" <?= isset($month) && $month == $num ? 'selected' : ''; ?>><?= $nama; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>&nbsp;</label><br>
            <button type="submit"><i class="fas fa-search"></i> Tampilkan</button>
        </div>
    </form>

    <?php if (isset($monthly_expenditure) && !empty($monthly_expenditure)): ?>

        <!-- Grafik -->
        <canvas id="expenditureChart" height="100"></canvas>

        <!-- Tabel -->
        <table>
            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Total Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($monthly_expenditure as $nama => $total): ?>
    <tr>
        <td><?= $nama; ?></td>
        <td>Rp <?= number_format($total, 0, ',', '.'); ?></td>
    </tr>
    <?php endforeach; ?>
    <?php
        $grand_total = array_sum($monthly_expenditure);
    ?>
    <tr style="font-weight: bold; background-color: #f2f2f2;">
        <td style="text-align: right;">Total</td>
        <td>Rp <?= number_format($grand_total, 0, ',', '.'); ?></td>
    </tr>
</tbody>

        </table>

        <!-- Aksi -->
        <div class="actions">
            <form method="post" action="<?= site_url('report/pdf'); ?>" target="_blank">
                <input type="hidden" name="year" value="<?= $year; ?>">
                <input type="hidden" name="month" value="<?= $month; ?>">
                <button type="submit"><i class="fas fa-file-pdf"></i> Cetak PDF</button>
            </form>

            <form method="post" action="<?= site_url('report/excel'); ?>">
                <input type="hidden" name="year" value="<?= $year; ?>">
                <input type="hidden" name="month" value="<?= $month; ?>">
                <button type="submit"><i class="fas fa-file-excel"></i> Export Excel</button>
            </form>
        </div>
    <?php endif; ?>
    

    <a href="<?= site_url('dashboard/bendahara'); ?>" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
</div>

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
            plugins: {
                legend: { display: false }
            },
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
