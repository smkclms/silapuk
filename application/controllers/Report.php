<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Expenditure_model');
        $this->load->library('tcpdf');
        $this->load->library('PHPExcel_Lib');
    }

    // Halaman utama laporan dengan form filter
    public function index() {
        $data['users'] = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);
        $this->load->view('report_view', $data);
    }

    // Generate rekap pengeluaran bulanan (jumlah per pengguna)
    public function generate() {
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        $user_id = $this->session->userdata('user_id');
        $user_role = strtolower($this->session->userdata('role'));

        if ($user_role === 'bendahara') {
            $users = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);
        } else {
            $users = [$this->User_model->get_user_by_id($user_id)];
        }

        $monthly_expenditure = [];
        foreach ($users as $user) {
            if (!$user) continue;
            $total = $this->Expenditure_model->get_monthly_expenditure($year, $month, $user->id);
            $monthly_expenditure[$user->nama_lengkap] = $total;
        }

        $data = [
            'monthly_expenditure' => $monthly_expenditure,
            'year' => $year,
            'month' => $month
        ];

        $this->load->view('report_view', $data);
    }

    // Generate PDF rekap pengeluaran bulanan
    public function pdf() {
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        if (!$year || !$month) {
            show_error('Parameter tahun dan bulan diperlukan.');
        }

        $user_id = $this->session->userdata('user_id');
        $user_role = strtolower($this->session->userdata('role'));

        if ($user_role === 'bendahara') {
            $users = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);
        } else {
            $users = [$this->User_model->get_user_by_id($user_id)];
        }

        $monthly_expenditure = [];
        $total_pengeluaran = 0;
        foreach ($users as $user) {
            if (!$user) continue;
            $total = $this->Expenditure_model->get_monthly_expenditure($year, $month, $user->id);
            $monthly_expenditure[$user->nama_lengkap] = $total;
            $total_pengeluaran += $total;
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $nama_bulan = isset($bulan[(int)$month]) ? $bulan[(int)$month] : '';

        $pdf = new Tcpdf();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, "Laporan Pengeluaran Bulan $nama_bulan $year", 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 12);

        $html = '<table border="1" cellpadding="4" cellspacing="0" style="border-collapse: collapse;">'
              . '<tr style="background-color:#007bff;color:#fff;">'
              . '<th>Nama</th><th>Pengeluaran</th></tr>';

        foreach ($monthly_expenditure as $nama => $total) {
            $html .= "<tr><td>" . htmlspecialchars($nama) . "</td>"
                   . "<td style='text-align:right;'>Rp " . number_format($total, 0, ',', '.') . "</td></tr>";
        }

        $html .= '<tr style="background-color:#f2f2f2;font-weight:bold;">'
               . '<td>Total</td>'
               . '<td style="text-align:right;">Rp ' . number_format($total_pengeluaran, 0, ',', '.') . '</td>'
               . '</tr>';

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('laporan_pengeluaran.pdf', 'I');
    }

    // Export Excel rekap pengeluaran bulanan
    public function excel() {
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        if (!$year || !$month) {
            show_error('Parameter tahun dan bulan diperlukan.');
        }

        $user_id = $this->session->userdata('user_id');
        $user_role = strtolower($this->session->userdata('role'));

        if ($user_role === 'bendahara') {
            $users = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);
        } else {
            $users = [$this->User_model->get_user_by_id($user_id)];
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $nama_bulan = isset($bulan[(int)$month]) ? $bulan[(int)$month] : '';

        $this->load->library('PHPExcel_Lib');
        $excel = new PHPExcel_Lib();

        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Laporan Pengeluaran');

        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', "Total Pengeluaran Bulan $nama_bulan $year");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getRowDimension('1')->setRowHeight(30);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Nama Pengguna');
        $sheet->setCellValue('B3', 'Total Pengeluaran');

        $row = 4;
        $total_pengeluaran = 0;
        foreach ($users as $user) {
            if (!$user) continue;
            $total = $this->Expenditure_model->get_monthly_expenditure($year, $month, $user->id);
            $sheet->setCellValue('A' . $row, $user->nama_lengkap);
            $sheet->setCellValue('B' . $row, $total);
            $total_pengeluaran += $total;
            $row++;
        }

        $sheet->setCellValue('A' . $row, 'Total');
        $sheet->setCellValue('B' . $row, $total_pengeluaran);

        $sheet->getStyle('B4:B' . $row)
              ->getNumberFormat()
              ->setFormatCode('#,##0');

        $sheet->getStyle('A3:B3')->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        $filename = 'Laporan Pengeluaran Bulan ' . $nama_bulan . ' ' . $year . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit;
    }

    // Laporan detail barang dibelanjakan per pengguna - PDF
    public function pdf_items() {
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        if (!$year || !$month) {
            show_error('Parameter tahun dan bulan diperlukan.');
        }

        $user_id = $this->session->userdata('user_id');
        $user_role = strtolower($this->session->userdata('role'));

        if ($user_role === 'bendahara') {
            $users = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);
        } else {
            $users = [$this->User_model->get_user_by_id($user_id)];
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $nama_bulan = isset($bulan[(int)$month]) ? $bulan[(int)$month] : '';

        $pdf = new Tcpdf();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, "Laporan Barang Dibeli Bulan $nama_bulan $year", 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 11);

        foreach ($users as $user) {
            if (!$user) continue;

            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, "Pengguna: " . $user->nama_lengkap, 0, 1);
            $pdf->SetFont('helvetica', '', 11);

            $items = $this->Expenditure_model->get_items_by_user_month($user->id, $year, $month);

            if (empty($items)) {
                $pdf->Cell(0, 8, 'Tidak ada data pengeluaran.', 0, 1);
                $pdf->Ln(5);
                continue;
            }

            $tbl = '<table border="1" cellpadding="4">
                        <tr style="background-color:#007bff;color:#fff;">
                            <th>Nama Barang</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                        </tr>';

            $total_user = 0;
            foreach ($items as $item) {
                $tbl .= '<tr>
                            <td>' . htmlspecialchars($item->item_name) . '</td>
                            <td style="text-align:center;">' . $item->quantity . '</td>
                            <td style="text-align:right;">Rp ' . number_format($item->price, 0, ',', '.') . '</td>
                            <td style="text-align:right;">Rp ' . number_format($item->total_price, 0, ',', '.') . '</td>
                            <td style="text-align:center;">' . date('d-m-Y', strtotime($item->date)) . '</td>
                         </tr>';
                $total_user += $item->total_price;
            }

            $tbl .= '<tr style="font-weight:bold;">
                        <td colspan="3" style="text-align:right;">Total</td>
                        <td style="text-align:right;">Rp ' . number_format($total_user, 0, ',', '.') . '</td>
                        <td></td>
                     </tr>';

            $tbl .= '</table><br><br>';

            $pdf->writeHTML($tbl, true, false, false, false, '');
        }

        $pdf->Output('laporan_barang_bulanan.pdf', 'I');
    }

    // Laporan detail barang dibelanjakan per pengguna - Excel
    public function excel_items() {
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        if (!$year || !$month) {
            show_error('Parameter tahun dan bulan diperlukan.');
        }

        $user_id = $this->session->userdata('user_id');
        $user_role = strtolower($this->session->userdata('role'));

        if ($user_role === 'bendahara') {
            $users = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);
        } else {
            $users = [$this->User_model->get_user_by_id($user_id)];
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $nama_bulan = isset($bulan[(int)$month]) ? $bulan[(int)$month] : '';

        $this->load->library('PHPExcel_Lib');
        $excel = new PHPExcel_Lib();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('Laporan Barang');

        $row = 1;
        $sheet->setCellValue('A' . $row, "Laporan Barang Dibeli Bulan $nama_bulan $year");
        $sheet->mergeCells("A$row:E$row");
        $sheet->getStyle("A$row")->getFont()->setBold(true)->setSize(14);
        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->getStyle("A$row")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $row += 2;

        foreach ($users as $user) {
            if (!$user) continue;

            $sheet->setCellValue('A' . $row, "Pengguna: " . $user->nama_lengkap);
            $sheet->getStyle("A$row")->getFont()->setBold(true);
            $row++;

            $sheet->setCellValue('A' . $row, 'Nama Barang');
            $sheet->setCellValue('B' . $row, 'Qty');
            $sheet->setCellValue('C' . $row, 'Harga Satuan');
            $sheet->setCellValue('D' . $row, 'Total Harga');
            $sheet->setCellValue('E' . $row, 'Tanggal');
            $sheet->getStyle("A$row:E$row")->getFont()->setBold(true);
            $row++;

            $items = $this->Expenditure_model->get_items_by_user_month($user->id, $year, $month);

            if (empty($items)) {
                $sheet->setCellValue('A' . $row, 'Tidak ada data pengeluaran.');
                $row += 2;
                continue;
            }

            $total_user = 0;
            foreach ($items as $item) {
                $sheet->setCellValue('A' . $row, $item->item_name);
                $sheet->setCellValue('B' . $row, $item->quantity);
                $sheet->setCellValue('C' . $row, $item->price);
                $sheet->setCellValue('D' . $row, $item->total_price);
                $sheet->setCellValue('E' . $row, date('d-m-Y', strtotime($item->date)));
                $total_user += $item->total_price;
                $row++;
            }

            $sheet->setCellValue('C' . $row, 'Total');
            $sheet->setCellValue('D' . $row, $total_user);
            $sheet->getStyle("C$row:D$row")->getFont()->setBold(true);
            $row += 2;
        }

        $sheet->getStyle('C4:D' . $row)
              ->getNumberFormat()
              ->setFormatCode('#,##0');

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Laporan_Barang_Bulanan_{$nama_bulan}_{$year}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit;
    }
    
}
