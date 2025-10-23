<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('sisaAnggaran')) {
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
        $sql = "SELECT * FROM menu WHERE $role_id = 1";
        $query = $CI->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array(); // Return empty array if no menu found
        }
    }
} 