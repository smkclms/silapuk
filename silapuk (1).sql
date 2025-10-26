-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 14 Okt 2025 pada 02.00
-- Versi Server: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `silapuk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggaran`
--

CREATE TABLE IF NOT EXISTS `anggaran` (
`id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `tahun_id` int(11) unsigned DEFAULT NULL,
  `jumlah_anggaran` decimal(15,2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `anggaran`
--

INSERT INTO `anggaran` (`id`, `user_id`, `tahun_id`, `jumlah_anggaran`, `tahun`, `created_at`) VALUES
(20, 3, 1, '1000000.00', 2025, '2025-10-13 18:30:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kode_rekening`
--

CREATE TABLE IF NOT EXISTS `kode_rekening` (
`id` int(11) unsigned NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama_rekening` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kode_rekening`
--

INSERT INTO `kode_rekening` (`id`, `kode`, `nama_rekening`, `created_at`) VALUES
(1, '11124', 'All Kegiatan', '2025-06-20 03:22:33'),
(82, '5.1.02.01.01.0055', 'Belanja Makanan dan Minuman pada Fasilitas Pelayanan Urusan Pendidikan', '2025-06-23 08:02:44'),
(83, '5.1.02.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover', '2025-06-23 08:02:44'),
(84, '5.1.02.03.02.0463', 'Belanja Pemeliharaan Alat Peraga-Alat Peraga Pelatihan dan Percontohan-Alat Peraga Pelatihan', '2025-06-23 08:02:44'),
(85, '5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak dan Penggandaan', '2025-06-23 08:02:44'),
(86, '5.1.02.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor', '2025-06-23 08:02:44'),
(87, '5.1.02.02.01.0063', 'Belanja Kawat/Faksimili/Internet/TV Berlangganan', '2025-06-23 08:02:44'),
(88, '5.1.02.02.01.0003', 'Honorarium  Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia', '2025-06-23 08:02:44'),
(89, '5.1.02.02.04.0036', 'Belanja Sewa Kendaraan Bermotor Penumpang', '2025-06-23 08:02:44'),
(90, '5.1.02.02.04.0037', 'Belanja Sewa Kendaraan Bermotor Angkutan Barang', '2025-06-23 08:02:44'),
(91, '5.1.02.04.01.0003', 'Belanja Perjalanan Dinas Dalam Kota / Dalam Daerah', '2025-06-23 08:02:44'),
(92, '5.1.02.02.01.0013', '002. Honorarium Tenaga Administrasi (Giyan rinaldi)', '2025-06-23 08:02:44'),
(93, '5.1.02.01.01.0037', 'Belanja Obat-Obat-Obatan', '2025-06-23 08:02:44'),
(94, '5.2.05.01.01.0001', 'Belanja Modal Buku Umum', '2025-06-23 08:02:44'),
(95, '5.2.05.01.01.0003', 'Belanja Modal Buku Agama', '2025-06-23 08:02:44'),
(96, '5.1.02.01.01.0001', 'Belanja Bahan-Bahan Bangunan dan Konstruksi', '2025-06-23 08:02:44'),
(97, '5.1.02.01.01.0031', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Listrik', '2025-06-23 08:02:44'),
(98, '5.1.02.02.01.0016', 'Belanja Jasa Tenaga Penanganan Prasarana dan Sarana Umum / Upah Tukang', '2025-06-23 08:02:44'),
(99, '5.2.02.05.01.0005', 'Belanja Modal Alat Kantor Lainnya', '2025-06-23 08:02:44'),
(100, '5.2.02.10.01.0002', 'Belanja Modal Personal Computer', '2025-06-23 08:02:44'),
(101, '5.1.02.01.01.0027', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Benda Pos', '2025-06-23 08:02:44'),
(102, '5.1.02.01.01.0030', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Perabot Kantor', '2025-06-23 08:02:44'),
(103, '5.1.02.02.01.0061', 'Belanja Tagihan Listrik', '2025-06-23 08:02:44'),
(104, '5.1.02.02.01.0062', 'Belanja Langganan Jurnal/Surat Kabar/Majalah', '2025-06-23 08:02:44'),
(105, '5.1.02.02.01.0009', 'Honorarium Penyelenggara Ujian', '2025-06-23 08:02:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE IF NOT EXISTS `log_aktivitas` (
`id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `waktu_aktivitas` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE IF NOT EXISTS `pengeluaran` (
`id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `tahun_id` int(11) unsigned DEFAULT NULL,
  `tanggal_pengeluaran` date NOT NULL,
  `jumlah_pengeluaran` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `kode_rekening_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sumber_anggaran`
--

CREATE TABLE IF NOT EXISTS `sumber_anggaran` (
`id` int(11) unsigned NOT NULL,
  `tahun` varchar(10) DEFAULT NULL,
  `nama_sumber` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sumber_anggaran`
--

INSERT INTO `sumber_anggaran` (`id`, `tahun`, `nama_sumber`, `jumlah`, `created_at`) VALUES
(6, '2025', 'Dana BOSP Reguler', '5000000.00', '2025-10-13 18:28:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_anggaran`
--

CREATE TABLE IF NOT EXISTS `tahun_anggaran` (
`id` int(11) unsigned NOT NULL,
  `tahun` int(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tahun_anggaran`
--

INSERT INTO `tahun_anggaran` (`id`, `tahun`) VALUES
(1, 2025),
(2, 2026);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('bendahara','operator','pengguna') NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tahun_aktif` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama_lengkap`, `foto`, `created_at`, `tahun_aktif`) VALUES
(2, 'admin', '$2y$10$Jh8YmocIJHpl0d5rFad2mO711UZ0c12f4NQlOUz3aQ3nDLUO9CjFO', 'bendahara', 'Sukirman', '1750408780.jpg', '2025-06-20 03:11:05', NULL),
(3, 'perkantoran', '$2y$10$6DJCXx73C/3naQ8LBZunk.nOyi5EicQoJTsIesh2.yRQS4cIgQiGC', 'pengguna', 'MPLB', NULL, '2025-06-20 03:51:57', NULL),
(4, 'pemasaran', '$2y$10$T3IJjy.3LKYU6sSGIkxZ6Om//4PkLjJQ7BqybTHPvZbX7.Ui2FyC.', 'pengguna', 'Pemasaran', NULL, '2025-06-20 06:46:54', NULL),
(5, 'kuliner', '$2y$10$QWAUbOXu4NWP2JRucy66iOQ1amKrEB1vZT5oramWJNNRBPLrnKSke', 'pengguna', 'Kuliner', NULL, '2025-06-20 06:47:15', NULL),
(6, 'perhotelan', '$2y$10$CWDfOm41KDng.mru9Pa0U.Sq54O4G.WQqpf4.ZIFukm73WrF/pvZS', 'pengguna', 'Perhotelan', NULL, '2025-06-20 07:09:47', NULL),
(7, 'ulp', '$2y$10$7e4v68n7EtrpAN3C.ghqquvQ6bsx850/KvXqG/taHt6p4O9PHFXm2', 'pengguna', 'ULP', NULL, '2025-06-20 07:10:06', NULL),
(8, 'kurikulum', '$2y$10$dN7yJXTa7tjdcuvygK3NvOw7.eqt4lN3UFotUAxmcwakekgfzjDUy', 'pengguna', 'Kurikulum', NULL, '2025-06-20 07:11:07', NULL),
(13, 'Smkn1cilimus', '$2y$10$Jlt8PCir2Lwur2dWjKwiNeXxhsCPR2/efKiHT..23N2K7RPD99OzW', 'bendahara', 'Sukirman, S.Pd', NULL, '2025-06-21 12:13:29', NULL),
(14, 'sarpras', '$2y$10$HGpv5.d5LhyI9czwocDSP.BwX7aRCgAgea1ckxm/8fdWUhqKlIr7a', 'pengguna', 'Sarpras', NULL, '2025-06-21 14:30:00', NULL),
(15, 'sdm', '$2y$10$o7MIYS8.iLbcMrHfsA8OOum2A5DE7kFy0nWxmr1qZ.qrSr62ZSTsS', 'pengguna', 'SDM', NULL, '2025-06-21 14:30:51', NULL),
(16, 'hubin', '$2y$10$0miS5RrkbPNknMtPzwfNq.cwpNuEmm9OzZnNSAL5SZ6dDCQ55atny', 'pengguna', 'HUBIN', NULL, '2025-06-21 14:31:18', NULL),
(17, 'kesiswaan', '$2y$10$mvsd5xxB5aTpScqkWsYQquYZiI/ox9Slr63XwQIqZjh1OOhRZaOOS', 'pengguna', 'Kesiswaan', NULL, '2025-06-21 14:31:37', NULL),
(18, 'tatausaha', '$2y$10$p.eRHgrV2C7Ycp6cXquJ2.g6SgQ9ndNyabDzSVmvVAytzs2g3.yoO', 'pengguna', 'Tata Usaha', NULL, '2025-06-21 14:32:06', NULL),
(19, 'nonbajeter', '$2y$10$s1U0nJtVyv6Kh3HPi2zW0e8/JqnaC9wqS/BFP2YkFg.PQfTjMHaZW', 'pengguna', 'Non Bajeter', NULL, '2025-06-21 14:32:27', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggaran`
--
ALTER TABLE `anggaran`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `fk_anggaran_tahun` (`tahun_id`);

--
-- Indexes for table `kode_rekening`
--
ALTER TABLE `kode_rekening`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `kode_rekening_id` (`kode_rekening_id`), ADD KEY `fk_pengeluaran_tahun` (`tahun_id`);

--
-- Indexes for table `sumber_anggaran`
--
ALTER TABLE `sumber_anggaran`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tahun_anggaran`
--
ALTER TABLE `tahun_anggaran`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `tahun` (`tahun`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD KEY `fk_users_tahun` (`tahun_aktif`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggaran`
--
ALTER TABLE `anggaran`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `kode_rekening`
--
ALTER TABLE `kode_rekening`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `sumber_anggaran`
--
ALTER TABLE `sumber_anggaran`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tahun_anggaran`
--
ALTER TABLE `tahun_anggaran`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `anggaran`
--
ALTER TABLE `anggaran`
ADD CONSTRAINT `anggaran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_anggaran_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_anggaran` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
ADD CONSTRAINT `fk_pengeluaran_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun_anggaran` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pengeluaran_ibfk_2` FOREIGN KEY (`kode_rekening_id`) REFERENCES `kode_rekening` (`id`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `fk_users_tahun` FOREIGN KEY (`tahun_aktif`) REFERENCES `tahun_anggaran` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
