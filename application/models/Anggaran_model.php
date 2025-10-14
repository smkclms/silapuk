<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    // ✅ Menambahkan anggaran baru
    public function create_anggaran($data) {
        $tahun_id = $this->session->userdata('tahun_id');        // ID dari tabel tahun_anggaran
        $tahun = $this->session->userdata('tahun_anggaran');     // Nilai tahun (contoh: 2025)

        if ($tahun_id && $tahun) {
            $data['tahun_id'] = $tahun_id;
            $data['tahun'] = $tahun;
        }

        return $this->db->insert('anggaran', $data);
    }

    // ✅ Mengambil semua anggaran berdasarkan tahun aktif
    public function get_all_anggaran() {
        $tahun_id = $this->session->userdata('tahun_id');

        if ($tahun_id) {
            $this->db->where('tahun_id', $tahun_id);
        }

        return $this->db->order_by('created_at', 'DESC')->get('anggaran')->result();
    }

    // ✅ Mengambil anggaran berdasarkan user_id (dan tahun aktif)
    public function get_anggaran_by_user($user_id) {
        $tahun_id = $this->session->userdata('tahun_id');

        $this->db->where('user_id', $user_id);
        if ($tahun_id) {
            $this->db->where('tahun_id', $tahun_id);
        }

        return $this->db->order_by('created_at', 'DESC')->get('anggaran')->result();
    }

    public function get_anggaran_by_id($id) {
        return $this->db->where('id', $id)->get('anggaran')->row();
    }

    public function update_anggaran($id, $data) {
        return $this->db->where('id', $id)->update('anggaran', $data);
    }

    public function delete_anggaran($id) {
        return $this->db->where('id', $id)->delete('anggaran');
    }

    public function tahun_exists($tahun) {
        $this->db->where('tahun', $tahun);
        $query = $this->db->get('tahun_anggaran');
        return $query->num_rows() > 0;
    }

    public function tambahTahunAnggaran($tahun) {
        $data = ['tahun' => $tahun];
        return $this->db->insert('tahun_anggaran', $data);
    }

    public function reset_anggaran($tahun) {
        $this->db->where('tahun', $tahun);
        return $this->db->update('anggaran', ['jumlah_anggaran' => 0]);
    }

    public function get_all_tahun() {
        return $this->db->order_by('tahun', 'DESC')->get('tahun_anggaran')->result();
    }

    public function get_anggaran_by_user_tahun($user_id, $tahun) {
        return $this->db->get_where('anggaran', [
            'user_id' => $user_id,
            'tahun'   => $tahun
        ])->result();
    }
}
?>
