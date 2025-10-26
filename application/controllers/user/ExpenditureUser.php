<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExpenditureUser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Expenditure_model');
        $this->load->model('KodeRekening_model');
        $this->load->model('SumberAnggaran_model');
        $this->load->model('KategoriPengeluaran_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');


        // Cek login
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        // Hanya role 'pengguna' yang boleh akses
        $role = strtolower($this->session->userdata('role'));
        if ($role !== 'pengguna') {
            redirect('dashboard/bendahara');
        }
    }

    public function index() {
    $this->load->library('pagination');
    $user_id = $this->session->userdata('user_id');

    // 🔹 Ambil nilai filter dari form GET
    $filter_sumber   = $this->input->get('sumber_id');
    $filter_kategori = $this->input->get('kategori_pengeluaran_id');

    // 🔹 Hitung total data sesuai filter
    $total_rows = $this->Expenditure_model->count_expenditures_by_user($user_id, $filter_sumber, $filter_kategori);

    // 🔹 Konfigurasi pagination
    $config['base_url'] = site_url('user/expenditureuser/index');
    $config['total_rows'] = $total_rows;
    $config['per_page'] = 10;
    $config['uri_segment'] = 4;

    // 🔹 Styling pagination Bootstrap
    $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    // 🔹 Halaman aktif
    $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

    // 🔹 Ambil data sesuai filter + pagination
    $data['expenditures'] = $this->Expenditure_model->get_expenditures_by_user_paginated(
        $user_id,
        $config['per_page'],
        $page,
        $filter_sumber,
        $filter_kategori
    );

    // 🔹 Dropdown
    $data['kode_rekening'] = $this->KodeRekening_model->get_all_kode_rekening();
    $data['sumber_anggaran'] = $this->SumberAnggaran_model->get_all_sumber();
    $data['kategori_pengeluaran'] = $this->KategoriPengeluaran_model->get_all();

    // 🔹 Kirim variabel filter ke view agar tidak undefined
    $data['filter_sumber'] = $filter_sumber;
    $data['filter_kategori'] = $filter_kategori;

    // 🔹 Pagination link
    $data['pagination'] = $this->pagination->create_links();

    // 🔹 Load view
    $data['content'] = $this->load->view('user/expenditure_user_view', $data, TRUE);
    $this->load->view('layouts/user_layout', $data);
}




    public function add() {
    $user_id = $this->session->userdata('user_id');
    $tahun_id = $this->session->userdata('tahun_id');

    // 🔹 Aturan validasi
    $this->form_validation->set_rules('tanggal_pengeluaran', 'Tanggal Pengeluaran', 'required');
    $this->form_validation->set_rules('jumlah_pengeluaran', 'Jumlah Pengeluaran', 'required|numeric|greater_than[0]');
    $this->form_validation->set_rules('kode_rekening_id', 'Kode Rekening', 'required|integer');
    $this->form_validation->set_rules('sumber_id', 'Sumber Anggaran', 'required|integer');
    $this->form_validation->set_rules('kategori_pengeluaran_id', 'Kategori Pengeluaran', 'required|integer');

    if ($this->form_validation->run() == FALSE) {
        // 🔹 Kembalikan ke halaman utama dengan error message
        $this->session->set_flashdata('error', validation_errors());
        redirect('user/expenditureuser');
    }

    // 🔹 Simpan data jika validasi lolos
    $data = [
        'user_id' => $user_id,
        'kode_rekening_id' => $this->input->post('kode_rekening_id'),
        'sumber_id' => $this->input->post('sumber_id'),
        'kategori_pengeluaran_id' => $this->input->post('kategori_pengeluaran_id'),
        'tanggal_pengeluaran' => $this->input->post('tanggal_pengeluaran'),
        'jumlah_pengeluaran' => $this->input->post('jumlah_pengeluaran'),
        'keterangan' => $this->input->post('keterangan'),
        'tahun_id' => $tahun_id,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $this->Expenditure_model->create_expenditure($data);
    $this->session->set_flashdata('success', 'Pengeluaran berhasil ditambahkan.');
    redirect('user/expenditureuser');
}

    public function delete($id) {
        $user_id = $this->session->userdata('user_id');
        $pengeluaran = $this->Expenditure_model->get_expenditure_by_id($id);

        if ($pengeluaran && $pengeluaran->user_id == $user_id) {
            $this->Expenditure_model->delete_expenditure($id);
            $this->session->set_flashdata('success', 'Data pengeluaran berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Anda tidak berhak menghapus data ini.');
        }

        redirect('user/expenditureuser');
    }

    // Import data Excel (sementara belum disesuaikan kolom baru)
    public function import() {
        $this->load->library(['upload', 'PHPExcel_Lib']);

        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 2048;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_import')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('user/expenditureuser');
        }

        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];

        include APPPATH.'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = PHPExcel_IOFactory::load($file_path);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $imported = 0;
        $skipped = 0;
        $insert = [];
        $tahun_id = $this->session->userdata('tahun_anggaran_id');

        foreach ($sheetData as $i => $row) {
            if ($i == 1) continue; // Lewati header

            $user_id        = $row['A'];
            $kode_rekening  = trim($row['B']);
            $tanggal        = $row['C'];
            $jumlah         = $row['D'];
            $keterangan     = $row['E'];

            // Cari kode rekening berdasarkan kode
            $kode_row = $this->db->get_where('kode_rekening', ['kode' => $kode_rekening])->row();

            if ($kode_row) {
                $insert[] = [
                    'user_id'             => $user_id,
                    'kode_rekening_id'    => $kode_row->id,
                    'tahun_id'            => $tahun_id,
                    'tanggal_pengeluaran' => $tanggal,
                    'jumlah_pengeluaran'  => $jumlah,
                    'keterangan'          => $keterangan,
                    'created_at'          => date('Y-m-d H:i:s')
                ];
                $imported++;
            } else {
                $skipped++;
            }
        }

        if (!empty($insert)) {
            $this->db->insert_batch('pengeluaran', $insert);
        }

        unlink($file_path);

        $msg = "Berhasil impor $imported data.";
        if ($skipped > 0) {
            $msg .= " $skipped data dilewati karena kode rekening tidak ditemukan.";
        }

        $this->session->set_flashdata('success', $msg);
        redirect('user/expenditureuser');
    }
    public function edit($id)
{
    $user_id = $this->session->userdata('user_id');
    $pengeluaran = $this->Expenditure_model->get_expenditure_by_id($id);

    // Validasi agar user hanya bisa edit datanya sendiri
    if (!$pengeluaran || $pengeluaran->user_id != $user_id) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan atau bukan milik Anda.');
        redirect('user/expenditureuser');
    }

    // Ambil data tambahan untuk dropdown
    $data['pengeluaran'] = $pengeluaran;
    $data['kode_rekening'] = $this->KodeRekening_model->get_all_kode_rekening();
    $data['sumber_anggaran'] = $this->SumberAnggaran_model->get_all();
    $data['kategori_pengeluaran'] = $this->KategoriPengeluaran_model->get_all();

    $data['content'] = $this->load->view('user/expenditure_user_edit', $data, TRUE);
    $this->load->view('layouts/user_layout', $data);
}

public function update($id) {
    $user_id = $this->session->userdata('user_id');
    $pengeluaran = $this->Expenditure_model->get_expenditure_by_id($id);

    if (!$pengeluaran || $pengeluaran->user_id != $user_id) {
        $this->session->set_flashdata('error', 'Anda tidak berhak mengubah data ini.');
        redirect('user/expenditureuser');
    }

    // 🔹 Validasi
    $this->form_validation->set_rules('tanggal_pengeluaran', 'Tanggal Pengeluaran', 'required');
    $this->form_validation->set_rules('jumlah_pengeluaran', 'Jumlah Pengeluaran', 'required|numeric|greater_than[0]');
    $this->form_validation->set_rules('kode_rekening_id', 'Kode Rekening', 'required|integer');
    $this->form_validation->set_rules('sumber_id', 'Sumber Anggaran', 'required|integer');
    $this->form_validation->set_rules('kategori_pengeluaran_id', 'Kategori Pengeluaran', 'required|integer');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('user/expenditureuser/edit/'.$id);
    }

    // 🔹 Update data
    $data = [
        'tanggal_pengeluaran' => $this->input->post('tanggal_pengeluaran'),
        'jumlah_pengeluaran' => $this->input->post('jumlah_pengeluaran'),
        'kode_rekening_id' => $this->input->post('kode_rekening_id'),
        'sumber_id' => $this->input->post('sumber_id'),
        'kategori_pengeluaran_id' => $this->input->post('kategori_pengeluaran_id'),
        'keterangan' => $this->input->post('keterangan'),
    ];

    $this->Expenditure_model->update_expenditure($id, $data);
    $this->session->set_flashdata('success', 'Data pengeluaran berhasil diperbarui.');
    redirect('user/expenditureuser');
}


}
