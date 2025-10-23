<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rka_model extends CI_Model {

    public function __construct() {
        parent::__construct();
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
}