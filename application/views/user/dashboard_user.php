<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .card-summary {
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            transition: transform 0.2s ease;
            text-align: center;
        }
        .card-summary:hover {
            transform: translateY(-3px);
        }
        .card-summary h6 {
            font-size: 0.9rem;
            color: #666;
        }
        .card-summary h4 {
            margin-top: 5px;
            font-weight: bold;
        }
        .chart-container {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container-fluid py-3">
    <div class="dashboard-header">
        <div>
            <h3><i class="fas fa-chart-pie"></i> Dashboard Saya (<?= $user->nama_lengkap; ?>)</h3>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar-alt"></i> Tahun Anggaran: 
<strong><?= isset($tahun_aktif) && $tahun_aktif !== '' ? $tahun_aktif : '-'; ?></strong>

            </p>
        </div>
        <div>
            <h5>Hai, <?= $user->nama_lengkap; ?> 👋</h5>
        </div>
    </div>

    <!-- Ringkasan Anggaran -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card card-summary p-3 border-primary">
                <h6>Total Anggaran</h6>
                <h4 class="text-primary">Rp <?= number_format($total_pagu, 0, ',', '.'); ?></h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-summary p-3 border-success">
                <h6>Total Disalurkan</h6>
                <h4 class="text-success">Rp <?= number_format($total_disalurkan, 0, ',', '.'); ?></h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-summary p-3 border-danger">
                <h6>Total Dibelanjakan</h6>
                <h4 class="text-danger">Rp <?= number_format($total_dibelanjakan, 0, ',', '.'); ?></h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-summary p-3 border-warning">
                <h6>Sisa Anggaran</h6>
                <h4 class="text-warning">Rp <?= number_format($sisa_anggaran, 0, ',', '.'); ?></h4>
            </div>
        </div>
    </div>

    <!-- Grafik Pengeluaran -->
    <div class="chart-container">
        <h5 class="mb-3"><i class="fas fa-chart-line"></i> Grafik Pengeluaran Bulanan</h5>
        <canvas id="chartUser" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartUser').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($chart_data)); ?>,
        datasets: [{
            label: 'Jumlah Pengeluaran (Rp)',
            data: <?= json_encode(array_values($chart_data)); ?>,
            backgroundColor: 'rgba(54,162,235,0.6)',
            borderColor: 'rgba(54,162,235,1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
