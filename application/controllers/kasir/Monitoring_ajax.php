<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_ajax extends CI_Controller {
    /**
     * Constructor to initialize the controller
     * Loads necessary models, libraries, and session data
     */
    //protected $perPage;


    public function __construct() {
        parent::__construct();
        //$this->load->model('Anggaran_model');
        //$this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->database();
        $this->load->library('form_validation');
		$this->load->helper('url');
        $this->load->helper('menu_helper');
		$this->load->helper('status_helper');      
		$this->load->helper('tanggal_helper');
		$this->load->library('session');
        $this->load->library('Ajax_pagination_anggaran');
        $this->perPage = 2;
        
		// Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }
    /**
     * Index method to load the main view for Pengajuan
     */
    public function index() {
        
        $data['title'] = 'Monitoring Anggaran';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('kasir/monitoring-ajax-index', $data);        
        $this->load->view('template/footer');
    }

    public  function data()
    {
        // set parameters for pagination
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
        
        // Jika terdapat kata kunci pencarian, tambahkan ke parameter pencarian
        if(!empty($keywords)){
            $params['search']['keywords'] = $keywords;
        }
        
        // Jika terdapat parameter pengurutan, tambahkan ke parameter pencarian
        if(!empty($sortBy)){
            $params['search']['sortBy'] = $sortBy;
        }

        /**
         * Mengecek apakah terdapat keyword pencarian pada parameter.
         * Jika ada, membangun klausa WHERE untuk pencarian berdasarkan nomor_pengajuan
         * dan membuat query SQL untuk menghitung jumlah record yang sesuai.
         * Jika tidak ada keyword pencarian, membuat query SQL untuk menghitung seluruh record.
         *
         * @param array $params Parameter yang berisi data pencarian, khususnya 'search' dan 'keywords'.
         * @var string $keywords Kata kunci pencarian yang digunakan untuk filter data.
         * @var string $where Klausa WHERE SQL yang digunakan untuk filter data berdasarkan keyword.
         * @var string $sql_count Query SQL untuk menghitung jumlah record pada tabel monitoring.
         */
        if(!empty($params['search']['keywords'])){  // jika ada keyword pencarian 
            # set parameter
            $keywords = $params['search']['keywords'];			
			$where = "WHERE kode_status >= 31 AND nomor_pengajuan LIKE '%$keywords%'";
            
            # menghitung jumlah record
            $sql_count = "SELECT id FROM monitoring $where";
        } else { // jika tidak ada keyword pencarian
            # set parameter
            
            # menghitung jumlah record
			$where = "WHERE kode_status >= 31";
            $sql_count = "SELECT id FROM monitoring $where";
        }

        /**
         * Mengecek apakah terdapat parameter pengurutan (sortBy) pada parameter pencarian.
         * Jika ada, membangun klausa ORDER BY sesuai dengan nilai sortBy (misal: ASC atau DESC).
         * Jika tidak ada, secara default mengurutkan data berdasarkan id secara menurun (DESC).
         *
         * @param array $params Parameter yang berisi data pencarian, khususnya 'search' dan 'sortBy'.
         * @var string $order Nilai pengurutan yang diambil dari parameter sortBy.
         * @var string $order_by Klausa ORDER BY SQL yang digunakan untuk mengurutkan data.
         */
        if(!empty($params['search']['sortBy'])) {
            // Jika terdapat parameter sortBy, gunakan untuk mengurutkan data
            $order = $params['search']['sortBy'];
            $order_by = "ORDER BY id $order";
        } else {
            // Jika tidak ada parameter sortBy, urutkan data secara default DESC
            $order_by = 'ORDER BY id DESC';
        }

        /**
         * Menentukan batasan data (LIMIT) untuk query SQL berdasarkan parameter 'start' dan 'limit'.
         * Jika kedua parameter tersedia, maka query akan mengambil data mulai dari 'start' sebanyak 'limit' baris.
         * Jika hanya 'limit' yang tersedia, maka query hanya membatasi jumlah data sebanyak 'limit' baris dari awal.
         *
         * @param array $params Parameter yang berisi 'start' dan 'limit' untuk paginasi.
         * @var string $limit Klausa LIMIT SQL yang digunakan untuk membatasi jumlah data yang diambil.
         */
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

        // Mengambil data records dari tabel monitoring dengan filter dan paginasi
        $sql_monitoring = "SELECT * FROM monitoring $where ORDER BY id DESC $limit";
        $query = $this->db->query($sql_monitoring); // Eksekusi query untuk mengambil data

        // Menghitung total records dari tabel monitoring sesuai filter pencarian
        $query_count = $this->db->query($sql_count);

        // Inisialisasi array untuk menyimpan data monitoring yang diambil dari database
        $array_monitoring = array();
        $sql_dpsj = '';
        $array_deskripsi_dpsj = array();
        $array_kode_dpsj = array();
        
        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            
            foreach ($query->result_array() as $rows){
                $array_monitoring[$rows['id']] = $rows; // Simpan setiap record ke dalam array
                $array_kode_dpsj[] = $rows['kode_dpsj']; // Simpan kode_dpsj untuk digunakan di query berikutnya
            }

            // set kode dpsj
            $kode_dpsj = implode("','", $array_kode_dpsj);
            $kode_dpsj = "'".$kode_dpsj."'";
            $data['kode_dpsj'] = $kode_dpsj;

            // buat array untuk menyimpan deskripsi dpsj
            $array_deskripsi_dpsj = array();

            // Jika ada kode_dpsj, ambil deskripsi_dpsj dari tabel dpsj
            if(!empty($kode_dpsj)) {
                $sql_dpsj = "SELECT kode_dpsj, deskripsi_dpsj FROM unit_kerja WHERE kode_dpsj IN ($kode_dpsj)";
                $query_dpsj = $this->db->query($sql_dpsj);
                
                if($query_dpsj->num_rows() > 0) {
                    foreach ($query_dpsj->result_array() as $row) {
                        $array_deskripsi_dpsj[$row['kode_dpsj']] = $row['deskripsi_dpsj'];
                    }
                }
            }

        } else {
            // Jika tidak ada data, set total record menjadi 0
            $totalRec = 0;
        }


        // Set data untuk view
        $data['posts'] = $array_monitoring;
        $data['totalRec'] = $totalRec;
        $data['offset'] = $offset;
        $data['limit'] = $this->perPage;
        $data['page'] = $page;
        $data['sql'] = $sql_monitoring;
        $data['array_deskripsi_dpsj'] = $array_deskripsi_dpsj;
        
        # pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = 'kasir/monitoring_ajax';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['uri_segment']   = 4;

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

        $this->ajax_pagination_anggaran->initialize($config);


		//$data['data_petugas'] = $array_petugas;
		//$data['username'] = $this->username;

        # load the view
        $this->load->view('kasir/monitoring-ajax-data', $data, false);
    }
}