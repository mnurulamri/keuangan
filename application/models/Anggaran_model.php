<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all anggaran
    public function get_all() {
        $this->db->order_by('tahun', 'DESC');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('anggaran')->result();
    }

    // Get anggaran by id
    public function get_by_id($id) {
        return $this->db->get_where('anggaran', array('id' => $id))->row();
    }

    // Insert new anggaran
    public function insert($data) {
        $this->db->insert('anggaran', $data);
        return $this->db->insert_id();
    }

    // Update anggaran
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('anggaran', $data);
    }

    // Delete anggaran
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('anggaran');
    }

    // Get anggaran by status
    public function get_by_status($status) {
        $this->db->where('status', $status);
        return $this->db->get('anggaran')->result();
    }

    // Get anggaran by unit kerja
    public function get_by_unit($unit_id) {
        $this->db->where('unit_id', $unit_id);
        return $this->db->get('anggaran')->result();
    }

    /*  ------ --------------
        update 
        --------------------- */

    // Tambahkan method baru untuk generate nomor pengajuan
    public function generate_nomor_pengajuan($kode_unit) {
        $current_month = date('m');
        $current_year = date('Y');
        
        // Cari nomor terakhir bulan ini, tahun ini dan kode unit kerja
        $this->db->select_max('nomor_urut');
        $this->db->where('MONTH(created_at)', $current_month);
        $this->db->where('YEAR(created_at)', $current_year);
        $this->db->where('kode_unit', $kode_unit);
        $query = $this->db->get('monitoring');
        $result = $query->row();
        
        $next_number = 1;
        if ($result && $result->nomor_urut) {
            $next_number = $result->nomor_urut + 1;
        }
        
        $nomor_urut = str_pad($next_number, 3, '0', STR_PAD_LEFT);
        $bulan = date('m');
        $tahun = date('Y');
        
        return $nomor_urut . '/ANG.' . $bulan . '/' . $tahun . '-' . $kode_unit;
    }

    // Update method insert_pengajuan
    public function insert_pengajuan($data_pemohon, $data_rincian) {
        $this->db->trans_start();
        
        // Get nomor urut terakhir
        $current_month = date('m');
        $current_year = date('Y');
        $this->db->select_max('nomor_urut');
        $this->db->where('MONTH(created_at)', $current_month);
        $this->db->where('YEAR(created_at)', $current_year);
        $query = $this->db->get('pengajuan_pemohon');
        $result = $query->row();
        
        $next_number = 1;
        if ($result && $result->nomor_urut) {
            $next_number = $result->nomor_urut + 1;
        }
        
        // Tambahkan nomor urut ke data pemohon
        $data_pemohon['nomor_urut'] = $next_number;
        
        $this->db->insert('pengajuan_pemohon', $data_pemohon);
        $pengajuan_id = $this->db->insert_id();
        
        foreach($data_rincian as &$rincian) {
            $rincian['pengajuan_id'] = $pengajuan_id;
        }
        $this->db->insert_batch('pengajuan_rincian', $data_rincian);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }


}