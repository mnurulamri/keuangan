<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('sisaAnggaran')) {
    /**
     * Helper function to calculate remaining budget.
     *
     * @param float $anggaran_awal Initial budget amount.
     * @param float $total_komitmen Total commitments made against the budget.
     * @return float Remaining budget after commitments.
     */
    function sisaAnggaran($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana) {
        // Ambil sisa anggaran dari database
        $CI =& get_instance();
        $CI->load->database();
        
        $sql = "SELECT sisa_anggaran FROM rka WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
        $query = $CI->db->query($sql, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));

        if ($query->num_rows() > 0) {       
            foreach ($query->result_array() as $row) {
                $anggaran_awal = $row['sisa_anggaran'];
            }
        } else {
            $anggaran_awal = 0; // Default value if no data found
        }
        // Ambil total komitmen
        $sql_komitmen = "SELECT SUM(komitmen) AS total_komitmen FROM pengajuan_rincian WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
        $query_komitmen = $CI->db->query($sql_komitmen, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
        $komitmen = $query_komitmen->row_array();
        if (!$komitmen) {            
            foreach ($query_komitmen->result_array() as $row) {
                $total_komitmen = $row['total_komitmen'];
            }
        } else {
            $total_komitmen = 0; // Default value if no data found
        }
        // Ambil total realisasi
        $sql_realisasi = "SELECT SUM(jumlah) AS total_realisasi FROM realisasi WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
        $query_realisasi = $CI->db->query($sql_realisasi, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
        $realisasi = $query_realisasi->row_array();
        if (!$realisasi) {
            foreach ($query_realisasi->result_array() as $row) {
                $total_realisasi = $row['total_realisasi'];
            }
        } else {
            $total_realisasi = 0; // Default value if no data found
        }
        // Hitung sisa anggaran
        if($total_realisasi == 0) {
            $sisa_anggaran = $anggaran_awal - $total_komitmen;
        } else {
            $sisa_anggaran = $anggaran_awal - $total_realisasi;
        }
        return number_format($sisa_anggaran);
    }
}