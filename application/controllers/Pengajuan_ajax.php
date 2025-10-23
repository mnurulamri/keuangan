<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_ajax extends CI_Controller {
    /**
     * Constructor to initialize the controller
     * Loads necessary models, libraries, and session data
     */
    //protected $perPage;


    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
        $this->load->helper('status_helper');
        $this->load->helper('menu_helper');
		$this->load->library('session');
        $this->load->library('Ajax_pagination_pengajuan');
        $this->perPage = 2;

        // Cek apakah user sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }
    /**
     * Index method to load the main view for Pengajuan
     */
    public function index() {
        
        $data['title'] = 'Daftar Pengajuan';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('unit_kerja/pengajuan-ajax-index', $data);        
        $this->load->view('template/footer');
    }
    /**
     * Method to fetch data for Pengajuan based on kode_bidang
     */
    public function data()
    {
        $this->load->database();
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];
        // ambil kode dpsj berdasarkan kode bidang
        $sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
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

        // Tambahkan filter status pada WHERE
        $where = "WHERE kode_dpsj IN ($kode_dpsj)";

        // jika keyword dan status sama-sama terisi, maka tambahkan ke WHERE berdasarkan keyword dan status
        if(isset($keywords) && $keywords !== '' && isset($status) && $status !== '' && $status !== 'Semua') {
            $where .= " AND nomor_pengajuan LIKE '%$keywords%' AND kode_status = '$status'";
        } elseif(isset($keywords) && $keywords !== '') {
            $where .= " AND nomor_pengajuan LIKE '%$keywords%'";
        } elseif(isset($status) && $status !== '' && $status !== 'Semua') {
            $where .= " AND kode_status = '$status'";
        }
        
        //print_r($where); exit();// Debugging line to check the where clause
       /*
        if(!empty($params['search']['keywords']) || !empty($status)) {
            $where .= " AND kode_dpsj IN ($kode_dpsj)";
        }
        if(!empty($params['search']['keywords'])) {
            $keywords = $params['search']['keywords'];
            $where .= " AND nomor_pengajuan LIKE '%$keywords%'";
        }
        if(!empty($status)) {
            $where .= " AND status = '$status'";
        }

        if(!empty($params['search']['keywords'])){  // jika ada keyword pencarian 
            # set parameter
            $keywords = $params['search']['keywords'];			
			$where = "WHERE kode_dpsj IN ($kode_dpsj) AND nomor_pengajuan LIKE '%$keywords%'";
            
            # menghitung jumlah record
            $sql_count = "SELECT id FROM pengajuan_pemohon $where";
        } else { // jika tidak ada keyword pencarian
            # set parameter
            
            # menghitung jumlah record
			$where = "WHERE kode_dpsj IN ($kode_dpsj)";
            $sql_count = "SELECT id FROM pengajuan_pemohon $where";
        }
        */

        if(!empty($params['search']['sortBy'])) {
            # Asecending
            $order = $params['search']['sortBy'];
            $order_by = "ORDER BY id $order";
        } else {
            # Descending
            $order_by = 'ORDER BY id DESC';
        }

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
        $sql = "SELECT * FROM pengajuan_pemohon $where ORDER BY id DESC $limit";
        $query = $this->db->query($sql); //$query = $this->db->get();
        
        # get total records
        $sql_count = "SELECT id FROM pengajuan_pemohon $where";
        $query_count = $this->db->query($sql_count);

        $daftar_pengajuan = array();
        $array_rincian = array();
        $array_monitoring = array();
        $array_monitoring_keterangan = array();
        
        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            
            foreach ($query->result_array() as $rows){
                $array_id_pengajuan_pemohon[] = $rows['id'];                
                $daftar_pengajuan[$rows['id']] = $rows;
            }

            // get id pengajuan pemohon agar bisa digunakan untuk mengambil data rincian sesuai halaman
            $array_value_id = implode(",", $array_id_pengajuan_pemohon);

            
            # get rincian berdasarkan id pengajuan pemohon
            // jika ada id pengajuan pemohon
            if(!empty($array_value_id)) {
                $array_value_id = $array_value_id;
            } else {
                $array_value_id = 0;
            }

            // ambil rincian berdasarkan id pengajuan pemohon
            $sql_rincian = "SELECT * FROM pengajuan_rincian WHERE id_pengajuan_pemohon IN ($array_value_id)";           
            $query_rincian = $this->db->query($sql_rincian);
            $result_rincian = $query_rincian->result_array();
            if($query_rincian->num_rows() > 0) {
                $array_rincian = array();
                foreach ($result_rincian as $row) {
                    $array_rincian[$row['id_pengajuan_pemohon']][] = $row;
                }
            } else {
                $array_rincian = array();
            }

            // ambil data monitoring -> untuk menampilkan kode_status
            $sql_monitoring = "SELECT kode_status, id_pengajuan_pemohon, anggaran_keterangan_disetujui FROM monitoring WHERE id_pengajuan_pemohon IN ($array_value_id)";
            $query_monitoring = $this->db->query($sql_monitoring);
            if($query_monitoring->num_rows() > 0) {
                $array_monitoring = array();
                foreach ($query_monitoring->result_array() as $row) {
                    $array_monitoring[$row['id_pengajuan_pemohon']] = $row['kode_status'];
                    $array_monitoring_keterangan[$row['id_pengajuan_pemohon']] = $row['anggaran_keterangan_disetujui'] ?? '';
                }
            } else {
                $array_monitoring = array();
            }

        } else {
            $totalRec = 0;
            $array_value_id = 0;
            //$array_petugas = array();
        }

        # pagination
        $data['totalRec'] = $totalRec;
        $data['num_rows'] = $query->num_rows();
		$data['posts'] = $daftar_pengajuan;
        $data['array_rincian'] = $array_rincian;
        $data['sql'] = $sql;
        $data['array_value_id'] = $array_value_id;
        $data['array_monitoring'] = $array_monitoring;
        $data['array_monitoring_keterangan'] = $array_monitoring_keterangan;

        # pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = 'pengajuan_ajax';
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

        $this->ajax_pagination_pengajuan->initialize($config);


		//$data['data_petugas'] = $array_petugas;
		//$data['username'] = $this->username;

        # load the view
        $this->load->view('unit_kerja/pengajuan-ajax-data', $data, false);
    }

}