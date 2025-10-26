<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnggaranUser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('SumberAnggaran_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);

        // Pastikan user sudah login
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        // Hanya untuk role 'pengguna'
        $role = strtolower($this->session->userdata('role'));
        if ($role !== 'pengguna') {
            redirect('dashboard/bendahara');
        }
    }

    public function index() {
        // Ambil tahun aktif & id tahun dari session
        $tahun_aktif = $this->session->userdata('tahun_anggaran');
        $tahun_id    = $this->session->userdata('tahun_id');
        $user_id     = $this->session->userdata('user_id');

        // Cegah akses jika session kosong
        if (!$tahun_aktif || !$tahun_id) {
            $this->session->set_flashdata('error', 'Silakan login ulang, tahun anggaran tidak ditemukan.');
            redirect('auth/login');
        }

        // Ambil data anggaran user + sumber anggaran
        $anggaran = $this->Anggaran_model->get_anggaran_user_with_sumber($user_id);
        $sumber   = $this->SumberAnggaran_model->get_all_sumber();

        // Gunakan layout user
        $data['content'] = $this->load->view('user/anggaran_user_view', [
            'anggaran' => $anggaran,
            'sumber_anggaran' => $sumber,
            'tahun_aktif' => $tahun_aktif
        ], TRUE);

        $this->load->view('layouts/user_layout', $data);
    }

    public function add() {
        $user_id         = $this->session->userdata('user_id');
        $tahun_id        = $this->session->userdata('tahun_id');
        $tahun           = $this->session->userdata('tahun_anggaran');
        $jumlah_anggaran = $this->input->post('jumlah_anggaran');
        $sumber_id       = $this->input->post('sumber_id');
        $keterangan      = $this->input->post('keterangan');

        // Buat array data
        $data = [
            'user_id'         => $user_id,
            'sumber_id'       => $sumber_id,
            'jumlah_anggaran' => $jumlah_anggaran,
            'keterangan'      => $keterangan,
            'tahun'           => $tahun,
            'tahun_id'        => $tahun_id,
            'created_at'      => date('Y-m-d H:i:s')
        ];

        // Simpan ke database
        $this->Anggaran_model->create_anggaran($data);

        $this->session->set_flashdata('success', 'Anggaran berhasil ditambahkan.');
        redirect('user/anggaranuser');
    }

    public function delete($id) {
        $user_id  = $this->session->userdata('user_id');
        $anggaran = $this->Anggaran_model->get_anggaran_by_id($id);

        // Pastikan user hanya bisa hapus miliknya
        if ($anggaran && $anggaran->user_id == $user_id) {
            $this->Anggaran_model->delete_anggaran($id);
            $this->session->set_flashdata('success', 'Anggaran berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
        }

        redirect('user/anggaranuser');
    }
}
