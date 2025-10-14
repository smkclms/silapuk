<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_model extends CI_Model {
    public function get_budget_by_user($user_id) {
        return $this->db->get_where('anggaran', ['user_id' => $user_id])->result();
    }

    public function create_budget($data) {
        return $this->db->insert('anggaran', $data);
    }
    public function get_total_budget_by_user($user_id) {
    $this->db->select_sum('jumlah_anggaran');
    $this->db->where('user_id', $user_id);
    return $this->db->get('anggaran')->row()->jumlah_anggaran;
}

    // Tambahkan metode lain sesuai kebutuhan
}
?>
