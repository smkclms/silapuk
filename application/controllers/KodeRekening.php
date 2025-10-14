<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KodeRekening extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('KodeRekening_model');
        $this->load->library('pagination');

    }

    // Menampilkan daftar kode rekening
   public function index() {
    // Konfigurasi pagination
    $config['base_url'] = site_url('koderekening/index');
    $config['total_rows'] = $this->db->count_all('kode_rekening'); // total data
    $config['per_page'] = 10; // tampilkan 10 data per halaman
    $config['uri_segment'] = 3;

    // Styling pagination (bootstrap 5)
    $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['first_link'] = 'First';
    $config['last_link'] = 'Last';
    $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['first_tag_close'] = '</span></li>';
    $config['prev_link'] = '&laquo';
    $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['prev_tag_close'] = '</span></li>';
    $config['next_link'] = '&raquo';
    $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['next_tag_close'] = '</span></li>';
    $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['last_tag_close'] = '</span></li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['num_tag_close'] = '</span></li>';

    $this->pagination->initialize($config);

    $start = $this->uri->segment(3, 0);
    $data['kode_rekening'] = $this->db->get('kode_rekening', $config['per_page'], $start)->result();

    $data['pagination'] = $this->pagination->create_links();

    $this->load->view('kode_rekening_view', $data);
}


    // Menambahkan kode rekening baru
    public function add() {
        $data = array(
            'kode' => $this->input->post('kode'),
            'nama_rekening' => $this->input->post('nama_rekening')
        );
        $this->KodeRekening_model->create_kode_rekening($data);
        redirect('koderekening');
    }

    public function edit($id) {
    $data['kode_rekening'] = $this->KodeRekening_model->get_kode_rekening_by_id($id);

    // Cek apakah data ditemukan
    if (!$data['kode_rekening']) {
        show_404(); // atau redirect ke halaman lain
    }

    $this->load->view('edit_kode_rekening_view', $data);
}

public function update($id) {
    $data = array(
        'kode' => $this->input->post('kode'),
        'nama_rekening' => $this->input->post('nama_rekening')
    );

    $this->KodeRekening_model->update_kode_rekening($id, $data);
    redirect('koderekening');
}
public function delete($id) {
    $this->KodeRekening_model->delete_kode_rekening($id);
    redirect('koderekening');
}
public function import() {
    $this->load->library('upload');
    $this->load->library('PHPExcel_Lib');

    $config['upload_path']   = './uploads/';
    $config['allowed_types'] = 'xls|xlsx|csv';
    $config['max_size']      = 2048;

    $this->upload->initialize($config);

    if (!$this->upload->do_upload('file_import')) {
        $this->session->set_flashdata('error', $this->upload->display_errors());
        redirect('koderekening');
    } else {
        $file_data = $this->upload->data();
        $file_path = './uploads/' . $file_data['file_name'];

        // Baca file Excel
        $objPHPExcel = PHPExcel_IOFactory::load($file_path);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $jumlah_insert = 0;
        $jumlah_update = 0;

        foreach ($sheetData as $i => $row) {
            if ($i == 1) continue; // Lewati header

            $kode = trim($row['A']);
            $nama = trim($row['B']);

            if ($kode && $nama) {
                $cek = $this->db->get_where('kode_rekening', ['kode' => $kode])->row();
                if ($cek) {
                    // Update
                    $this->db->where('kode', $kode);
                    $this->db->update('kode_rekening', ['nama_rekening' => $nama]);
                    $jumlah_update++;
                } else {
                    // Insert baru
                    $this->db->insert('kode_rekening', [
                        'kode' => $kode,
                        'nama_rekening' => $nama
                    ]);
                    $jumlah_insert++;
                }
            }
        }

        // Hapus file setelah diproses
        unlink($file_path);

        $this->session->set_flashdata('success', "Import selesai. Insert: $jumlah_insert, Update: $jumlah_update.");
        redirect('koderekening');
    }
}

}
?>
