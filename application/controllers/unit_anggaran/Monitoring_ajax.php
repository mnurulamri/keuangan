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
		$this->load->helper('status_helper');
        $this->load->helper('tanggal_helper');
		$this->load->helper('menu_helper');
		$this->load->library('session');
        $this->load->library('Ajax_pagination_anggaran');
        $this->perPage = 10;
        
        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }
    /**
     * Index method to load the main view for Pengajuan
     */
    public function index() {
        
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

        $data['title'] = 'Monitoring Anggaran';
        $data['nama'] = 'test nama';
        
        // ambil kode_status dari input get
        $data['kode_status'] = $this->input->get('kode_status');

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE anggaran = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => $this->menu()) );
        $this->load->view('anggaran/monitoring-ajax-index', $data);        
        $this->load->view('template/footer');
    }

	public function menu()
	{		
		$sql = "SELECT * FROM menu where anggaran = 1 order by parent, sort";
		$query = $this->db->query($sql);
		$menu = $query->result_array();
		return $menu;
	}

    public  function data()
    {
        // set parameters for pagination
        $params = array();

        # calc offset number
        $page = $this->input->post('page');        
        $status = $this->input->post('status');       
        $tahun = $this->input->post('tahun');       
        $bulan = $this->input->post('bulan');

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

        $where = "WHERE a.id_pengajuan_pemohon = b.id AND a.kode_status > 0 AND SUBSTR(a.nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(a.nomor_pengajuan, 9, 2) = '$bulan' ";

        if ($status == 'Diretur'){
			$where .= " AND a.kode_status IN (12,14,22,32,33,42,52,62,63) AND a.nomor_pengajuan LIKE '%$keywords%'";
		} else if ($status == 'Disetujui'){
			$where .= " AND a.kode_status IN (11,13,21,31,41,44,51,61,71) AND a.nomor_pengajuan LIKE '%$keywords%'";
		} else if ($status == '1'){
			$where .= " AND a.kode_status IN (1,10,66) AND a.nomor_pengajuan LIKE '%$keywords%'";
		} else {
	        if(isset($keywords) && $keywords !== '' && isset($status) && $status !== '' && $status !== 'Semua') {  // jika ada keyword pencarian 		
				$where = "WHERE a.id_pengajuan_pemohon = b.id AND SUBSTR(a.nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(a.nomor_pengajuan, 9, 2) = '$bulan' AND a.nomor_pengajuan LIKE '%$keywords%' AND a.kode_status = '$status'";
	        } elseif(isset($keywords) && $keywords !== '') {
	            $where = "WHERE a.id_pengajuan_pemohon = b.id AND SUBSTR(a.nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(a.nomor_pengajuan, 9, 2) = '$bulan' AND a.nomor_pengajuan LIKE '%$keywords%'";
	        } elseif(isset($status) && $status !== '' && $status !== 'Semua') {
	            $where = "WHERE a.id_pengajuan_pemohon = b.id AND SUBSTR(a.nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(a.nomor_pengajuan, 9, 2) = '$bulan' AND a.kode_status = '$status'";
	        }
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
        $sql = "SELECT a.*, tanggal FROM monitoring a, pengajuan_pemohon b $where ORDER BY a.nomor_pengajuan DESC $limit";
        $query = $this->db->query($sql); // Eksekusi query untuk mengambil data

        // Menghitung total records dari tabel monitoring sesuai filter pencarian
        $sql_count = "SELECT a.id FROM monitoring a, pengajuan_pemohon b $where";
        $query_count = $this->db->query($sql_count);

        // Inisialisasi array untuk menyimpan data monitoring yang diambil dari database
        $array_monitoring = array();
        $sql_dpsj = '';
        $array_deskripsi_dpsj = array();
        $array_rincian_komitmen = array();
        $array_rincian = array();
        
        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            
            foreach ($query->result_array() as $rows){
                $array_monitoring[$rows['id']] = $rows; // Simpan setiap record ke dalam array
                $array_kode_dpsj[] = $rows['kode_dpsj']; // Simpan kode_dpsj untuk digunakan di query berikutnya
                $array_id_pengajuan_pemohon[] = $rows['id_pengajuan_pemohon']; 
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
            $sql_rincian = "SELECT * FROM view_pengajuan_rincian_realisasi WHERE id_pengajuan_pemohon IN ($array_value_id)";           
            $query_rincian = $this->db->query($sql_rincian);
            $result_rincian = $query_rincian->result_array();
            if($query_rincian->num_rows() > 0) {
                $array_rincian_komitmen = array();
                foreach ($result_rincian as $row) {
                    $array_rincian_komitmen[$row['id_pengajuan_pemohon']][] = $row;
                    $array_rincian[$row['id_pengajuan_pemohon']][] = $row;
                }
            } else {
                $array_rincian_komitmen = array();
                $array_rincian = array();
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
        $data['sql'] = $sql;
        $data['array_deskripsi_dpsj'] = $array_deskripsi_dpsj;
        $data['array_rincian_komitmen'] = $array_rincian_komitmen;
        $data['array_rincian'] = $array_rincian;
        
        # pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = 'unit_anggaran/monioring_ajax';
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
        $this->load->view('anggaran/monitoring-ajax-data', $data, false);
    }
}