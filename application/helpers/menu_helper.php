<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('menu')) {
    /**
     * Helper function to calculate remaining budget.
     *
     * @param float $anggaran_awal Initial budget amount.
     * @param float $total_komitmen Total commitments made against the budget.
     * @return float Remaining budget after commitments.
     */
    function menu() {
        // Ambil menu berdasarkan role_id dari database
        $CI =& get_instance();
        $CI->load->database();
        
        $role_id = $CI->session->userdata('logged_anggaran')['role'];
        $sql = "SELECT * FROM menu WHERE $role_id = 1 ORDER BY parent ASC, sort ASC";
        $query = $CI->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array(); // Return empty array if no menu found
        }
    }
} 

if (! function_exists('dpsj')) {
    /**
     * Helper function to calculate remaining budget.
     *
     * @param float $anggaran_awal Initial budget amount.
     * @param float $total_komitmen Total commitments made against the budget.
     * @return float Remaining budget after commitments.
     */
    function dpsj() {
        // Ambil menu berdasarkan role_id dari database
        $CI =& get_instance();
        $CI->load->database();
        
        $array_kode_dpsj = $CI->session->userdata('logged_anggaran')['array_dpsj'];
        $kode_dpsj = implode("','", $array_kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";
        
        $sql = "SELECT nama_unit FROM unit_kerja WHERE kode_dpsj IN ($kode_dpsj) LIMIT 1";
        $query = $CI->db->query($sql);
        
        if ($query->num_rows() > 0) {
			foreach( $query->result_array() as $row){
				$nama_unit = $row['nama_unit'];
			}
            return $nama_unit;
        } else {
            return array(); // Return empty array if no menu found
        }
        
        /*$kode_dpsj = $CI->session->userdata('logged_anggaran')['kode_dpsj'];
        $sql = "SELECT deskripsi_dpsj FROM unit_kerja WHERE kode_dpsj = '$kode_dpsj' LIMIT 1";
        $query = $CI->db->query($sql);

        if ($query->num_rows() > 0) {
			foreach( $query->result_array() as $row){
				$deskripsi_dpsj = $row['deskripsi_dpsj'];
			}
            return $deskripsi_dpsj;
        } else {
            return array(); // Return empty array if no menu found
        }*/
    }
} 