<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
		$this->load->helper('url');
    }
    
    /**
     * Get all data for export
     */
    public function get_export_data($filters = array()) {
        $this->db->select('*');
        $this->db->from('rka'); // Ganti dengan nama tabel Anda
        
        // Apply filters if any
        if (!empty($filters['tahun_anggaran'])) {
            $this->db->where('tahun_anggaran', $filters['tahun_anggaran']);
        }
        
        if (!empty($filters['kode_dana'])) {
            $this->db->where('kode_dana', $filters['kode_dana']);
        }
        
        if (!empty($filters['kategori_kegiatan'])) {
            $this->db->where('kategori_kegiatan', $filters['kategori_kegiatan']);
        }
        
        // Order by
        $this->db->order_by('tahun_anggaran', 'DESC');
        $this->db->order_by('kode_kegiatan', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Get distinct years for filter
     */
    public function get_tahun_anggaran() {
        $this->db->distinct();
        $this->db->select('tahun_anggaran');
        $this->db->order_by('tahun_anggaran', 'DESC');
        $query = $this->db->get('rka');
        return $query->result_array();
    }
}
?>