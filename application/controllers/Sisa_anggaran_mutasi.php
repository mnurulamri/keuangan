<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Sisa_anggaran Controller
 *
 * This controller handles the retrieval of budget data based on user input.
 * It uses the Anggaran_model and Rka_model to fetch the necessary data.
 */
class Sisa_anggaran_mutasi extends CI_Controller {

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
        $tahun_anggaran = $this->input->post('tahun_anggaran');
		$kode_kegiatan = $this->input->post('kode_kegiatan');
		$kode_akun = $this->input->post('kode_akun');
		$kode_dana = $this->input->post('kode_dana');
        $jumlah = $this->input->post('jumlah');

        /*$kode_dpsj = '09000400';
		$kode_kegiatan = 'F0078.01.01.5.001';
		$kode_akun = '723207';
		$kode_dana = '51';*/

        // ambil data anggaran awal untuk admin sdm (Tiwi)
        //$sql = "SELECT sisa_anggaran FROM view_anggaran_mutasi WHERE tahun_anggaran = ? AND kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";

        // ambil data anggaran untuk pum uni
        /*$sql = "SELECT sisa_anggaran 
                FROM view_anggaran_mutasi 
                WHERE tahun_anggaran = ? AND kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ? AND flag_payroll IN ('Procost Unit', 'Procost Umum')";*/
        
        $sql = "SELECT sisa_anggaran 
                FROM view_anggaran_mutasi 
                WHERE tahun_anggaran = '$tahun_anggaran' AND kode_kegiatan = '$kode_kegiatan' AND kode_akun = '$kode_akun' AND kode_dana = '$kode_dana'
                ";
        $query = $this->db->query($sql);
        //echo '<pre>';print_r($query->result_array() ); exit();
        if ($query->num_rows() > 0) {  
            foreach ($query->result_array() as $row) {
                $sisa_anggaran = $row['sisa_anggaran'];
            }
        } else {
            $sisa_anggaran = 0; // Default value if no data found
        }

		$sisa_anggaran += (int)$jumlah;

        if ($sisa_anggaran < 0) {
            echo 'anggaran tidak mencukupi';
            return;
        }

        echo number_format($sisa_anggaran); 
        
	}
}