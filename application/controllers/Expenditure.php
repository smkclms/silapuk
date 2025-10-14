<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenditure extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Budget_model');
        $this->load->model('Expenditure_model'); // Model pengeluaran
        $this->load->model('KodeRekening_model'); // Model kode rekening
        $this->load->helper('url');
        $this->load->library(['session', 'pagination']);

        // Pastikan user login
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index($page = 0) {
        $limit = 12;

        // Ambil tahun aktif dari session
        $tahun_id = $this->session->userdata('tahun_id');
        if (!$tahun_id) {
            $this->session->set_flashdata('error', 'Tahun anggaran belum dipilih. Silakan login ulang.');
            redirect('auth/login');
        }

        // Konfigurasi pagination
        $config['base_url'] = site_url('expenditure/index');
        $config['total_rows'] = $this->Expenditure_model->count_all_expenditures($tahun_id);
        $config['per_page'] = $limit;
        $config['uri_segment'] = 3;

        // Styling pagination Bootstrap 5
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        // Ambil data pengeluaran per tahun aktif
        $data['expenditures'] = $this->Expenditure_model->get_expenditures_with_kodering($limit, $page, $tahun_id);
        $data['users'] = $this->User_model->get_all_users();
        $data['kode_rekening'] = $this->KodeRekening_model->get_all_kode_rekening();
        $data['pagination'] = $this->pagination->create_links();
        $data['tahun_id'] = $tahun_id;

        $this->load->view('expenditure_view', $data);
    }

    // Tambah data pengeluaran
    public function add() {
        $tahun_id = $this->session->userdata('tahun_id');
        if (!$tahun_id) {
            $this->session->set_flashdata('error', 'Tahun anggaran tidak ditemukan.');
            redirect('auth/login');
        }

        $data = [
            'user_id' => $this->input->post('user_id'),
            'tahun_id' => $tahun_id,
            'tanggal_pengeluaran' => $this->input->post('tanggal_pengeluaran'),
            'jumlah_pengeluaran' => $this->input->post('jumlah_pengeluaran'),
            'keterangan' => $this->input->post('keterangan'),
            'kode_rekening_id' => $this->input->post('kode_rekening_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->Expenditure_model->create_expenditure($data);
        redirect('expenditure');
    }

    // Edit pengeluaran
    public function edit($id) {
        $data['expenditure'] = $this->Expenditure_model->get_expenditure_by_id($id);
        $data['users'] = $this->User_model->get_all_users();
        $data['kode_rekening'] = $this->KodeRekening_model->get_all_kode_rekening();

        if (!$data['expenditure']) {
            show_404();
        }

        $this->load->view('expenditure_edit_view', $data);
    }

    // Update pengeluaran
    public function update($id) {
        $data = [
            'user_id' => $this->input->post('user_id'),
            'tanggal_pengeluaran' => $this->input->post('tanggal_pengeluaran'),
            'jumlah_pengeluaran' => $this->input->post('jumlah_pengeluaran'),
            'keterangan' => $this->input->post('keterangan'),
            'kode_rekening_id' => $this->input->post('kode_rekening_id')
        ];

        $this->Expenditure_model->update_expenditure($id, $data);
        redirect('expenditure');
    }

    // Hapus pengeluaran
    public function delete($id) {
        $this->Expenditure_model->delete_expenditure($id);
        redirect('expenditure');
    }

    // Import data Excel
    public function import() {
        $this->load->library(['upload', 'PHPExcel_Lib']);

        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 2048;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_import')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('expenditure');
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
        redirect('expenditure');
    }
}
