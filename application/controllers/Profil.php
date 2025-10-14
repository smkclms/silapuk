<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');

        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $this->load->view('profil/profil', $data);
    }

    public function update() {
    $user_id = $this->session->userdata('user_id');
    $nama_lengkap = $this->input->post('nama_lengkap');

    $this->db->set('nama_lengkap', $nama_lengkap);

    if (!empty($_FILES['foto']['name'])) {
        $config['upload_path'] = './assets/img/profil/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = 2048;
        $config['file_name'] = time(); // nama file unik

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto')) {
            $foto = $this->upload->data('file_name');
            $this->db->set('foto', $foto);

            // ✅ Simpan juga ke session
            $this->session->set_userdata('foto', $foto);
        } else {
            echo $this->upload->display_errors();
            return;
        }
    }

    $this->db->where('id', $user_id);
    $this->db->update('users');

    // ✅ Update session nama juga
    $this->session->set_userdata('nama_lengkap', $nama_lengkap);

    redirect('profil');
}


    public function password() {
        $this->load->view('profil/password');
    }

    public function update_password() {
        $user_id = $this->session->userdata('user_id');
        $lama = $this->input->post('password_lama');
        $baru = $this->input->post('password_baru');
        $konfirmasi = $this->input->post('konfirmasi_password');

        $user = $this->User_model->get_user_by_id($user_id);

        if (password_verify($lama, $user->password)) {
            if ($baru === $konfirmasi) {
                $hash = password_hash($baru, PASSWORD_DEFAULT);
                $this->db->set('password', $hash);
                $this->db->where('id', $user_id);
                $this->db->update('users');
                redirect('profil/password');
            } else {
                echo "Konfirmasi password tidak cocok.";
            }
        } else {
            echo "Password lama salah.";
        }
    }
}
