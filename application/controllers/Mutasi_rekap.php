<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_rekap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Mutasi_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('menu_helper');
        $this->load->helper('status_helper');        
        $this->load->helper('periode_helper');
        $this->load->library('Ajax_pagination_mutasi');
        $this->perPage = 20;

        // Load session library
		$this->load->library('session');
        
		// Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Daftar Mutasi';
        $data['nama'] = $this->session->userdata('logged_anggaran')['username'];
        
        // set periode penginputan
        $sql = "SELECT * FROM periode ORDER BY tahun DESC, bulan ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
		foreach ($result as $row){
			$array_tahun[] = $row['tahun'];
			$array_bulan[] = $row['bulan'];
		}
        $data['array_tahun'] = array_unique($array_tahun);
        $data['array_bulan'] = $array_bulan;
            
		//set bulan aktif
		$sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
		foreach ($result as $row){
			$array_tahun_aktif[] = $row['tahun'];
			$array_bulan_aktif[] = $row['bulan'];
		}
        $data['array_tahun_aktif'] = array_unique($array_tahun_aktif);
        $data['array_bulan_aktif'] = $array_bulan_aktif;

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('mutasi/mutasi-rekap-ajax-index', $data);    
        $this->load->view('template/footer');
    }

    public function data()
    {
        // ambil data DPSJ
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];

        // ambil kode dpsj berdasarkan kode bidang
		if($this->session->userdata('logged_anggaran')['role'] == 'pum'){
        	$sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
		} else {
			$sql = "SELECT kode_dpsj FROM unit_kerja ";
		}
        //$sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_dpsj[] = $row['kode_dpsj'];
        }
        $kode_dpsj = implode("','", $kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";
        $data[$kode_dpsj] = $kode_dpsj;
        //print_r($kode_dpsj); exit();
        $params = array();

        # calc offset number
        $page = $this->input->post('page');        
        $status = $this->input->post('status');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');

        // jika $bulan == 'All'
        if($bulan == 'All'){
            $filter_bulan = '';
        } else {
            $filter_bulan = "AND SUBSTR(nomor_pengajuan, 12, 2) = '$bulan'";
        }
        
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        $data['page'] = $page;
        //set start and limit
        $params['start'] = $offset;
        $params['limit'] = $this->perPage;
        $data['offset'] = $offset;  
		$limit = $offset.','. $this->perPage;
        
        # set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        
        if(!empty($sortBy)){
            $params['search']['sortBy'] = $sortBy;
        }

        # Tambahkan filter status pada WHERE
		if($this->session->userdata['logged_anggaran']['role'] == 'pum'){
        	$where = "WHERE kode_dpsj IN ($kode_dpsj) AND kode_status > 0 AND tahun = $tahun $filter_bulan";

			//$created_by = $this->session->userdata['logged_anggaran']['username'];
			//$where = "WHERE created_by IN ('$created_by')";
		} else {
			$where = "WHERE kode_dpsj IN ($kode_dpsj) AND kode_status > 0 AND tahun = $tahun $filter_bulan";
		}

		//$where = "WHERE kode_dpsj IN ($kode_dpsj)";

        # set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params))
        {            
            $param_limit = $params['limit'];
            $param_start = $params['start'];
            $limit = "LIMIT $param_start, $param_limit";
        } elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $param_limit = $params['limit'] ;
            $limit = "LIMIT $param_limit" ;
        }

        # get records
        $sql = "SELECT * FROM view_mutasi_pengajuan $where ORDER BY kode_grup DESC $limit";
        $query = $this->db->query($sql); //$query = $this->db->get();
        
        # get total records
        $sql_count = "SELECT kode_grup FROM view_mutasi_pengajuan $where";
        $query_count = $this->db->query($sql_count);

        $daftar_pengajuan = array();
        $array_rincian = array();
        $array_kode_grup = array();
        $sql_rincian = '';
        $array_value_kode_grup = '';

        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            foreach ($query->result_array() as $rows){
                $array_kode_grup[] = $rows['kode_grup'];
                $daftar_pengajuan[$rows['kode_grup']] = $rows;
            }

            // set nilai kode grup
            $array_value_kode_grup = implode(",", $array_kode_grup);

            // jika ada kode grup
            if(!empty($array_value_kode_grup)) {
                $array_value_kode_grup = $array_value_kode_grup;
            } else {
                $array_value_kode_grup = 0;
            }

            // ambil rincian berdasarkan kode_grup
            if($this->session->userdata['logged_anggaran']['role'] == 'pum'){
                $sql_rincian = "
                    SELECT a.*, b.kode_dpsj as kode_dpsj_rka 
                                FROM mutasi a 
                                LEFT JOIN rka b  ON (
                                    a.kode_kegiatan COLLATE utf8mb4_unicode_ci = b.kode_kegiatan COLLATE utf8mb4_unicode_ci AND 
                                    a.kode_akun COLLATE utf8mb4_unicode_ci = b.kode_akun COLLATE utf8mb4_unicode_ci AND 
                                    a.kode_dana COLLATE utf8mb4_unicode_ci = b.kode_dana COLLATE utf8mb4_unicode_ci
                                )
                    WHERE kode_status=2 AND kode_grup IN ($array_value_kode_grup) AND SUBSTR(nomor_pengajuan, 15, 4) = '$tahun' $filter_bulan
                    ORDER BY nomor_pengajuan DESC, kode_grup, id ASC";       
            } else {
                $sql_rincian = "
                    SELECT a.*, b.kode_dpsj as kode_dpsj_rka 
                                FROM mutasi a 
                                LEFT JOIN rka b  ON (
                                    a.kode_kegiatan COLLATE utf8mb4_unicode_ci = b.kode_kegiatan COLLATE utf8mb4_unicode_ci AND 
                                    a.kode_akun COLLATE utf8mb4_unicode_ci = b.kode_akun COLLATE utf8mb4_unicode_ci AND 
                                    a.kode_dana COLLATE utf8mb4_unicode_ci = b.kode_dana COLLATE utf8mb4_unicode_ci
                                )
                    WHERE kode_status=2 AND SUBSTR(nomor_pengajuan, 15, 4) = '$tahun' $filter_bulan
                    ORDER BY nomor_pengajuan DESC, kode_grup, id ASC"; 
            }

            $query_rincian = $this->db->query($sql_rincian);
            $result_rincian = $query_rincian->result_array();
            if($query_rincian->num_rows() > 0) {
                $array_rincian = array();
                foreach ($result_rincian as $row) {
                    //$array_rincian[$row['kode_grup']][] = $row;
					$array_rincian[] = $row;
                }
            } else {
                $array_rincian = array();
            }

        } else {
            $totalRec = 0;
        }

        # pagination
        $data['totalRec'] = $totalRec;
        $data['num_rows'] = $query->num_rows();
		$data['posts'] = $daftar_pengajuan;
        $data['array_rincian'] = $array_rincian;
        $data['sql'] = $sql_rincian;
        $data['array_value_kode_grup'] = $array_value_kode_grup;

        # pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = 'mutasi';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['uri_segment']   = 3;

		// Bootstrap Stylings
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<i class="glyphicon glyphicon-step-backward"></i>';
		$config['last_link'] = '<i class="glyphicon glyphicon-step-forward"></i>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="glyphicon glyphicon-triangle-left"></i>';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '<i class="glyphicon glyphicon-triangle-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

        $this->ajax_pagination_mutasi->initialize($config);


		//$data['data_petugas'] = $array_petugas;
		//$data['username'] = $this->username;

        # load the view
		/*if($this->session->userdata['logged_anggaran']['role'] == 'pum'){
			$this->load->view('mutasi/mutasi-rekap-ajax-data', $data, false);
		} else {
			$this->load->view('mutasi/admin-mutasi-rekap-ajax-data', $data, false);
		}*/
		$this->load->view('mutasi/mutasi-rekap-ajax-data', $data, false);
        //$this->load->view('mutasi/mutasi-ajax-data', $data, false);
    }
}