<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenditure_model extends CI_Model {

    // 🔹 Simpan pengeluaran baru
    public function create_expenditure($data) {
        return $this->db->insert('pengeluaran', $data);
    }

    // 🔹 Ambil pengeluaran berdasarkan user_id
    public function get_expenditures_by_user($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->get('pengeluaran')->result_array();
    }

    // 🔹 Total pengeluaran per bulan (opsional user_id)
    public function get_monthly_expenditure($year, $month, $user_id = null) {
        $this->db->select_sum('jumlah_pengeluaran');
        $this->db->where('YEAR(tanggal_pengeluaran)', $year);
        $this->db->where('MONTH(tanggal_pengeluaran)', $month);

        if ($user_id != null) {
            $this->db->where('user_id', $user_id);
        }

        $query = $this->db->get('pengeluaran')->row();
        if ($query) {
            return $query->jumlah_pengeluaran;
        } else {
            return 0;
        }
    }

    // 🔹 Total pengeluaran per bulan untuk user tertentu
    public function get_monthly_expenditure_by_user($year, $month, $user_id) {
        $this->db->select_sum('jumlah_pengeluaran');
        $this->db->where('YEAR(tanggal_pengeluaran)', $year);
        $this->db->where('MONTH(tanggal_pengeluaran)', $month);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('pengeluaran')->row();
        if ($query) {
            return $query->jumlah_pengeluaran;
        } else {
            return 0;
        }
    }

    // 🔹 Ambil semua pengeluaran
    public function get_all_expenditures() {
        $this->db->order_by('tanggal_pengeluaran', 'DESC');
        return $this->db->get('pengeluaran')->result();
    }

    // 🔹 Ambil pengeluaran berdasarkan ID
    public function get_expenditure_by_id($id) {
        return $this->db->get_where('pengeluaran', array('id' => $id))->row();
    }

    // 🔹 Update pengeluaran
    public function update_expenditure($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pengeluaran', $data);
    }

    // 🔹 Hapus pengeluaran
    public function delete_expenditure($id) {
        $this->db->where('id', $id);
        return $this->db->delete('pengeluaran');
    }

    // 🔹 Hitung total data pengeluaran
    public function count_all_expenditures() {
        return $this->db->count_all('pengeluaran');
    }

    // 🔹 Ambil data pengeluaran dengan limit (untuk dashboard)
    public function get_expenditures_limit($limit = 5, $start = 0) {
        $this->db->order_by('tanggal_pengeluaran', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get('pengeluaran')->result();
    }

    // 🔹 Ambil data pengeluaran untuk pagination
    public function get_expenditures_paginated($limit, $offset) {
        $this->db->order_by('tanggal_pengeluaran', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('pengeluaran')->result();
    }

    // 🔹 Ambil data pengeluaran beserta kode rekening
    public function get_expenditures_with_kodering($limit = 10, $offset = 0) {
        $this->db->select('
            p.*,
            k.kode AS kode_rekening_kode,
            k.nama_rekening,
            u.nama_lengkap AS nama_user,
            t.tahun AS nama_tahun_anggaran
        ');
        $this->db->from('pengeluaran p');
        $this->db->join('kode_rekening k', 'k.id = p.kode_rekening_id', 'left');
        $this->db->join('users u', 'u.id = p.user_id', 'left');
        $this->db->join('tahun_anggaran t', 't.id = p.tahun_id', 'left');
        $this->db->order_by('p.tanggal_pengeluaran', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

}
