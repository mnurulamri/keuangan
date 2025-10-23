<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_ajax extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper('status_helper');
		$this->load->helper('url');
        $this->load->helper('menu_helper');
        $this->load->helper('dashboard_helper');
		$this->load->library('session');
		$this->load->library('Ajax_pagination_realisasi');
		$this->perPage = 2;

        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() {
        
        
        $data['title'] = 'Daftar Rekap Realisasi UMKO';
        $data['nama'] = 'test nama';
        $data['info_boxes'] = dashboard_status($this->session->userdata('logged_anggaran')['role']);

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('verifikator/monitoring-ajax-index', $data);        
        $this->load->view('template/footer');
    }

    public function data()
    {
		$this->load->database();
		// get all data from pengajuan_rincian berdasarkan kode_bidang
        /*$kode_bidang = $this->session->userdata('kode_bidang');

        // ambil kode dpsj berdasarkan kode bidang
        $sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_dpsj[] = $row['kode_dpsj'];
        }

        $kode_dpsj = implode("','", $kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";
		$data[$kode_dpsj] = $kode_dpsj;*/

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
        
        if(!empty($keywords)){
            $params['search']['keywords'] = $keywords;
        }
        
        if(!empty($sortBy)){
            $params['search']['sortBy'] = $sortBy;
        }

        /**
         * Mengecek apakah terdapat keyword pencarian pada parameter.
         * Jika ada, membangun klausa WHERE untuk pencarian berdasarkan nomor_pengajuan
         */
        $WHERE = "WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND 
                        b.id = c.id_pengajuan_rincian AND a.id_pengajuan_pemohon = d.id AND
                        d.kode_bidang = e.kode_bidang "; // set default where clause for kode_status > 41

        if(isset($keywords) && $keywords !== '' && isset($status) && $status !== '' && $status !== 'Semua') 
        {  // jika ada keyword pencarian 		
			$WHERE .= "AND a.nomor_pengajuan LIKE '%$keywords%' AND a.kode_status = '$status'";
        } 
        elseif(isset($status) && $status !== '' && $status !== 'Semua') 
        {
            $WHERE .= "AND a.kode_status = '$status'";
        } 
        else if(empty($keywords) && $status == 'Semua')
        { // jika tidak ada keyword pencarian dan status adalah Semua
            $WHERE .= "AND a.kode_status > 41"; // set default where clause for kode_status >= 41    
        } 
        else if(isset($keywords) && $keywords !== '' && $status == 'Semua')
        { // jika ada keyword pencarian dan status adalah Semua
            $WHERE .= "AND a.nomor_pengajuan LIKE '%$keywords%' AND a.kode_status > 41"; // set default where clause for kode_status >= 41
        } 
        elseif(isset($keywords) && $keywords !== '')
        { // jika ada keyword pencarian dan status adalah Semua
            $WHERE .= "AND a.nomor_pengajuan LIKE '%$keywords%'";
        }

        /**
         * Mengecek apakah terdapat parameter pengurutan pada parameter.
         * Jika ada, membangun klausa ORDER BY untuk mengurutkan hasil berdasarkan id.
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
        } elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params))
        {
            $param_limit = $params['limit'] ;
            $limit = "LIMIT $param_limit" ;
        }

        # get records
        $sql = "SELECT DISTINCT a.*, a.id_pengajuan_pemohon, c. id_pengajuan_rincian, d.kode_bidang as kode_bidang, e.nama_unit as nama_unit
                FROM monitoring a, pengajuan_rincian b, realisasi c, pengajuan_pemohon d,unit_kerja e
                $WHERE $order_by $limit";
        $query = $this->db->query($sql); //$query = $this->db->get();
        
        // menghitung jumlah record
        $sql_count = "SELECT DISTINCT a.*, a.id_pengajuan_pemohon, c. id_pengajuan_rincian, d.kode_bidang as kode_bidang, e.nama_unit as nama_unit
                        FROM monitoring a, pengajuan_rincian b, realisasi c, pengajuan_pemohon d,unit_kerja e
                        $WHERE";
        $query_count = $this->db->query($sql_count);

        # set tampungan data
        $daftar_pengajuan = array();
        $array_id_pengajuan = array();
        
        $get_value_id_pengajuan = 0;

        $array_rincian = array();
        $array_realisasi = array();
        $array_monitoring = array();
        
        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            
            foreach ($query->result_array() as $rows){
                $array_nomor_pengajuan[] = $rows['nomor_pengajuan'];                
                $daftar_pengajuan[$rows['id']] = $rows;

                $array_id_pengajuan_pemohon[] = $rows['id_pengajuan_pemohon'];
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
            $sql_realisasi = "
            SELECT id_pengajuan_rincian, SUM(bruto) as total_bruto, SUM(pph) as total_pph, SUM(netto) as total_netto 
            FROM realisasi WHERE id_pengajuan_rincian IN (
                SELECT id 
                FROM pengajuan_rincian 
                WHERE id_pengajuan_pemohon IN ($array_value_id_pengajuan_pemohon)) 
            GROUP BY id_pengajuan_rincian";
            
            $query_realisasi = $this->db->query($sql_realisasi);
            if($query_realisasi->num_rows() > 0) {
                $result_realisasi = $query_realisasi->result_array();
                foreach ($result_realisasi as $row) {
                    // jika nlai bruto > 0 masukkan total bruto, total pph, total netto ke dalam array rincian
                    if($row['total_bruto'] > 0) {
                        $array_realisasi[$row['id_pengajuan_rincian']]['total_bruto'] = $row['total_bruto'];
                        $array_realisasi[$row['id_pengajuan_rincian']]['total_pph'] = $row['total_pph'];
                        $array_realisasi[$row['id_pengajuan_rincian']]['total_netto'] = $row['total_netto'];
                    } else {
                        // jika tidak ada data realisasi, set total bruto, total pph, total netto ke 0
                        $array_realisasi[$row['id_pengajuan_rincian']]['total_bruto'] = 0;
                        $array_realisasi[$row['id_pengajuan_rincian']]['total_pph'] = 0;
                        $array_realisasi[$row['id_pengajuan_rincian']]['total_netto'] = 0;
                    }
                }
            } else {
                // jika tidak ada data realisasi, set total bruto, total pph, total netto ke 0
                foreach ($array_rincian as $key => $value) {
                    $array_realisasi[$key]['total_bruto'] = 0;
                    $array_realisasi[$key]['total_pph'] = 0;
                    $array_realisasi[$key]['total_netto'] = 0;
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
        $data['sql'] = $sql;

        # pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = 'realisasi_ajax';
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

        $this->ajax_pagination_realisasi->initialize($config);

		//$data['username'] = $this->username;

        # load the view
        $this->load->view('verifikator/monitoring-ajax-data', $data, false);
    	//echo '<pre>';print_r($data);
    }
}