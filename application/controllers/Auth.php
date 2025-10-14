<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Anggaran_model'); // untuk ambil tahun anggaran
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
    }

    // ✅ Halaman login
    public function login() {
        // Ambil daftar tahun anggaran dari tabel tahun_anggaran
        $data['tahun_anggaran'] = $this->db->order_by('tahun', 'DESC')->get('tahun_anggaran')->result();
        $this->load->view('login_view', $data);
    }

    // ✅ Proses autentikasi
    public function authenticate() {
        $username       = $this->input->post('username', TRUE);
        $password       = $this->input->post('password', TRUE);
        $id_tahun       = $this->input->post('tahun_anggaran', TRUE); // ID tahun dari dropdown

        // Ambil user berdasarkan username
        $user = $this->User_model->get_user_by_username($username);

        if ($user) {
            // Cek password hash
            if (password_verify($password, $user->password)) {

                // Ambil data tahun berdasarkan ID (pastikan valid)
                $tahun_row = $this->db->get_where('tahun_anggaran', ['id' => $id_tahun])->row();

                if (!$tahun_row) {
                    $this->session->set_flashdata('error', 'Tahun anggaran tidak valid.');
                    redirect('auth/login');
                    return;
                }

                // Simpan ID & nilai tahun ke session
                $this->session->set_userdata([
                    'user_id'         => $user->id,
                    'id'              => $user->id,
                    'role'            => $user->role,
                    'nama'            => $user->nama_lengkap,
                    'nama_lengkap'    => $user->nama_lengkap,
                    'tahun_id'        => $tahun_row->id,    // simpan ID
                    'tahun_anggaran'  => $tahun_row->tahun, // simpan nilai tahun (contoh: 2026)
                ]);

                // Redirect sesuai role
                switch ($user->role) {
                    case 'bendahara':
                        redirect('dashboard/bendahara');
                        break;
                    case 'admin':
                        redirect('dashboard/admin');
                        break;
                    default:
                        redirect('dashboard/view');
                        break;
                }

            } else {
                // Password salah
                $this->session->set_flashdata('error', 'Username atau password salah.');
                redirect('auth/login');
            }
        } else {
            // Username tidak ditemukan
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }
    }

    // ✅ Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
?>
