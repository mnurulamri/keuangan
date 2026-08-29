<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Sisa_anggaran Controller
 *
 * This controller handles the retrieval of budget data based on user input.
 * It uses the Anggaran_model and Rka_model to fetch the necessary data.
 */
class Sisa_anggaran_form_edit extends CI_Controller {

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
        
        // cek id pengajuan rincian untuk mendapatkan jumlah lama yang akan diedit
        if(!$this->input->post('id_pengajuan_rincian')) {
            $jumlah_lama = 0;
        } else {
            $id_pengajuan_rincian = $this->input->post('id_pengajuan_rincian');
            $sql = "SELECT komitmen FROM pengajuan_rincian WHERE id = ?";
            $query = $this->db->query($sql, array($id_pengajuan_rincian));
            if ($query->num_rows() > 0) {  
                $row = $query->row_array();
                $jumlah_lama = (int)$row['komitmen'];
            } else {
                $jumlah_lama = 0;
            }
        }
        
        $sql = "SELECT sisa_anggaran 
                FROM view_anggaran_mutasi 
                WHERE tahun_anggaran = '$tahun_anggaran' AND kode_kegiatan = '$kode_kegiatan' AND kode_akun = '$kode_akun' AND kode_dana = '$kode_dana' ";
                $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {  
            foreach ($query->result_array() as $row) {
                $sisa_anggaran = $row['sisa_anggaran'];
            }
        } else {
            $sisa_anggaran = 0; // Default value if no data found
        }
        // hitung sisa anggaran dengan mengurangi jumlah lama yang akan diedit dan jumlah baru yang akan disimpan
        $sisa_anggaran += (int)$jumlah_lama; // tambahkan jumlah lama yang akan diedit ke sisa anggaran        
		$sisa_anggaran -= (int)$jumlah;
        //echo $sisa_anggaran.' '. $jumlah; //exit();
        if ($sisa_anggaran < 0) {
            echo 'anggaran tidak mencukupi';
            return;
        }

        echo number_format($sisa_anggaran); 
        
        
	}
}