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
        $sumber = $this->SumberAnggaran_model->get_all_sumber();

        // Gunakan layout bendahara (sidebar + konten tengah)
        $data['content_view'] = 'sumber_anggaran_view';
        $data['content_data'] = [
            'sumber' => $sumber,
            'tahun_aktif' => $tahun_aktif
        ];
        $data['title'] = 'Sumber Anggaran';

        $this->load->view('layouts/bendahara_layout', $data);
    }

    // ✅ Tambah sumber baru
    public function add() {
        $tahun_aktif = $this->session->userdata('tahun_anggaran');

        if (!$tahun_aktif) {
            $this->session->set_flashdata('error', 'Sesi tahun anggaran tidak ditemukan. Silakan login ulang.');
            redirect('auth/login');
        }

        $nama_sumber = $this->input->post('nama_sumber');
        $jumlah      = $this->input->post('jumlah');

        // 🧩 Jika jumlah kosong, isi otomatis 0
        if ($jumlah === '' || $jumlah === null) {
            $jumlah = 0;
        }

        $data = [
            'nama_sumber' => $nama_sumber,
            'jumlah'      => $jumlah,
            'tahun'       => $tahun_aktif,
            'created_at'  => date('Y-m-d H:i:s')
        ];

        $this->SumberAnggaran_model->create_sumber($data);
        $this->session->set_flashdata('success', 'Sumber anggaran berhasil ditambahkan.');
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

        $nama_sumber = $this->input->post('nama_sumber');
        $jumlah      = $this->input->post('jumlah');

        // 🧩 Jika jumlah kosong saat update, tetap isi 0
        if ($jumlah === '' || $jumlah === null) {
            $jumlah = 0;
        }

        $data = [
            'nama_sumber' => $nama_sumber,
            'jumlah'      => $jumlah
        ];

        $this->SumberAnggaran_model->update_sumber($id, $data);
        $this->session->set_flashdata('success', 'Data sumber anggaran berhasil diperbarui.');
        redirect('sumberanggaran');
    }

    // ✅ Hapus data
    public function delete($id = null) {
        if (!$id) redirect('sumberanggaran');

        $this->SumberAnggaran_model->delete_sumber($id);
        $this->session->set_flashdata('success', 'Data sumber anggaran berhasil dihapus.');
        redirect('sumberanggaran');
    }
}
