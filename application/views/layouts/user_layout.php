<?php
$tahun_aktif  = $this->session->userdata('tahun_anggaran');
$nama_lengkap = $this->session->userdata('nama_lengkap');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIKEUSEK - Dashboard Pengguna</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family:'Poppins',sans-serif;
    margin:0;
    background:#f4f6f9;
    color:#333;
}
.container {
    display:flex;
    min-height:100vh;
}

/* Sidebar dasar */
.sidebar {
    width:230px;
    background:#007bff;
    color:#fff;
    padding:20px 15px;
    display:flex;
    flex-direction:column;
    transition:all 0.3s ease;
    position:fixed;
    top:0;left:0;
    height:100vh;
    z-index:1000;
    overflow-x:hidden;
    box-shadow: 2px 0 6px rgba(0,0,0,0.1);
}
.sidebar.collapsed {
    width:70px;
}
.sidebar h2 {
    font-size:18px;
    text-align:center;
    margin-bottom:25px;
    transition:opacity 0.3s ease;
}
.sidebar.collapsed h2 {
    opacity:0;
}
.sidebar a {
    color:#fff;
    text-decoration:none;
    padding:10px;
    display:flex;
    align-items:center;
    border-radius:6px;
    font-size:14px;
    margin-bottom:5px;
    transition:all 0.3s ease;
}
.sidebar a i {
    width:25px;
    text-align:center;
}
.sidebar a:hover,
.sidebar a.active {
    background:#0056b3;
}
.logout {
    margin-top:auto;
    background:#dc3545;
    text-align:center;
    border-radius:6px;
    padding:10px;
}

/* Konten utama */
.main-content {
    flex:1;
    margin-left:200px;
    padding:15px 20px;
    transition:margin-left 0.3s ease;
}
.sidebar.collapsed + .main-content {
    margin-left:70px;
}

/* Topbar */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    position: relative;
    z-index: 900; /* biar di atas sidebar */
}

/* Tombol toggle fix di kiri topbar */
.toggle-btn {
    background: none;
    border: none;
    color: #007bff;
    font-size: 20px;
    cursor: pointer;
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1001;
}

.topbar h1 {
    font-size: 18px;
    margin: 0;
    text-align: center;
    flex: 1;
}

.content {
    padding: 10px;
    margin-top: 10px;
}

/* Responsif: layar kecil */
@media (max-width: 768px) {
    .sidebar {
        position:fixed;
        left:-230px;
        top:0;
        width:230px;
        height:100vh;
        background:#007bff;
        z-index:999;
    }
    .sidebar.active {
        left:0;
        box-shadow:0 0 0 100vmax rgba(0,0,0,0.3);
    }
    .main-content {
        margin-left:0;
        padding:20px;
    }
    .sidebar.collapsed {
        width:230px;
    }
}
</style>
</head>

<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>SIKEUSEK</h2>
        <a href="<?= site_url('user/dashboarduser'); ?>"><i class="fas fa-home"></i> Dashboard</a>
        <a href="<?= site_url('user/anggaranuser'); ?>"><i class="fa fa-wallet"></i> Anggaran</a>
        <a href="<?= site_url('user/expenditureuser'); ?>"><i class="fa fa-coins"></i> Pengeluaran</a>
        <a href="<?= site_url('user/reportuser'); ?>"><i class="fa fa-chart-line"></i> Laporan</a>
        <a href="<?= site_url('user/laporanpenggunaanuser'); ?>"><i class="fa fa-file-alt"></i> Laporan Penggunaan</a>
        <a href="<?= site_url('auth/logout'); ?>" class="logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Konten utama -->
    <div class="main-content">
        <div class="topbar">
            <h1>
                <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>
<h1>Hai, <?= $nama_lengkap; ?> 👋</h1>
            <span><strong>Tahun aktif:</strong> <?= isset($tahun_aktif) ? $tahun_aktif : 'Belum dipilih'; ?></span>
        </div>

        <div class="content">
            <?= isset($content) ? $content : '' ?>
        </div>
    </div>
</div>

<script>
function toggleSidebar(){
    const sidebar=document.getElementById('sidebar');
    if(window.innerWidth <= 768){
        sidebar.classList.toggle('active'); // overlay mode untuk HP
    }else{
        sidebar.classList.toggle('collapsed'); // mode kecil/besar untuk desktop
    }
}
window.addEventListener('resize',function(){
    const sidebar=document.getElementById('sidebar');
    if(window.innerWidth>768){
        sidebar.classList.remove('active');
    }
});
</script>
<!-- ✅ Tambahkan ini di bagian bawah sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
