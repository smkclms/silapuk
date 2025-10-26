<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportUser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Expenditure_model');
        $this->load->library('tcpdf');
        $this->load->library('PHPExcel_Lib');
        $this->load->library('session');

        // Cegah akses jika bukan user biasa
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        $role = strtolower($this->session->userdata('role'));
        if (in_array($role, ['bendahara', 'superadmin'])) {
            redirect('dashboard/bendahara');
        }
    }

    // =======================
    //  TAMPILAN UTAMA LAPORAN
    // =======================
    public function index() {
        $data['content'] = $this->load->view('user/report_user_view', [], TRUE);
        $this->load->view('layouts/user_layout', $data);
    }

    // =======================
    //  GENERATE LAPORAN BULANAN
    // =======================
    public function generate() {
        $year  = $this->input->post('year');
        $month = $this->input->post('month');
        $user_id = $this->session->userdata('user_id');

        if (!$year || !$month) {
            show_error('Parameter tahun dan bulan diperlukan.');
        }

        $user = $this->User_model->get_user_by_id($user_id);
        $expenditures = $this->db
            ->where('user_id', $user_id)
            ->where('YEAR(tanggal_pengeluaran)', $year)
            ->where('MONTH(tanggal_pengeluaran)', $month)
            ->get('pengeluaran')
            ->result();

        // Hitung total per tanggal
        $monthly_expenditure = [];
        foreach ($expenditures as $ex) {
            $tgl = date('d-m-Y', strtotime($ex->tanggal_pengeluaran));
            if (!isset($monthly_expenditure[$tgl])) {
                $monthly_expenditure[$tgl] = 0;
            }
            $monthly_expenditure[$tgl] += $ex->jumlah_pengeluaran;
        }

        $data = [
            'monthly_expenditure' => $monthly_expenditure,
            'year' => $year,
            'month' => $month,
            'user' => $user
        ];

        $view = $this->load->view('user/report_user_view', $data, TRUE);
        $this->load->view('layouts/user_layout', ['content' => $view]);
    }

    // =======================
    //  CETAK PDF
    // =======================
    public function pdf() {
        $year  = $this->input->post('year');
        $month = $this->input->post('month');
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);

        $bulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];
        $nama_bulan = $bulan[(int)$month];

        $expenditures = $this->db
            ->where('user_id', $user_id)
            ->where('YEAR(tanggal_pengeluaran)', $year)
            ->where('MONTH(tanggal_pengeluaran)', $month)
            ->get('pengeluaran')
            ->result();

        $pdf = new Tcpdf();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, "Laporan Pengeluaran Bulan $nama_bulan $year", 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 11);

        $html = '<h4>Nama Pengguna: '.htmlspecialchars($user->nama_lengkap).'</h4>';
        $html .= '<table border="1" cellpadding="5">
                    <tr style="background-color:#007bff;color:white;">
                        <th>Tanggal</th>
                        <th>Jumlah Pengeluaran</th>
                        <th>Keterangan</th>
                    </tr>';

        $total = 0;
        foreach ($expenditures as $ex) {
            $html .= '<tr>
                        <td>'.date('d-m-Y', strtotime($ex->tanggal_pengeluaran)).'</td>
                        <td style="text-align:right;">Rp '.number_format($ex->jumlah_pengeluaran,0,',','.').'</td>
                        <td>'.htmlspecialchars($ex->keterangan).'</td>
                      </tr>';
            $total += $ex->jumlah_pengeluaran;
        }

        $html .= '<tr style="font-weight:bold;background:#f2f2f2;">
                    <td>Total</td>
                    <td style="text-align:right;">Rp '.number_format($total,0,',','.').'</td>
                    <td></td>
                  </tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('Laporan_Pengeluaran_'.$user->nama_lengkap.'.pdf', 'I');
    }

    // =======================
    //  EXPORT KE EXCEL
    // =======================
    public function excel() {
        $year  = $this->input->post('year');
        $month = $this->input->post('month');
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);

        $bulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];
        $nama_bulan = $bulan[(int)$month];

        $expenditures = $this->db
            ->where('user_id', $user_id)
            ->where('YEAR(tanggal_pengeluaran)', $year)
            ->where('MONTH(tanggal_pengeluaran)', $month)
            ->get('pengeluaran')
            ->result();

        $excel = new PHPExcel_Lib();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Laporan Pengeluaran');

        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', "Laporan Pengeluaran Bulan $nama_bulan $year");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Tanggal');
        $sheet->setCellValue('B3', 'Jumlah Pengeluaran');
        $sheet->setCellValue('C3', 'Keterangan');
        $sheet->getStyle('A3:C3')->getFont()->setBold(true);

        $row = 4;
        $total = 0;
        foreach ($expenditures as $ex) {
            $sheet->setCellValue('A'.$row, date('d-m-Y', strtotime($ex->tanggal_pengeluaran)));
            $sheet->setCellValue('B'.$row, $ex->jumlah_pengeluaran);
            $sheet->setCellValue('C'.$row, $ex->keterangan);
            $total += $ex->jumlah_pengeluaran;
            $row++;
        }

        $sheet->setCellValue('A'.$row, 'Total');
        $sheet->setCellValue('B'.$row, $total);
        $sheet->getStyle('A'.$row.':B'.$row)->getFont()->setBold(true);

        foreach (['A', 'B', 'C'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Laporan_Pengeluaran_'.$user->nama_lengkap.'_'.$nama_bulan.'_'.$year.'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit;
    }
}
