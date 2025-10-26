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
        $data['tahun_anggaran'] = $this->db->order_by('tahun', 'ASC')->get('tahun_anggaran')->result();
        $this->load->view('login_view', $data);
    }

    // ✅ Proses autentikasi
    public function authenticate() {
    $username = $this->input->post('username', TRUE);
    $password = $this->input->post('password', TRUE);
    $id_tahun = $this->input->post('tahun_anggaran', TRUE); // ID tahun dari dropdown

    // Ambil user berdasarkan username
    $user = $this->User_model->get_user_by_username($username);

    if ($user) {
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
    'logged_in'       => TRUE, // ✅ tambahkan ini!
    'user_id'         => $user->id,
    'id'              => $user->id,
    'role'            => strtolower($user->role),
    'nama'            => $user->nama_lengkap,
    'nama_lengkap'    => $user->nama_lengkap,
    'tahun_id'        => $tahun_row->id,
    'tahun_anggaran'  => $tahun_row->tahun,
]);


            // 🔐 Redirect sesuai role
            switch (strtolower($user->role)) {
                case 'bendahara':
                case 'superadmin':
                    redirect('dashboard/bendahara');
                    break;

                case 'admin':
                    redirect('dashboard/admin');
                    break;

                case 'pengguna':
                case 'user':
                case 'pegawai':
                case 'guru':
                    redirect('user/dashboarduser');
                    break;

                default:
                    redirect('auth/login');
                    break;
            }

        } else {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }
    } else {
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
