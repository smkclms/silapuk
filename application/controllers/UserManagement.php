<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usermanagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    // Tampilkan halaman manajemen pengguna
    public function index() {
        $data['users'] = $this->User_model->get_all_users();
        $this->load->view('user_management_view', $data);
    }

    // Tambah pengguna via AJAX
    public function add_ajax() {
        $data = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role' => $this->input->post('role'),
            'nama_lengkap' => $this->input->post('nama_lengkap')
        ];

        if ($this->User_model->create_user($data)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    // Ambil data user untuk edit via AJAX
    public function get_user($id) {
        $user = $this->User_model->get_user_by_id($id);
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(null);
        }
    }

    // Update pengguna via AJAX
    public function edit_ajax() {
        $id = $this->input->post('id');
        $data = [
            'username' => $this->input->post('username'),
            'role' => $this->input->post('role'),
            'nama_lengkap' => $this->input->post('nama_lengkap')
        ];

        $password = $this->input->post('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->User_model->update_user($id, $data)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    // Hapus pengguna via AJAX
    public function delete_ajax() {
        $id = $this->input->post('id');
        if ($this->User_model->delete_user($id)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
