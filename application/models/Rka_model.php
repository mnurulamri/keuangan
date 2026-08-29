<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rka_model extends CI_Model {

    protected $table = 'rka';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db->where('tahun_anggaran', 2026)->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
    
    // Fungsi tambahan untuk mendapatkan data unik
    public function get_distinct_kode_dpsj() {
        $this->db->distinct();
        $this->db->select('kode_dpsj, deskripsi_dpsj');
        $this->db->where('kode_dpsj IS NOT NULL');
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    public function get_distinct_kode_dana() {
        $this->db->distinct();
        $this->db->select('kode_dana, deskripsi_dana');
        $this->db->where('kode_dana IS NOT NULL');
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    public function get_by_kode_dpsj($kode_dpsj) {
        return $this->db->get_where($this->table, ['kode_dpsj' => $kode_dpsj])->result();
    }

    // Fungsi untuk mendapatkan daftar tahun unik
    public function get_distinct_tahun() {
        $this->db->distinct();
        $this->db->select('tahun_anggaran');
        $this->db->order_by('tahun_anggaran', 'DESC');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    // Fungsi untuk mendapatkan data berdasarkan tahun
    public function get_by_tahun($tahun) {
        $this->db->where('tahun_anggaran', $tahun);
        return $this->db->get($this->table)->result();
    }

    // Fungsi untuk mendapatkan total anggaran per tahun
    public function get_total_anggaran_per_tahun($tahun = null) {
        $this->db->select('tahun_anggaran, SUM(anggaran) as total_anggaran');
        
        if ($tahun) {
            $this->db->where('tahun_anggaran', $tahun);
        }
        
        $this->db->group_by('tahun_anggaran');
        $this->db->order_by('tahun_anggaran', 'DESC');
        $query = $this->db->get($this->table);
        
        return $query->result();
    }

    public function search_dpsj($keyword) {
        $this->db->select('kode_dpsj, deskripsi_dpsj');
        $this->db->like('deskripsi_dpsj', $keyword);
        //$this->db->where('SA', $unit_id);
        /*if($unit_id) {
            $this->db->where('SA', $unit_id);
        }*/
        //$this->db->group_by('KODE_KEGIATAN, NAMA_KEGIATAN');
        return $this->db->get('rka')->result();
    }

    public function search_project_costing($keyword, $unit_id = null) {
        $this->db->select('KODE_KEGIATAN, NAMA_KEGIATAN, AKUN, DESKRIPSI_AKUN, SISA_ANGGARAN');
        $this->db->like('NAMA_KEGIATAN', $keyword);
        if($unit_id) {
            $this->db->where('SA', $unit_id);
        }
        $this->db->group_by('KODE_KEGIATAN, NAMA_KEGIATAN');
        return $this->db->get('rka')->result();
    }

    public function search_akun($keyword, $kode_kegiatan = null) {
        $this->db->select('AKUN, DESKRIPSI_AKUN, SISA_ANGGARAN');
        $this->db->like('DESKRIPSI_AKUN', $keyword);
        if($kode_kegiatan) {
            $this->db->where('KODE_KEGIATAN', $kode_kegiatan);
        }
        return $this->db->get('rka')->result();
    }

    public function get_sisa_anggaran($kode_kegiatan, $akun) {
        $this->db->select('SISA_ANGGARAN');
        $this->db->where('KODE_KEGIATAN', $kode_kegiatan);
        $this->db->where('AKUN', $akun);
        $result = $this->db->get('rka')->row();
        return $result ? $result->SISA_ANGGARAN : 0;
    }

    public function check_transaksi_by_akun($kode_kegiatan, $kode_akun, $kode_dana) {
        $this->db->where('kode_kegiatan', $kode_kegiatan);
        $this->db->where('kode_akun', $kode_akun);
        $this->db->where('kode_dana', $kode_dana);
        $result = $this->db->get('pengajuan_rincian')->row();
        return $result ? true : false;
    }
}