<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KodeRekening_model extends CI_Model {

    // Ambil semua data kode rekening
    public function get_all_kode_rekening() {
        return $this->db->get('kode_rekening')->result();
    }

    // Tambahkan data kode rekening
    public function create_kode_rekening($data) {
        return $this->db->insert('kode_rekening', $data);
    }

    // Ambil satu data berdasarkan ID
    public function get_kode_rekening_by_id($id) {
        return $this->db->get_where('kode_rekening', ['id' => $id])->row();
    }

    // Update data berdasarkan ID
    public function update_kode_rekening($id, $data) {
        return $this->db->where('id', $id)->update('kode_rekening', $data);
    }

    // Hapus data berdasarkan ID
    public function delete_kode_rekening($id) {
    return $this->db->delete('kode_rekening', ['id' => $id]);
}
}
?>
