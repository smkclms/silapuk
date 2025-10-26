<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardUser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Expenditure_model');
        $this->load->model('Anggaran_model');
        $this->load->model('User_model');
        $this->load->library('session');

        // ✅ Pastikan user login
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        // ✅ Hanya role 'pengguna' yang boleh masuk
        $role = strtolower($this->session->userdata('role'));
        if ($role !== 'pengguna') {
            redirect('dashboard/bendahara');
        }
    }

    public function index() {
    $user_id   = $this->session->userdata('user_id');
    $tahun_id  = $this->session->userdata('tahun_id');
    $tahun     = $this->session->userdata('tahun_anggaran');

    // ✅ Tangani kasus session kosong setelah login pertama
    if (empty($tahun)) {
        $tahun = '-';
    }

    // ✅ Ambil total anggaran user
    $anggaran_list = $this->Anggaran_model->get_anggaran_by_user($user_id);
    $total_anggaran = 0;
    foreach ($anggaran_list as $a) {
        $total_anggaran += $a->jumlah_anggaran;
    }

    // ✅ Ambil total pengeluaran user
    $expenditures_user = $this->Expenditure_model->get_expenditures_by_user($user_id);
    $total_pengeluaran = 0;
    foreach ($expenditures_user as $ex) {
        $total_pengeluaran += is_array($ex) ? $ex['jumlah_pengeluaran'] : $ex->jumlah_pengeluaran;
    }

    $sisa_anggaran = $total_anggaran - $total_pengeluaran;

    // ✅ Data untuk grafik
    $chart_data = [];
    foreach ($expenditures_user as $ex) {
        $tanggal = date('d-m-Y', strtotime($ex->tanggal_pengeluaran));
        if (!isset($chart_data[$tanggal])) $chart_data[$tanggal] = 0;
        $chart_data[$tanggal] += $ex->jumlah_pengeluaran;
    }

    // ✅ Data dikirim ke view
    $content_data = [
        'tahun_aktif' => $tahun,
        'total_pagu' => $total_anggaran,
        'total_disalurkan' => $total_anggaran,
        'total_dibelanjakan' => $total_pengeluaran,
        'sisa_anggaran' => $sisa_anggaran,
        'chart_data' => $chart_data,
        'user' => $this->User_model->get_user_by_id($user_id),
    ];

    $data['content'] = $this->load->view('user/dashboard_user', $content_data, TRUE);
    $this->load->view('layouts/user_layout', $data);
}


}
