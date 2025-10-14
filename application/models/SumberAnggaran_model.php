<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SumberAnggaran_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Tambah sumber baru (otomatis ambil tahun dari session)
    public function create_sumber($data) {
        $tahun = $this->session->userdata('tahun_anggaran'); // ambil tahun dari session

        if ($tahun) {
            $data['tahun'] = $tahun;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('sumber_anggaran', $data);
    }

    // Ambil semua sumber anggaran berdasarkan tahun aktif
    public function get_all_sumber() {
    $tahun = $this->session->userdata('tahun_anggaran');
    if ($tahun) {
        $this->db->where('tahun', $tahun);
    }
    $this->db->order_by('created_at', 'DESC');
    return $this->db->get('sumber_anggaran')->result();
}


    public function get_sumber_by_id($id) {
        return $this->db->where('id', $id)->get('sumber_anggaran')->row();
    }

    public function update_sumber($id, $data) {
        return $this->db->where('id', $id)->update('sumber_anggaran', $data);
    }

    public function delete_sumber($id) {
        return $this->db->where('id', $id)->delete('sumber_anggaran');
    }

    // Ambil sumber berdasarkan tahun tertentu (manual)
    public function get_sumber_by_tahun($tahun) {
        return $this->db->get_where('sumber_anggaran', ['tahun' => $tahun])->result();
    }
    
}
?>
