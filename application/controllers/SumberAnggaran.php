<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SumberAnggaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SumberAnggaran_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    // ✅ Halaman utama daftar sumber anggaran
    public function index() {
        // Ambil tahun aktif dari session
        $tahun_aktif = $this->session->userdata('tahun_anggaran');

        // Cegah akses jika session tahun kosong
        if (!$tahun_aktif) {
            $this->session->set_flashdata('error', 'Silakan login ulang, tahun anggaran tidak ditemukan.');
            redirect('auth/login');
        }

        // Ambil data sumber anggaran berdasarkan tahun aktif
        $data['sumber'] = $this->SumberAnggaran_model->get_all_sumber();
        $data['tahun_aktif'] = $tahun_aktif;

        $this->load->view('sumber_anggaran_view', $data);
    }

    // ✅ Tambah sumber baru
    public function add() {
        $tahun_aktif = $this->session->userdata('tahun_anggaran');

        if (!$tahun_aktif) {
            $this->session->set_flashdata('error', 'Sesi tahun anggaran tidak ditemukan. Silakan login ulang.');
            redirect('auth/login');
        }

        $data = [
            'nama_sumber' => $this->input->post('nama_sumber'),
            'jumlah'      => $this->input->post('jumlah'),
            'tahun'       => $tahun_aktif, // simpan tahun login aktif
            'created_at'  => date('Y-m-d H:i:s')
        ];

        $this->SumberAnggaran_model->create_sumber($data);
        redirect('sumberanggaran');
    }

    // ✅ Edit data
    public function edit($id = null) {
        if (!$id) redirect('sumberanggaran');

        $data['item'] = $this->SumberAnggaran_model->get_sumber_by_id($id);
        if (!$data['item']) show_404();

        $this->load->view('sumberanggaran_edit', $data);
    }

    // ✅ Proses update data
    public function update($id = null) {
        if (!$id) redirect('sumberanggaran');

        $data = [
            'nama_sumber' => $this->input->post('nama_sumber'),
            'jumlah'      => $this->input->post('jumlah')
        ];

        $this->SumberAnggaran_model->update_sumber($id, $data);
        redirect('sumberanggaran');
    }

    // ✅ Hapus data
    public function delete($id = null) {
        if (!$id) redirect('sumberanggaran');

        $this->SumberAnggaran_model->delete_sumber($id);
        redirect('sumberanggaran');
    }
}
