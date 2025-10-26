<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    public function index() {
    // Ambil tahun aktif & id tahun dari session
    $tahun_aktif = $this->session->userdata('tahun_anggaran');
    $tahun_id    = $this->session->userdata('tahun_id');

    // Cegah akses jika session kosong
    if (!$tahun_aktif || !$tahun_id) {
        $this->session->set_flashdata('error', 'Silakan login ulang, tahun anggaran tidak ditemukan.');
        redirect('auth/login');
    }

    // Ambil data anggaran & user
    $anggaran = $this->Anggaran_model->get_all_anggaran();
    $users    = $this->User_model->get_all();

    // Gunakan layout bendahara
    $data['content_view'] = 'anggaran_view';
    $data['content_data'] = [
        'anggaran' => $anggaran,
        'users' => $users,
        'tahun_aktif' => $tahun_aktif
    ];
    $data['title'] = 'Data Anggaran';

    // Panggil layout utama
    $this->load->view('layouts/bendahara_layout', $data);
}


    public function add() {
        // Ambil dari form
        $user_id         = $this->input->post('user_id');
        $jumlah_anggaran = $this->input->post('jumlah_anggaran');
        $tahun           = $this->input->post('tahun');

        // Buat array data
        $data = [
            'user_id'         => $user_id,
            'jumlah_anggaran' => $jumlah_anggaran,
            'tahun'           => $tahun,
            'created_at'      => date('Y-m-d H:i:s')
        ];

        // Gunakan fungsi yang benar agar tahun_id otomatis ikut
        $this->Anggaran_model->create_anggaran($data);

        $this->session->set_flashdata('success', 'Data anggaran berhasil ditambahkan.');
        redirect('anggaran');
    }

    public function delete($id) {
        $this->Anggaran_model->delete_anggaran($id);
        $this->session->set_flashdata('success', 'Data anggaran berhasil dihapus.');
        redirect('anggaran');
    }
}
