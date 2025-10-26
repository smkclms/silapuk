<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Pengeluaran_model'); // ✅ ubah dari Expenditure_model
        $this->load->model('User_model');
        $this->load->model('Anggaran_model');
        $this->load->model('SumberAnggaran_model');
        $this->load->library('pagination');

        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $tahun_aktif = $this->session->userdata('tahun_anggaran');

        // Ambil semua pengeluaran berdasarkan tahun aktif
        $data['expenditures'] = $this->db
            ->where('tahun', $tahun_aktif)
            ->get('pengeluaran')
            ->result();

        $data['users'] = $this->User_model->get_all_users();

        $sumber_anggaran = $this->SumberAnggaran_model->get_all_sumber();
        $total_pagu = 0;
        foreach ($sumber_anggaran as $item) {
            $total_pagu += $item->jumlah;
        }
        $data['total_pagu'] = $total_pagu;

        $this->load->view('dashboard_bendahara', $data);
    }

    public function bendahara($page = 0) {
        $tahun_aktif = $this->session->userdata('tahun_anggaran');

        // Konfigurasi pagination
        $config['base_url'] = site_url('dashboard/bendahara');
        $config['total_rows'] = $this->db->where('tahun', $tahun_aktif)->count_all_results('pengeluaran');
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;

        // Bootstrap 5 pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $data['users'] = $this->User_model->get_all_users_except_roles(['bendahara', 'superadmin']);

        // Ambil data pengeluaran berdasarkan tahun aktif
        $data['expenditures'] = $this->db
            ->where('tahun', $tahun_aktif)
            ->limit($config['per_page'], $page)
            ->get('pengeluaran')
            ->result();

        // ✅ Data untuk grafik
        $all_expenditures = $this->db
            ->where('tahun', $tahun_aktif)
            ->get('pengeluaran')
            ->result();

        $expenditures_chart = [];
        foreach ($all_expenditures as $ex) {
            $user = $this->User_model->get_user_by_id($ex->user_id);
            $name = $user ? $user->nama_lengkap : 'Tidak Diketahui';
            if (!isset($expenditures_chart[$name])) {
                $expenditures_chart[$name] = 0;
            }
            $expenditures_chart[$name] += $ex->jumlah_pengeluaran;
        }
        $data['expenditures_chart'] = $expenditures_chart;

        // Hitung total pagu
        $sumber_anggaran = $this->SumberAnggaran_model->get_all_sumber();
        $total_pagu = 0;
        foreach ($sumber_anggaran as $item) {
            $total_pagu += $item->jumlah;
        }
        $data['total_pagu'] = $total_pagu;

        // Hitung total disalurkan dan dibelanjakan
        $total_disalurkan = 0;
        $total_dibelanjakan = 0;

        foreach ($data['users'] as $user) {
            $anggaran = $this->db
                ->where('user_id', $user->id)
                ->where('tahun', $tahun_aktif)
                ->get('anggaran')
                ->row();
            $total_disalurkan += $anggaran ? $anggaran->jumlah_anggaran : 0;

            $pengeluaran_user = $this->db
                ->where('user_id', $user->id)
                ->where('tahun', $tahun_aktif)
                ->get('pengeluaran')
                ->result();

            foreach ($pengeluaran_user as $ex) {
                $total_dibelanjakan += $ex->jumlah_pengeluaran;
            }
        }

        $data['total_disalurkan'] = $total_disalurkan;
        $data['total_dibelanjakan'] = $total_dibelanjakan;

        $this->load->view('dashboard_bendahara', $data);
    }

    public function view() {
        $user_id = $this->session->userdata('user_id');
        $tahun_aktif = $this->session->userdata('tahun_anggaran');

        if (!$user_id) {
            redirect('auth/login');
        }

        $data['expenditures'] = $this->db
            ->where('user_id', $user_id)
            ->where('tahun', $tahun_aktif)
            ->get('pengeluaran')
            ->result();

        $anggaran = $this->db
            ->where('user_id', $user_id)
            ->where('tahun', $tahun_aktif)
            ->get('anggaran')
            ->row();

        $data['anggaran'] = $anggaran;
        $data['user'] = $this->User_model->get_user_by_id($user_id);

        $this->load->view('dashboard_view', $data);
    }

    public function laporan_penggunaan_user() {
        $user_id = $this->session->userdata('user_id');
        $role = strtolower($this->session->userdata('role'));
        $tahun_aktif = $this->session->userdata('tahun_anggaran');

        if (in_array($role, ['bendahara', 'superadmin'])) {
            redirect('laporanpenggunaan');
        }

        $user = $this->User_model->get_user_by_id($user_id);
        $anggaran = $this->db
            ->where('user_id', $user_id)
            ->where('tahun', $tahun_aktif)
            ->get('anggaran')
            ->row();

        $total_anggaran = $anggaran ? $anggaran->jumlah_anggaran : 0;

        $expenditures_user = $this->db
            ->where('user_id', $user_id)
            ->where('tahun', $tahun_aktif)
            ->get('pengeluaran')
            ->result();

        $total_pengeluaran = 0;
        foreach ($expenditures_user as $ex) {
            $total_pengeluaran += $ex->jumlah_pengeluaran;
        }

        $sisa_anggaran = $total_anggaran - $total_pengeluaran;

        $data = [
            'user' => $user,
            'total_anggaran' => $total_anggaran,
            'sisa_anggaran' => $sisa_anggaran,
            'expenditures_user' => $expenditures_user,
        ];

        $this->load->view('dashboard_view', $data);
    }
}
