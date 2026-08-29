<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periksa_realisasi_ajax extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper('tanggal_helper');
        $this->load->helper('status_helper');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('Ajax_pagination_korpum');
		$this->perPage = 10;
        
        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

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
        
        // ambil kode_status dari input get
        $data['kode_status'] = $this->input->get('kode_status');

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE korpum = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        //echo '<pre>'; print_r($data); echo'</pre>';
        
        $data['title'] = 'Daftar Rekap Realisasi UMKO';
        $data['nama'] = 'test nama';
        
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => $this->menu()) );
        $this->load->view('korpum/periksa-realisasi-ajax-index', $data);        
        $this->load->view('template/footer');
    }

	public function menu()
	{		
		$sql = "SELECT * FROM menu where korpum = 1 order by parent, sort";
		$query = $this->db->query($sql);
		$menu = $query->result_array();
		return $menu;
	}

    public function data()
    {
		$this->load->database();
		// get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];

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
        
        if(!empty($keywords)){
            $params['search']['keywords'] = $keywords;
        }
        
        if(!empty($sortBy)){
            $params['search']['sortBy'] = $sortBy;
        }

        if(!empty($params['search']['sortBy'])) {
            # Asecending
            $order = $params['search']['sortBy'];
            $order_by = "ORDER BY id $order";
        } else {
            # Descending
            $order_by = 'ORDER BY id DESC';
        }

        $where = "WHERE kode_status > 0 AND SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan' ";

        if ($status == 'Diretur'){
			$where .= " AND kode_status IN (12,14,22,32,33,42,52,62,63) AND nomor_pengajuan LIKE '%$keywords%'";
		} else if ($status == 'Disetujui'){
			$where .= " AND kode_status IN (11,13,21,31,41,44,51,61,71) AND nomor_pengajuan LIKE '%$keywords%'";
		} else if ($status == '1'){
			$where .= " AND kode_status IN (1,10) AND nomor_pengajuan LIKE '%$keywords%'";
		} else {
	        if(isset($keywords) && $keywords !== '' && isset($status) && $status !== '' && $status !== 'Semua') {  // jika ada keyword pencarian 		
				$where = "WHERE SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan' AND nomor_pengajuan LIKE '%$keywords%' AND kode_status = '$status'";
	        } elseif(isset($keywords) && $keywords !== '') {
	            $where = "WHERE SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan' AND nomor_pengajuan LIKE '%$keywords%'";
	        } elseif(isset($status) && $status !== '' && $status !== 'Semua') {
	            $where = "WHERE SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan' AND kode_status = '$status'";
	        }
		}

        # set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params))
        {            
            $param_limit = $params['limit'];
            $param_start = $params['start'];
            $limit = "LIMIT $param_start, $param_limit";
        } elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params))
        {
            $param_limit = $params['limit'] ;
            $limit = "LIMIT $param_limit" ;
        }
        
        # get records
        $sql = "SELECT * FROM monitoring $where ORDER BY SUBSTR(nomor_pengajuan,9,2) DESC, SUBSTR(nomor_pengajuan,1,3) DESC $limit";
        $data['sql2'] = $sql;

        $query = $this->db->query($sql); //$query = $this->db->get();
        
        $sql_count = "SELECT id FROM monitoring $where ORDER BY SUBSTR(nomor_pengajuan,9,2) DESC, SUBSTR(nomor_pengajuan,1,3) DESC $limit";
        $query_count = $this->db->query($sql_count);

        # set tampungan data
        $daftar_pengajuan = array();
        $array_id_pengajuan = array();
        
        $get_value_id_pengajuan = 0;
        /*
        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            
            foreach ($query->result_array() as $rows){
                $array_id_pengaduan[] = $rows['id'];                
                $daftar_pengaduan[$rows['id']] = $rows;
            }

            $array_value_id_pengaduan = implode(",", $array_id_pengaduan);

            # get petugas
            $sql = "SELECT id_pengaduan, username_petugas FROM petugas WHERE id_pengaduan in ($array_value_id_pengaduan)";           
            $query_petugas = $this->db->query($sql);
            $array_petugas = $query_petugas->result_array();

        } else {
            $totalRec = 0;
            $array_value_id_pengaduan = 0;
            $array_petugas = array();
        }
        */

        $array_rincian = array();
        $array_realisasi = array();
        $array_monitoring = array();
        $array_deskripsi_dpsj = array();
        $array_kode_dpsj = array();
        
        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            
            foreach ($query->result_array() as $rows){
                $array_nomor_pengajuan[] = $rows['nomor_pengajuan'];                
                $daftar_pengajuan[$rows['id']] = $rows;

                $array_id_pengajuan_pemohon[] = $rows['id_pengajuan_pemohon'];

                $array_kode_dpsj[] = $rows['kode_dpsj']; // Simpan kode_dpsj untuk digunakan di query berikutnya
            }

            $array_value_nomor_pengajuan = implode("','", $array_nomor_pengajuan);
            $array_value_nomor_pengajuan = "'".$array_value_nomor_pengajuan."'";

            $array_value_id_pengajuan_pemohon = implode(",", $array_id_pengajuan_pemohon);

            # get rincian
            //$sql = "SELECT * FROM pengajuan_rincian WHERE nomor_pengajuan in ($array_value_nomor_pengajuan)";
            $sql = "SELECT * FROM pengajuan_rincian WHERE id_pengajuan_pemohon in ($array_value_id_pengajuan_pemohon)";           
            $query_rincian = $this->db->query($sql);
            $result_rincian = $query_rincian->result_array();
            if($query_rincian->num_rows() > 0) {
                $array_rincian = array();
                foreach ($result_rincian as $row) {
                    $array_rincian[$row['id_pengajuan_pemohon']][] = $row;
                }
            } else {
                $array_rincian = array();
            }

            // tentukan jumlah total ralisasi berdasarkan id_pengajuan_pemohon pada tabel monitoring kemudian berdasarkan id_pengajuan_pemohon ambil data id dari tabel pengajuan_rincian, selanjutnya hitung jumlah total ralisasi pada tabel realisasi berdasarkan field id dari tabel pengajuan_rincian
            $sql_realisasi = "SELECT id_pengajuan_rincian, SUM(bruto) as total_bruto, SUM(pph) as total_pph, SUM(netto) as total_netto FROM realisasi WHERE id_pengajuan_rincian IN (SELECT id FROM pengajuan_rincian WHERE id_pengajuan_pemohon IN ($array_value_id_pengajuan_pemohon)) GROUP BY id_pengajuan_rincian";
            $query_realisasi = $this->db->query($sql_realisasi);
            if($query_realisasi->num_rows() > 0) {
                $result_realisasi = $query_realisasi->result_array();
                foreach ($result_realisasi as $row) {
                    // masukkan total bruto, total pph, total netto ke dalam array rincian
                    $array_realisasi[$row['id_pengajuan_rincian']]['total_bruto'] = $row['total_bruto'];
                    $array_realisasi[$row['id_pengajuan_rincian']]['total_pph'] = $row['total_pph'];
                    $array_realisasi[$row['id_pengajuan_rincian']]['total_netto'] = $row['total_netto'];
                }
            } else {
                // jika tidak ada data realisasi, set total bruto, total pph, total netto ke 0
                foreach ($array_rincian as $key => $value) {
                    $array_realisasi[$key]['total_bruto'] = 0;
                    $array_realisasi[$key]['total_pph'] = 0;
                    $array_realisasi[$key]['total_netto'] = 0;
                }
            }

            // set kode dpsj
            $kode_dpsj = implode("','", $array_kode_dpsj);
            $kode_dpsj = "'".$kode_dpsj."'";
            $data['kode_dpsj'] = $kode_dpsj;

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
            $totalRec = 0;
            $array_value_nomor_pengajuan = 0;

            $array_value_id_pengajuan_pemohon = 0;
            //$array_petugas = array();
        }

        //print_r($query_count->num_rows()); exit();
        $data['totalRec'] = $totalRec;
        $data['num_rows'] = $query->num_rows();
		$data['posts'] = $daftar_pengajuan;
        $data['array_rincian'] = $array_rincian;
        $data['array_realisasi'] = $array_realisasi;
        $data['array_deskripsi_dpsj'] = $array_deskripsi_dpsj;
        $data['sql'] = $sql;

        # pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = 'periksa_realisasi_ajax';
        $config['total_rows']  = (int)$totalRec;
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

        $this->ajax_pagination_korpum->initialize($config);


		//$data['data_petugas'] = $array_petugas;
		//$data['username'] = $this->username;

        # load the view
        $this->load->view('korpum/periksa-realisasi-ajax-data', $data, false);
    	//echo '<pre>';print_r($data);
    }
}