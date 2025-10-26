<div style="margin-left: 10px; margin-right: 10px;">

    <h2 style="margin-top:0;"><i class="fa fa-wallet"></i> Anggaran Saya</h2>

    <!-- Form Tambah Anggaran -->
    <form action="<?= site_url('user/anggaranuser/add'); ?>" method="post" style="margin-bottom:25px;">
        <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
            <div>
                <label><strong>Sumber Anggaran:</strong></label><br>
                <select name="sumber_id" required style="padding:6px;border:1px solid #ccc;border-radius:4px;">
                    <option value="">-- Pilih Sumber --</option>
                    <?php foreach ($sumber_anggaran as $s): ?>
                        <option value="<?= $s->id; ?>"><?= htmlspecialchars($s->nama_sumber); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label><strong>Jumlah (Rp):</strong></label><br>
                <input type="number" name="jumlah_anggaran" min="0" step="0.01" required
                       style="padding:6px;border:1px solid #ccc;border-radius:4px;">
            </div>

            <div>
                <label><strong>Keterangan:</strong></label><br>
                <input type="text" name="keterangan" placeholder="Opsional"
                       style="padding:6px;border:1px solid #ccc;border-radius:4px;width:200px;">
            </div>

            <button type="submit" 
                    style="background:#007bff;color:white;border:none;padding:8px 14px;border-radius:4px;cursor:pointer;">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </form>

    <!-- Tabel Data Anggaran -->
    <?php if (empty($anggaran)): ?>
        <div class="card">
            <p>Belum ada anggaran yang Anda tambahkan untuk tahun <?= $tahun_aktif; ?>.</p>
        </div>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;margin-top:10px;">
                <thead>
                    <tr style="background:#007bff;color:white;">
                        <th style="padding:8px;">No</th>
                        <th style="padding:8px;">Sumber</th>
                        <th style="padding:8px;">Jumlah (Rp)</th>
                        <th style="padding:8px;">Keterangan</th>
                        <th style="padding:8px;">Tahun</th>
                        <th style="padding:8px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($anggaran as $a): ?>
                    <tr style="border-bottom:1px solid #ddd;">
                        <td style="padding:6px;text-align:center;"><?= $no++; ?></td>
                        <td style="padding:6px;"><?= htmlspecialchars(isset($a->nama_sumber) ? $a->nama_sumber : '-'); ?></td>
                        <td style="padding:6px;">Rp <?= number_format($a->jumlah_anggaran, 0, ',', '.'); ?></td>
                        <td style="padding:6px;"><?= htmlspecialchars(isset($a->keterangan) ? $a->keterangan : '-'); ?></td>
                        <td style="padding:6px;text-align:center;"><?= $a->tahun; ?></td>
                        <td style="padding:6px;text-align:center;">
                            <a href="<?= site_url('user/anggaranuser/delete/' . $a->id); ?>"
                               onclick="return confirm('Yakin ingin menghapus data ini?')"
                               style="color:white;background:#dc3545;padding:4px 8px;border-radius:4px;text-decoration:none;">
                               <i class="fa fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
