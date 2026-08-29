<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_rka_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Get distinct tahun_anggaran
     */
    public function get_tahun() {
        $this->db->select('tahun_anggaran');
        $this->db->distinct();
        $this->db->order_by('tahun_anggaran', 'DESC');
        $query = $this->db->get('view_anggaran_mutasi');
        return $query->result_array();
    }
    
    /**
     * Get distinct kode_dpsj with search by kode or deskripsi
     */
    public function get_kode_dpsj($search = '', $page = 1, $limit = 20) {
        $this->db->select('kode_dpsj, deskripsi_dpsj');
        $this->db->distinct();
        $this->db->where('kode_dpsj IS NOT NULL');
        $this->db->where('kode_dpsj !=', '');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_dpsj', $search);
            $this->db->or_like('deskripsi_dpsj', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('kode_dpsj', 'ASC');
        
        // For pagination in AJAX
        if ($limit > 0) {
            $offset = ($page - 1) * $limit;
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get('view_anggaran_mutasi');
        return $query->result_array();
    }
    
    /**
     * Get total distinct kode_dpsj for pagination
     */
    public function get_total_kode_dpsj($search = '') {
        $this->db->select('COUNT(DISTINCT kode_dpsj) as total');
        $this->db->where('kode_dpsj IS NOT NULL');
        $this->db->where('kode_dpsj !=', '');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kode_dpsj', $search);
            $this->db->or_like('deskripsi_dpsj', $search);
            $this->db->group_end();
        }
        
        $query = $this->db->get('view_anggaran_mutasi');
        $result = $query->row_array();
        return $result ? $result['total'] : 0;
    }
    
    /**
     * Get data for export with filters
     */
    public function get_export_data($tahun = '', $kode_dpsj_start = '', $kode_dpsj_end = '') {
        $this->db->select('
            tahun_anggaran,
            kode_dpsj,
            deskripsi_dpsj,
            kode_kegiatan,
            nama_kegiatan,
            kode_dana,
            deskripsi_dana,
            kategori_kegiatan,
            kode_akun,
            deskripsi_akun,
            anggaran,
            komitmen,
            aktual,
            mutasi,
            sisa_anggaran,
            flag_payroll,
            flag_count,
            flag_disetujui,
            pph,
            netto
        ');
        
        if (!empty($tahun)) {
            $this->db->where('tahun_anggaran', $tahun);
            // Filter by kode_dpsj range if both start and end are provided
            if (!empty($kode_dpsj_start) && !empty($kode_dpsj_end)) {
                $this->db->where('kode_dpsj >=', $kode_dpsj_start);
                $this->db->where('kode_dpsj <=', $kode_dpsj_end);
            } elseif (!empty($kode_dpsj_start)) {
                $this->db->where('kode_dpsj', $kode_dpsj_start);
            } elseif (!empty($kode_dpsj_end)) {
                $this->db->where('kode_dpsj', $kode_dpsj_end);
            }
        }
        
        if (!empty($kode_dpsj_start) && !empty($kode_dpsj_end)) {
            $this->db->where('kode_dpsj >=', $kode_dpsj_start);
            $this->db->where('kode_dpsj <=', $kode_dpsj_end);
        } elseif (!empty($kode_dpsj_start)) {
            $this->db->where('kode_dpsj', $kode_dpsj_start);
        } elseif (!empty($kode_dpsj_end)) {
            $this->db->where('kode_dpsj', $kode_dpsj_end);
        }
        
        $this->db->order_by('tahun_anggaran DESC, kode_dpsj, kode_kegiatan');
        $query = $this->db->get('view_anggaran_mutasi');
        return $query->result_array();
    }
    
    /**
     * Get total records for pagination/info
     */
    public function get_total_records($tahun = '', $kode_dpsj = '') {
        if (!empty($tahun)) {
            $this->db->where('tahun_anggaran', $tahun);
        }
        
        if (!empty($kode_dpsj)) {
            $this->db->where('kode_dpsj', $kode_dpsj);
        }
        
        return $this->db->count_all_results('view_anggaran_mutasi');
    }
}
?>