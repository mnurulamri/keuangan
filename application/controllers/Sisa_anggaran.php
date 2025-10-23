<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Sisa_anggaran Controller
 *
 * This controller handles the retrieval of budget data based on user input.
 * It uses the Anggaran_model and Rka_model to fetch the necessary data.
 */
class Sisa_anggaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('session');

        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

	public function index(){

		$kode_dpsj = $this->input->post('kode_dpsj');
		$kode_kegiatan = $this->input->post('kode_kegiatan');
		$kode_akun = $this->input->post('kode_akun');
		$kode_dana = $this->input->post('kode_dana');
        $jumlah = $this->input->post('jumlah');
        /*$kode_dpsj = '09000900';
		$kode_kegiatan = 'F0100.03.02.5.001';
		$kode_akun = '21303';
		$kode_dana = '51';*/

        // ambil data anggaran awal
        $sql = "SELECT sisa_anggaran FROM rka WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
        $query = $this->db->query($sql, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
        
        if ($query->num_rows() > 0) {  
            foreach ($query->result_array() as $row) {
                $anggaran_awal = $row['sisa_anggaran'];
            }
        } else {
            $anggaran_awal = 0; // Default value if no data found
        }
        //echo $this->db->last_query(); echo '<br>';
        // ambil data total komitmen
        $sql_komitmen = "SELECT SUM(komitmen) AS total_komitmen FROM pengajuan_rincian WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
        $query_komitmen = $this->db->query($sql_komitmen, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
        $komitmen = $query_komitmen->row_array();
        
        if (!$komitmen) {            
            foreach ($query_komitmen->result_array() as $row) {
                $total_komitmen = $row['total_komitmen'];
            }
        } else {
            $total_komitmen = 0; // Default value if no data found
        }
        //echo $this->db->last_query(); echo '<br>';
        // ambil data realisasi
        $sql_realisasi = "SELECT SUM(jumlah) AS total_realisasi FROM realisasi WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
        $query_realisasi = $this->db->query($sql_realisasi, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
        $realisasi = $query_realisasi->row_array();
        if (!$realisasi) {
            foreach ($realisasi->result_array() as $row) {
                $total_realisasi = $row['total_realisasi'];
            }
        } else {
            $total_realisasi = 0; // Default value if no data found
        }
        //echo $this->db->last_query(); echo '<br>';
        // hitung sisa anggaran
        if($total_realisasi == 0) {
            $sisa_anggaran = (int)$anggaran_awal - (int)$total_komitmen - (int)$jumlah;
        } else {
            $sisa_anggaran = (int)$anggaran_awal - (int)$total_realisasi - (int)$jumlah;
        }

        if ($sisa_anggaran < 0) {
            echo "0";
            return;
        }

        echo $sisa_anggaran = number_format($sisa_anggaran);
        //echo $anggaran_awal = number_format($anggaran_awal);
        //echo $total_komitmen = number_format($total_komitmen);
        //echo $total_realisasi = number_format($total_realisasi);
        //echo $this->db->last_query();

        //echo $this->db->select('sisa_anggaran')->get_compiled_select();

		//$this->output
        //    ->set_content_type('application/json')
        //    ->set_output(json_encode($anggaran_awal));
	}
}