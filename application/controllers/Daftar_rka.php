<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_rka extends CI_Controller {

  public function __construct() 
	{
        parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('menu_helper');
		$this->load->helper('periode_helper');
		$this->load->helper('status_helper');
		$this->load->library('session');

		// Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
	}

	public function index() 
	{
		
		$data['title'] = 'Daftar RKA';
        $data['nama'] = 'test nama';
		$data['periode'] = $this->periode();

        foreach($data['periode'] as $row){
            $tahun =$row['tahun'];
            $bulan =$row['bulan'];
        }

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('laporan/daftar-rka-index', $data);    
        $this->load->view('template/footer');
	}

	public function data() 
	{
		$tahun = $this->input->post('tahun');

		$sql = "SELECT * FROM view_rka_dpsj WHERE tahun_anggaran = ? ";
        $query = $this->db->query($sql, array($tahun));
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
		$this->load->view('laporan/daftar-rka-data', $data);
	}

	public function data_per_dpsj() 
	{
		$tahun = $this->input->post('tahun');
		$kode_dpsj = $this->session->userdata('logged_anggaran')['array_dpsj'];   
        $kode_dpsj = implode("','", $kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";

		//$sql = "SELECT * FROM view_rka_dpsj WHERE tahun_anggaran = ? AND kode_dpsj IN ($kode_dpsj)";
        $sql = "SELECT * FROM view_rka_dpsj WHERE tahun_anggaran = $tahun AND kode_dpsj IN ($kode_dpsj)";
        //echo $sql;exit();
        $query = $this->db->query($sql, array($tahun, $kode_dpsj));
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }

		$this->load->view('laporan/daftar-rka-data', $data);
	}

	public function akun() 
	{
		
		$data['title'] = 'Daftar RKA';
        $data['nama'] = 'test nama';
		$data['periode'] = $this->periode();

        foreach($data['periode'] as $row){
            $tahun =$row['tahun'];
            $bulan =$row['bulan'];
        }

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('laporan/daftar-rka-akun-index', $data);    
        $this->load->view('template/footer');
	}

	public function data_akun() 
	{
		$tahun = $this->input->post('tahun');
		$kode_dpsj = $this->session->userdata('logged_anggaran')['array_dpsj'];   
        $kode_dpsj = implode("','", $kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";
        
        //$sql = "SELECT * FROM view_anggaran_mutasi WHERE tahun_anggaran = $tahun AND kode_dpsj IN ($kode_dpsj) ORDER BY kode_dpsj, kode_kegiatan, kode_akun";        
        //$query = $this->db->query($sql, array($tahun, $kode_dpsj));
        $sql = "SELECT * FROM view_anggaran_mutasi WHERE tahun_anggaran = $tahun AND kode_dpsj IN ($kode_dpsj) ORDER BY kode_dpsj, kode_kegiatan, kode_akun";   
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }

		$this->load->view('laporan/daftar-rka-akun-data', $data);
	}

	public function all() 
	{
		
		$data['title'] = 'Daftar RKA';
        $data['nama'] = 'test nama';
		$data['periode'] = $this->periode();

        foreach($data['periode'] as $row){
            $tahun =$row['tahun'];
            $bulan =$row['bulan'];
        }

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('laporan/daftar-rka-admin-index', $data);    
        $this->load->view('template/footer');
	}

	public function detail_akun() 
	{
		$kode_dpsj = $this->input->post('kode_dpsj');
		$tahun = $this->input->post('tahun');

		$sql = "SELECT a.*, b.nomor_pengajuan FROM view_pengajuan_rincian_realisasi a LEFT OUTER JOIN pengajuan_pemohon b ON tahun_anggaran = $tahun AND a.kode_dpsj = '$kode_dpsj' WHERE a.id_pengajuan_pemohon = b.id";
        $query = $this->db->query($sql, array($tahun, $kode_dpsj));
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
		$this->load->view('laporan/daftar-rka-detail-akun', $data);
	}

	public function detail_mutasi() 
	{
		$kode_dpsj = $this->input->post('kode_dpsj');
		$tahun = $this->input->post('tahun');
//print_r($kode_dpsj);print_r($tahun);exit();
		$sql = "SELECT * FROM view_mutasi_rincian WHERE tahun_anggaran = ? AND kode_dpsj = ? AND kode_status = 2";
        $query = $this->db->query($sql, array($tahun, $kode_dpsj));
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
		$this->load->view('laporan/daftar-rka-detail-akun-mutasi', $data);
		//print_r($data['result']);exit();
	}

    public function periode() {
        $sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

	public function detail_akun_mutasi() 
	{
		$kode_kegiatan = $this->input->post('kode_kegiatan');
        $kode_akun = $this->input->post('kode_akun');
        $kode_dana = $this->input->post('kode_dana');
		$tahun = $this->input->post('tahun');
//print_r($kode_dpsj);print_r($tahun);exit();
		$sql = "SELECT * FROM view_mutasi_rincian WHERE tahun_anggaran = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ? AND kode_status = 2";
        $query = $this->db->query($sql, array($tahun, $kode_kegiatan, $kode_akun, $kode_dana));
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
		$this->load->view('laporan/daftar-rka-detail-akun-mutasi', $data);
		//echo '<pre>';
		//print_r($data['result']);
		//echo '</pre>';
		//exit();
	}

}
