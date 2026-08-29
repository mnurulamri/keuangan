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
		
		$data['title'] = 'Rekap RKA';
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
        $pagu = $this->input->post('pagu'); 
        
        if($pagu == 'fixed'){
            $flag_procost = "IN ('Procost Remun', 'Procost Umum')";
        } else if($pagu == 'unit'){
            $flag_procost = "IN ('Procost Unit')";
        } else if($pagu == 'Procost Remun'){
            $flag_procost = "IN ('Procost Remun')";
        } else if($pagu == 'Procost Umum'){
            $flag_procost = "IN ('Procost Umum')";
        } else {
            $flag_procost = "LIKE '%'";
        }

		//$sql = "SELECT * FROM view_rka_dpsj WHERE tahun_anggaran = ? ";
        $sql = "select distinct `view_anggaran_mutasi`.`tahun_anggaran` AS `tahun_anggaran`,
                `view_anggaran_mutasi`.`kode_dpsj` AS `kode_dpsj`,
                `view_anggaran_mutasi`.`deskripsi_dpsj` AS `deskripsi_dpsj`,
                sum(`view_anggaran_mutasi`.`anggaran`) AS `anggaran`,
                sum(`view_anggaran_mutasi`.`komitmen`) AS `komitmen`,
                sum(`view_anggaran_mutasi`.`aktual`) AS `aktual`,
                sum(`view_anggaran_mutasi`.`mutasi`) AS `mutasi`,
                sum(`view_anggaran_mutasi`.`sisa_anggaran`) AS `sisa_anggaran`,
                count(`view_anggaran_mutasi`.`flag_count`) AS `flag_count_mutasi` 
                from `view_anggaran_mutasi` 
                where `view_anggaran_mutasi`.`tahun_anggaran` = ? AND `view_anggaran_mutasi`.`flag_payroll` $flag_procost
                group by `view_anggaran_mutasi`.`tahun_anggaran`,`view_anggaran_mutasi`.`kode_dpsj`";
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
        $pagu = $this->input->post('pagu'); 
        
        if($pagu == 'fixed'){
            $flag_procost = "IN ('Procost Remun', 'Procost Umum')";
        } else if($pagu == 'unit'){
            $flag_procost = "IN ('Procost Unit')";
        } else if($pagu == 'Procost Remun'){
            $flag_procost = "IN ('Procost Remun')";
        } else if($pagu == 'Procost Umum'){
            $flag_procost = "IN ('Procost Umum')";
        } else {
            $flag_procost = "LIKE '%'";
        }

		//$sql = "SELECT * FROM view_rka_dpsj WHERE tahun_anggaran = ? AND kode_dpsj IN ($kode_dpsj)";
        //$sql = "SELECT * FROM view_rka_dpsj WHERE tahun_anggaran = $tahun AND kode_dpsj IN ($kode_dpsj)";
        $sql = "select distinct `view_anggaran_mutasi`.`tahun_anggaran` AS `tahun_anggaran`,
                `view_anggaran_mutasi`.`kode_dpsj` AS `kode_dpsj`,
                `view_anggaran_mutasi`.`deskripsi_dpsj` AS `deskripsi_dpsj`,
                sum(`view_anggaran_mutasi`.`anggaran`) AS `anggaran`,
                sum(`view_anggaran_mutasi`.`komitmen`) AS `komitmen`,
                sum(`view_anggaran_mutasi`.`aktual`) AS `aktual`,
                sum(`view_anggaran_mutasi`.`mutasi`) AS `mutasi`,
                sum(`view_anggaran_mutasi`.`sisa_anggaran`) AS `sisa_anggaran`,
                count(`view_anggaran_mutasi`.`flag_count`) AS `flag_count_mutasi` 
                from `view_anggaran_mutasi` 
                where `view_anggaran_mutasi`.`tahun_anggaran` = $tahun AND kode_dpsj IN ($kode_dpsj) AND `view_anggaran_mutasi`.`flag_payroll` $flag_procost
                group by `view_anggaran_mutasi`.`tahun_anggaran`,`view_anggaran_mutasi`.`kode_dpsj`";

        $query = $this->db->query($sql);
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
        $pagu = $this->input->post('pagu'); 
        
        if($pagu == 'fixed'){
            $flag_procost = "IN ('Procost Remun', 'Procost Umum')";
        } else if($pagu == 'unit'){
            $flag_procost = "IN ('Procost Unit')";
        } else if($pagu == 'Procost Remun'){
            $flag_procost = "IN ('Procost Remun')";
        } else if($pagu == 'Procost Umum'){
            $flag_procost = "IN ('Procost Umum')";
        } else {
            $flag_procost = "LIKE '%'";
        }

		//$sql = "SELECT a.*, b.nomor_pengajuan FROM view_pengajuan_rincian_realisasi a LEFT OUTER JOIN pengajuan_pemohon b ON tahun_anggaran = $tahun AND a.kode_dpsj = '$kode_dpsj' WHERE a.id_pengajuan_pemohon = b.id";

        $sql = "SELECT a.*, b.nomor_pengajuan, c.flag_payroll 
                FROM view_pengajuan_rincian_realisasi a 
                LEFT OUTER JOIN pengajuan_pemohon b ON a.id_pengajuan_pemohon = b.id
                LEFT JOIN rka c ON a.kode_dpsj = c.kode_dpsj AND a.tahun_anggaran = c.tahun_anggaran AND 
                            a.kode_kegiatan = c.kode_kegiatan AND a.kode_akun = c.kode_akun AND a.kode_dana = c.kode_dana
                WHERE a.tahun_anggaran = ? AND a.kode_dpsj = ? AND c.flag_payroll $flag_procost AND a.kode_status NOT IN (14, 32, 43, 53, 64)
                ORDER BY SUBSTR(a.nomor_pengajuan, 9, 2) DESC, SUBSTR(a.nomor_pengajuan, 1, 3) ASC";
        
        $query = $this->db->query($sql, array($tahun, $kode_dpsj));
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
        
        $data['result_dpsj'] = $this->db->get_where('unit_kerja', ['kode_dpsj' => $kode_dpsj])->row();
        
		$this->load->view('laporan/daftar-rka-detail-akun', $data);
	}

	public function detail_mutasi() 
	{
		$kode_dpsj = $this->input->post('kode_dpsj');
		$tahun = $this->input->post('tahun');
        
		$sql = "SELECT a.*, b.kode_dpsj as kode_dpsj_rka 
                FROM mutasi a 
                LEFT JOIN rka b  ON (
                    a.kode_kegiatan COLLATE utf8mb4_unicode_ci = b.kode_kegiatan COLLATE utf8mb4_unicode_ci AND 
                    a.kode_akun COLLATE utf8mb4_unicode_ci = b.kode_akun COLLATE utf8mb4_unicode_ci AND 
                    a.kode_dana COLLATE utf8mb4_unicode_ci = b.kode_dana COLLATE utf8mb4_unicode_ci
                ) 
                WHERE a.tahun_anggaran = ? AND b.kode_dpsj = ? AND a.kode_status = 2
                ORDER BY SUBSTR(a.nomor_pengajuan, 12, 2) DESC, SUBSTR(a.nomor_pengajuan, 1, 3) ASC";
        $query = $this->db->query($sql, array($tahun, $kode_dpsj));
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
		//$this->load->view('laporan/daftar-rka-detail-akun-mutasi', $data);
		print_r($data['result']);exit();
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
		$sql = "SELECT * FROM view_mutasi_rincian WHERE tahun_anggaran = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ? AND kode_status = 2
        ORDER BY SUBSTR(nomor_pengajuan, 12, 2) DESC, SUBSTR(nomor_pengajuan, 1, 3) ASC";
/*
        $sql = "SELECT * 
                FROM view_mutasi_rincian 
                WHERE tahun_anggaran = $tahun AND kode_kegiatan = '$kode_kegiatan' AND kode_akun = $kode_akun AND kode_dana = $kode_dana AND kode_status = 2
                ORDER BY SUBSTR(a.nomor_pengajuan, 12, 2) DESC, SUBSTR(a.nomor_pengajuan, 1, 3) ASC";
        echo '<pre>'; print_r($sql); echo '</pre>'; exit();*/
        $query = $this->db->query($sql, array($tahun, $kode_kegiatan, $kode_akun, $kode_dana));
        $result = $query->result_array();
        
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
		
        $this->load->view('laporan/daftar-rka-detail-akun-mutasi', $data);
		//echo '<pre>'; print_r($data['result']); echo '</pre>';		//exit();
	}

    public function detail_akun_dpsj()
    {
        $kode_dpsj = $this->input->post('kode_dpsj');
        $data['tahun'] = $tahun = $this->input->post('tahun');

        $pagu = $this->input->post('pagu'); 
        
        if($pagu == 'fixed'){
            $flag_procost = "IN ('Procost Remun', 'Procost Umum')";
        } else if($pagu == 'unit'){
            $flag_procost = "IN ('Procost Unit')";
        } else if($pagu == 'Procost Remun'){
            $flag_procost = "IN ('Procost Remun')";
        } else if($pagu == 'Procost Umum'){
            $flag_procost = "IN ('Procost Umum')";
        } else {
            $flag_procost = "LIKE '%'";
        }

        // set deskripsi dpsj
        $sql = "SELECT kode_dpsj, deskripsi_dpsj FROM unit_kerja WHERE kode_dpsj = ?";
        $query =  $this->db->query($sql, array($kode_dpsj));
        foreach($query->result_array() as $row){
            $data['deskripsi_dpsj'] = $row['deskripsi_dpsj'];
        }
        
        $sql = "SELECT * 
                FROM view_anggaran_mutasi 
                WHERE tahun_anggaran = ? AND kode_dpsj IN ($kode_dpsj) AND flag_payroll $flag_procost
                ORDER BY kode_dpsj, kode_kegiatan ASC, kode_akun ASC";
        $query = $this->db->query($sql, array($tahun));
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
        $data['sql'] = $sql;

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('laporan/daftar-rka-akun-dpsj', $data);    
        $this->load->view('template/footer');
    }

	public function detail_akun_komitmen_aktual() 
	{
        
		$tahun = $this->input->post('tahun');
		$kode_kegiatan = $this->input->post('kode_kegiatan');
        $kode_akun = $this->input->post('kode_akun');
        $kode_dana = $this->input->post('kode_dana');

		//$sql = "SELECT a.*, b.nomor_pengajuan FROM view_pengajuan_rincian_realisasi a LEFT OUTER JOIN pengajuan_pemohon b ON tahun_anggaran = $tahun AND a.kode_dpsj = '$kode_dpsj' WHERE a.id_pengajuan_pemohon = b.id";

        $sql = "SELECT a.*, b.nomor_pengajuan, c.flag_payroll 
                FROM view_pengajuan_rincian_realisasi a 
                LEFT OUTER JOIN pengajuan_pemohon b ON a.id_pengajuan_pemohon = b.id
                LEFT JOIN rka c ON a.kode_dpsj = c.kode_dpsj AND a.tahun_anggaran = c.tahun_anggaran AND 
                            a.kode_kegiatan = c.kode_kegiatan AND a.kode_akun = c.kode_akun AND a.kode_dana = c.kode_dana
                WHERE a.tahun_anggaran = $tahun AND a.kode_kegiatan = '$kode_kegiatan' AND a.kode_akun = $kode_akun AND a.kode_dana = $kode_dana AND a.kode_status NOT IN (14, 32, 43, 53, 64)
                ORDER BY SUBSTR(a.nomor_pengajuan, 9, 2) DESC, SUBSTR(a.nomor_pengajuan, 1, 3) DESC";
        
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if($query->num_rows() > 0){
            $data['result'] = $result; // default terkunci
        } else {
            $data['result'] = 'data belum tersedia';
        }
		$this->load->view('laporan/daftar-rka-detail-akun-dpsj', $data);
	}

}