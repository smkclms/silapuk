<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function get_all_users() {
        return $this->db->get('users')->result();
    }

    public function get_user_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }
    public function create_user($data) {
    return $this->db->insert('users', $data);
    }

    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id) {
        return $this->db->delete('users', ['id' => $id]);
    }
    public function get_user_by_username($username) {
    return $this->db->get_where('users', ['username' => $username])->row();
    }
    public function get_all_users_except_roles($roles = []) {
    if (!empty($roles)) {
        $this->db->where_not_in('role', $roles);
    }
    return $this->db->get('users')->result();
}
public function get_all() {
        return $this->db->order_by('nama_lengkap', 'ASC')->get('users')->result();
    }
    // Tambahkan metode lain sesuai kebutuhan
}
?>
