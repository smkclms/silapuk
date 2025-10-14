<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPenggunaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Pengeluaran_model');
        $this->load->library('tcpdf');
        $this->load->library('PHPExcel_Lib');
    }

    // Halaman laporan penggunaan (bendahara/superadmin)
    public function index() {
        $role = strtolower($this->session->userdata('role'));
        $user_id = $this->session->userdata('user_id');

        if ($role === 'bendahara' || $role === 'superadmin') {
            $data['users'] = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);

            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $filter_user_id = $this->input->get('user_id');

            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['user_id'] = $filter_user_id;

            $data['expenditures'] = $this->Pengeluaran_model->get_filtered_expenditures_with_rekening($start_date, $end_date, $filter_user_id);

            $this->load->view('laporan_penggunaan_view', $data);
        } else {
            redirect('dashboard/view');
        }
    }

    // Cetak PDF laporan penggunaan
    public function cetak_pdf() {
        $role = strtolower($this->session->userdata('role'));
        if (!in_array($role, ['bendahara', 'superadmin'])) {
            show_error('Anda tidak memiliki akses ke laporan ini.');
        }

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');

        $expenditures = $this->Pengeluaran_model->get_filtered_expenditures_with_rekening($start_date, $end_date, $user_id);

        $pdf = new Tcpdf();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, "Laporan Penggunaan Pengeluaran", 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 11);

        $html = '<table border="1" cellpadding="4" cellspacing="0" style="border-collapse: collapse;">';
        $html .= '<tr style="background-color:#007bff;color:#fff;">';
        $html .= '<th>Nomor</th><th>Nama Pengguna</th><th>Tanggal Pengeluaran</th><th>Jumlah</th><th>Kode Rekening</th><th>Keterangan</th></tr>';

        $no = 1;
        if (empty($expenditures)) {
            $html .= '<tr><td colspan="6" style="text-align:center;">Tidak ada data pengeluaran.</td></tr>';
        } else {
            foreach ($expenditures as $ex) {
                $user = $this->User_model->get_user_by_id($ex->user_id);
                $html .= '<tr>';
                $html .= '<td style="text-align:center;">' . $no++ . '</td>';
                $html .= '<td>' . ($user ? htmlspecialchars($user->nama_lengkap) : '-') . '</td>';
                $html .= '<td>' . date('d-m-Y', strtotime($ex->tanggal_pengeluaran)) . '</td>';
                $html .= '<td style="text-align:right;">Rp ' . number_format($ex->jumlah_pengeluaran, 0, ',', '.') . '</td>';
                $html .= '<td>' . htmlspecialchars($ex->kode_rekening_kode) . ' - ' . htmlspecialchars($ex->nama_rekening) . '</td>';
                $html .= '<td>' . htmlspecialchars($ex->keterangan) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('laporan_penggunaan.pdf', 'I');
    }

    // Export Excel laporan penggunaan
    public function export_excel() {
        $role = strtolower($this->session->userdata('role'));
        if (!in_array($role, ['bendahara', 'superadmin'])) {
            show_error('Anda tidak memiliki akses ke laporan ini.');
        }

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');

        $expenditures = $this->Pengeluaran_model->get_filtered_expenditures_with_rekening($start_date, $end_date, $user_id);

        $this->load->library('PHPExcel_Lib');
        $excel = new PHPExcel_Lib();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Laporan Penggunaan');

        $sheet->setCellValue('A1', 'Nomor');
        $sheet->setCellValue('B1', 'Nama Pengguna');
        $sheet->setCellValue('C1', 'Tanggal Pengeluaran');
        $sheet->setCellValue('D1', 'Jumlah Pengeluaran');
        $sheet->setCellValue('E1', 'Kode Rekening');
        $sheet->setCellValue('F1', 'Keterangan');

        $row = 2;
        $no = 1;
        foreach ($expenditures as $ex) {
            $user = $this->User_model->get_user_by_id($ex->user_id);
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $user ? $user->nama_lengkap : '-');
            $sheet->setCellValue('C' . $row, date('d-m-Y', strtotime($ex->tanggal_pengeluaran)));
            $sheet->setCellValue('D' . $row, $ex->jumlah_pengeluaran);
            $sheet->setCellValue('E' . $row, $ex->kode_rekening_kode . ' - ' . $ex->nama_rekening);
            $sheet->setCellValue('F' . $row, $ex->keterangan);
            $row++;
        }

        $sheet->getStyle('D2:D' . ($row - 1))
              ->getNumberFormat()
              ->setFormatCode('#,##0');

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Laporan_Penggunaan_" . date('Ymd_His') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit;
    }
}
