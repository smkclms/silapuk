<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserDashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Expenditure_model');
        $this->load->model('Anggaran_model');
        $this->load->model('User_model');
        $this->load->helper('url');
        $this->load->library('session');

        // Pastikan user sudah login
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        // Batasi hanya untuk role "pengguna" (bukan admin/bendahara)
        $role = strtolower($this->session->userdata('role'));
        if (!in_array($role, ['pengguna', 'user', 'pegawai', 'guru'])) {
            redirect('dashboard/bendahara');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');

        // Ambil data user
        $user = $this->User_model->get_user_by_id($user_id);
        $anggaran = $this->Anggaran_model->get_anggaran_by_user($user_id);
        $expenditures = $this->Expenditure_model->get_expenditures_by_user($user_id);

        // Hitung total
        $total_anggaran = !empty($anggaran) ? $anggaran[0]->jumlah_anggaran : 0;
        $total_pengeluaran = 0;
        foreach ($expenditures as $ex) {
            $total_pengeluaran += is_array($ex)
                ? $ex['jumlah_pengeluaran']
                : $ex->jumlah_pengeluaran;
        }
        $sisa = $total_anggaran - $total_pengeluaran;

        // Siapkan data untuk view
        $view_data = [
            'user' => $user,
            'anggaran' => $anggaran,
            'expenditures' => $expenditures,
            'total_anggaran' => $total_anggaran,
            'total_pengeluaran' => $total_pengeluaran,
            'sisa' => $sisa
        ];

        // Tampilkan dalam layout user
        $data['content'] = $this->load->view('user/dashboard_user', $view_data, TRUE);
        $this->load->view('layouts/user_layout', $data);
    }
}
