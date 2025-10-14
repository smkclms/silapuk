<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserExpenditureReport extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Pengeluaran_model');
        $this->load->library('tcpdf');
        $this->load->library('PHPExcel_Lib');
    }

    // Halaman utama laporan pengeluaran pengguna
    public function index() {
        $role = strtolower($this->session->userdata('role'));
        if (!in_array($role, ['bendahara', 'superadmin'])) {
            show_error('Anda tidak memiliki akses ke menu ini.');
        }

        $data['users'] = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);
        $this->load->view('user_expenditure_report_view', $data);
    }

    // Laporan PDF seluruh pengeluaran pengguna
    public function pdf_all() {
        $role = strtolower($this->session->userdata('role'));
        if (!in_array($role, ['bendahara', 'superadmin'])) {
            show_error('Anda tidak memiliki akses ke laporan ini.');
        }

        $users = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);

        $pdf = new Tcpdf();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, "Laporan Seluruh Pengeluaran Pengguna", 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 11);

        foreach ($users as $user) {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, "Pengguna: " . $user->nama_lengkap, 0, 1);
            $pdf->SetFont('helvetica', '', 11);

            $items = $this->Pengeluaran_model->get_all_by_user($user->id);

            if (empty($items)) {
                $pdf->Cell(0, 8, 'Tidak ada data pengeluaran.', 0, 1);
                $pdf->Ln(5);
                continue;
            }

            $tbl = '<table border="1" cellpadding="4">
                        <tr style="background-color:#007bff;color:#fff;">
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Kode Rekening</th>
                            <th>Jumlah Pengeluaran</th>
                        </tr>';

            $total_user = 0;
            foreach ($items as $item) {
                $tbl .= '<tr>
                            <td>' . date('d-m-Y', strtotime($item->tanggal_pengeluaran)) . '</td>
                            <td>' . htmlspecialchars($item->keterangan) . '</td>
                            <td style="text-align:center;">' . htmlspecialchars($item->kode_rekening_id) . '</td>
                            <td style="text-align:right;">Rp ' . number_format($item->jumlah_pengeluaran, 0, ',', '.') . '</td>
                         </tr>';
                $total_user += $item->jumlah_pengeluaran;
            }

            $tbl .= '<tr style="font-weight:bold;">
                        <td colspan="3" style="text-align:right;">Total</td>
                        <td style="text-align:right;">Rp ' . number_format($total_user, 0, ',', '.') . '</td>
                     </tr>';

            $tbl .= '</table><br><br>';

            $pdf->writeHTML($tbl, true, false, false, false, '');
        }

        $pdf->Output('laporan_seluruh_pengeluaran_pengguna.pdf', 'I');
    }

    // Laporan Excel seluruh pengeluaran pengguna
    public function excel_all() {
        $role = strtolower($this->session->userdata('role'));
        if (!in_array($role, ['bendahara', 'superadmin'])) {
            show_error('Anda tidak memiliki akses ke laporan ini.');
        }

        $users = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);

        $this->load->library('PHPExcel_Lib');
        $excel = new PHPExcel_Lib();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('Laporan Pengeluaran Pengguna');

        $row = 1;
        $sheet->setCellValue('A' . $row, "Laporan Seluruh Pengeluaran Pengguna");
        $sheet->mergeCells("A$row:D$row");
        $sheet->getStyle("A$row")->getFont()->setBold(true)->setSize(16);
        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->getStyle("A$row")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row += 2;

        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, "Pengguna: " . $user->nama_lengkap);
            $sheet->getStyle("A$row")->getFont()->setBold(true);
            $row++;

            $sheet->setCellValue('A' . $row, 'Tanggal');
            $sheet->setCellValue('B' . $row, 'Keterangan');
            $sheet->setCellValue('C' . $row, 'Kode Rekening');
            $sheet->setCellValue('D' . $row, 'Jumlah Pengeluaran');
            $sheet->getStyle("A$row:D$row")->getFont()->setBold(true);
            $row++;

            $items = $this->Pengeluaran_model->get_all_by_user($user->id);

            if (empty($items)) {
                $sheet->setCellValue('A' . $row, 'Tidak ada data pengeluaran.');
                $row += 2;
                continue;
            }

            $total_user = 0;
            foreach ($items as $item) {
                $sheet->setCellValue('A' . $row, date('d-m-Y', strtotime($item->tanggal_pengeluaran)));
                $sheet->setCellValue('B' . $row, $item->keterangan);
                $sheet->setCellValue('C' . $row, $item->kode_rekening_id);
                $sheet->setCellValue('D' . $row, $item->jumlah_pengeluaran);
                $total_user += $item->jumlah_pengeluaran;
                $row++;
            }

            $sheet->setCellValue('C' . $row, 'Total');
            $sheet->setCellValue('D' . $row, $total_user);
            $sheet->getStyle("C$row:D$row")->getFont()->setBold(true);
            $row += 2;
        }

        $sheet->getStyle('D3:D' . $row)
              ->getNumberFormat()
              ->setFormatCode('#,##0');

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Laporan_Seluruh_Pengeluaran_Pengguna.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit;
    }
}
