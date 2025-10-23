<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realisasi_rekap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('session');
        
		$session_data = array(
                        'username'  => 'xxx',
                        'hak_akses' => 1,
                        'role' => 'admin',
                        'kode_org_anggaran' => '00',
                        'cn_anggaran' => ''
                    );
		$this->session->set_userdata('logged_anggaran', $session_data);

        //if (!$this->session->userdata('logged_anggaran')) {
            //redirect('auth/login');
        //}
    }

	public function index() 
	{		
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('kode_bidang');

        // ambil kode dpsj berdasarkan kode bidang
        $sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_dpsj[] = $row['kode_dpsj'];
        }
        $kode_dpsj = implode("','", $kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";

        // ambil data dari tabel pengajuan_rincian berdasarkan kode_dpsj
        $sql = "SELECT * FROM pengajuan_rincian WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result_pengajuan_rincian = $query->result_array();

        // ambil data anggaraan dari tabel rka berdasarkan kode_dpsj
        $sql = "SELECT * FROM rka WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_akun[$row['kode_akun']] = $row['anggaran'];
        }

		// ambil data monitoring -> tambahkan kode dpsj supaya hanya bisa di lihat oleh unit ybs
        $sql = "SELECT * FROM monitoring WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $array_uraian[$row['nomor_pengajuan']] = $row['uraian'];
        }
        $data['array_uraian'] = $array_uraian;

        $data['rincian'] = $result_pengajuan_rincian;
        $data['kode_akun'] = $kode_akun;
        $data['sql'] = $sql;

        $data['title'] = 'Daftar Rekap Realisasi UMKO';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('unit_kerja/realisasi_daftar', $data);        
        $this->load->view('template/footer');
    
	}
}