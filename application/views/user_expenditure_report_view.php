<h2>Laporan Pengeluaran Pengguna</h2>

<form method="post" action="<?= site_url('userexpenditurereport/pdf_all'); ?>" target="_blank">
    <button type="submit">Cetak Laporan Seluruh Pengeluaran (PDF)</button>
</form>

<form method="post" action="<?= site_url('userexpenditurereport/excel_all'); ?>">
    <button type="submit">Export Laporan Seluruh Pengeluaran (Excel)</button>
</form>
