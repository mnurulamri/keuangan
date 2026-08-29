<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi extends CI_Controller {

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
        $this->perPage = 10;

        // Load session library
		$this->load->library('session');
        
		// Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function test(){
        $data['title'] = 'test title';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('test', $data);
        $this->load->view('template/footer');
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
        $this->load->view('mutasi/mutasi-ajax-index', $data);    
        $this->load->view('template/footer');
    }

    public function data()
    {
        // Ambil filter tahun dan bulan dari request
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        if($bulan != 'All'){
            $filter_bulan = ' AND bulan = \'' . $bulan . '\'';
        } else {
            $filter_bulan = '';
        }

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
        
		if(empty($keywords) or $keywords == '' or !isset($keywords) or !$keywords){
			$filter_keywords = '';
		} else {
			
			$filter_keywords = "AND nomor_pengajuan LIKE '%$keywords%'";
		}

        if(!empty($sortBy)){
            $params['search']['sortBy'] = $sortBy;
        }

        # Tambahkan filter status pada WHERE
		if($this->session->userdata['logged_anggaran']['role'] == 'pum'){
        	//$where = "WHERE kode_dpsj IN ($kode_dpsj) AND tahun = '$tahun' $filter_bulan AND (nomor_pengajuan LIKE '%$keywords%' or nomor_pengajuan IS NULL)"; //
            //$where = "WHERE kode_dpsj IN ($kode_dpsj) AND tahun = '$tahun' $filter_bulan";
			$where = "WHERE kode_dpsj IN ($kode_dpsj) AND tahun = '$tahun' $filter_bulan $filter_keywords";

			//$created_by = $this->session->userdata['logged_anggaran']['username'];
			//$where = "WHERE created_by IN ('$created_by')";
		} else {
			//$where = "WHERE kode_dpsj IN ($kode_dpsj) AND tahun = '$tahun' $filter_bulan AND kode_status > 0 AND (nomor_pengajuan LIKE '%$keywords%' or nomor_pengajuan IS NULL)";
            //$where = "WHERE kode_dpsj IN ($kode_dpsj) AND tahun = '$tahun' $filter_bulan AND kode_status > 0";
			$where = "WHERE kode_dpsj IN ($kode_dpsj) AND tahun = '$tahun' $filter_bulan AND kode_status > 0 $filter_keywords";
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
            $sql_rincian = "
                SELECT a.*, b.kode_dpsj as kode_dpsj_rka 
                FROM mutasi a 
                LEFT JOIN rka b  ON (
                    a.kode_kegiatan COLLATE utf8mb4_unicode_ci = b.kode_kegiatan COLLATE utf8mb4_unicode_ci AND 
                    a.kode_akun COLLATE utf8mb4_unicode_ci = b.kode_akun COLLATE utf8mb4_unicode_ci AND 
                    a.kode_dana COLLATE utf8mb4_unicode_ci = b.kode_dana COLLATE utf8mb4_unicode_ci
                ) 
                WHERE kode_grup IN ($array_value_kode_grup) AND tahun = '$tahun' $filter_bulan";           
            $query_rincian = $this->db->query($sql_rincian);
            $result_rincian = $query_rincian->result_array();
            if($query_rincian->num_rows() > 0) {
                $array_rincian = array();
                foreach ($result_rincian as $row) {
                    $array_rincian[$row['kode_grup']][] = $row;
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
        $data['sql'] = $sql;
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
		if($this->session->userdata['logged_anggaran']['role'] == 'pum'){
			$this->load->view('mutasi/mutasi-ajax-data', $data, false);
		} else {
			$this->load->view('mutasi/admin-mutasi-ajax-data', $data, false);
		}
        //$this->load->view('mutasi/mutasi-ajax-data', $data, false);
    }

    public function form() 
    {
        $data['title'] = 'Form Mutasi';
        $data['nama'] = 'test nama';
        
        // set periode
        $data['periode'] = $this->periode();

        foreach($data['periode'] as $row){
            $tahun =$row['tahun'];
            $bulan =$row['bulan'];
        }

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();
        
        $data['kode_bidang'] = $this->session->userdata('logged_anggaran')['kode_bidang'];

        // ambil identitas pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT * FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$data[kode_bidang]' ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data_pejabat[] = array(
                'nip' => $row['nip'],
                'nama' => $row['nama'],
                'jabatan' => $row['jabatan'],
                'telp' => $row['telp']
            );
        }
        $data['pejabat'] = $data_pejabat;
        
        // ambil nama unit
        $sql = "SELECT * FROM units WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $nama_unit = $row['nama_unit'];            
        }
        $data['nama_unit'] = $nama_unit;

        // ambil kode_ddpsj
        $sql = "SELECT * FROM unit_kerja WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_dpsj[] = $row;  
            $kode_unit = $row['kode_unit'];
			$array_kode_dpsj[$row['kode_dpsj']] = $row['kode_dpsj'];  
        }
        $data['array_dpsj'] = $array_dpsj;        
        $data['kode_unit'] = $kode_unit;

        // Generate nomor pengajuan untuk preview
        //$data['preview_nomor'] = $this->Anggaran_model->generate_nomor_pengajuan($kode_unit); // Default kode
        $data['preview_nomor'] = '';


        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('unit_id', 'Unit', 'required');
        $this->form_validation->set_rules('penanggung_jawab', 'Penanggung Jawab', 'required');
        $this->form_validation->set_rules('nomor_identitas', 'NPM/NIP/NUP', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');
        $this->form_validation->set_rules('untuk_nama', 'Untuk dan Atas Nama', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/tes_menu', array('menu' => menu()) );
            $this->load->view('mutasi/mutasi_form', $data);
            $this->load->view('template/footer');
			$this->load->view('mutasi/mutasi_script');
        } else {
        }
    }

    public function detail($id) {
        $data['title'] = 'Detail Pengajuan Anggaran';
        $data['anggaran'] = $this->Anggaran_model->get_by_id($id);
        
        if (empty($data['anggaran'])) {
            show_404();
        }
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('anggaran/detail', $data);
        $this->load->view('template/footer');
    }

    public function ajukan() {
		$kode_grup = $this->input->post('kode_grup');
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$kode_unit = $this->input->post('kode_unit');

        //if ($this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'keuangan') {
            //show_404();
        //}
        
        // set nomor pengajuan
        $nomor_pengajuan = $this->Mutasi_model->generate_nomor_pengajuan($kode_unit, $tahun, $bulan);
        $nomor_urut = $this->Mutasi_model->generate_nomor_urut($kode_unit, $tahun, $bulan);
        $data = array(
            'kode_status' => 1,
            'nomor_pengajuan' => $nomor_pengajuan,
            'nomor_urut' => $nomor_urut,
            'tgl_pengajuan' => date('Y-m-d H:i:s')
        );
        //print_r($data); exit();
        // update data pada tabel mutasi
        $this->db->where('kode_grup', $kode_grup);
        $this->db->update('mutasi', $data);
    }

    public function approve() {
		$kode_grup = $this->input->post('kode_grup');

        //if ($this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'keuangan') {
            //show_404();
        //}
        
        $data = array(
            'kode_status' => 2,
            'approved_by' => $this->session->userdata('logged_anggaran')['username'],
            'approved_at' => date('Y-m-d H:i:s')
        );
        
        // update data pada tabel mutasi
        $this->db->where('kode_grup', $kode_grup);
        $this->db->update('mutasi', $data);
    }

    public function dikembalikan() {
        $kode_grup = $this->input->post('kode_grup');

        $data = array(
            'kode_status' => 3,
            'dikembalikan_by' => $this->session->userdata('logged_anggaran')['username'],
            'dikembalikan_at' => date('Y-m-d H:i:s')
        );
        
        // update data pada tabel mutasi
        $this->db->where('kode_grup', $kode_grup);
        $this->db->update('mutasi', $data);
    }

    public function dibatalkan() {
        $kode_grup = $this->input->post('kode_grup');

        $data = array(
            'kode_status' => 4,
            'dibatalkan_by' => $this->session->userdata('logged_anggaran')['username'],
            'dibatalkan_at' => date('Y-m-d H:i:s')
        );
        
        // update data pada tabel mutasi
        $this->db->where('kode_grup', $kode_grup);
        $this->db->update('mutasi', $data);
    }

    public function generate_nomor_pengajuan($kode_unit) {
        $nomor = $this->Anggaran_model->generate_nomor_pengajuan($kode_unit);
        
        $response = array(
            'nomor' => $nomor
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function simpan_rincian()
    {
        // set nama unit kerja
        //print_r($this->input->post()) ;

        $kode_dpsj = $this->input->post('kode_dpsj');
        $nomor_pengajuan = $this->input->post('nomor_pengajuan'); // masih bernilai kosong
        /*$sql = "SELECT kode_unit, nama_unit FROM unit_kerja WHERE kode_dpsj = ?";
        $query = $this->db->query($sql, array($this->input->post('kode_dpsj')));
        $result = $query->result_array();*/
        $sql = "SELECT kode_unit, nama_unit, deskripsi_dpsj FROM unit_kerja WHERE kode_dpsj = '$kode_dpsj'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach($result as $row){
            $nama_unit = $row['nama_unit'];
            $kode_unit = $row['kode_unit'];
            $deskripsi_dpsj = $row['deskripsi_dpsj'];
        }
        //print_r($kode_unit);
        
        $data_pejabat = $this->input->post('data_pejabat');
        $data_pejabat[0]['kode_bidang'] = $this->session->userdata('logged_anggaran')['kode_bidang'];
        $data_pejabat[0]['nama_unit'] = $nama_unit;
        $data_pejabat[0]['kode_unit'] = $kode_unit;
        $data_pejabat[0]['created_at'] = date('Y-m-d H:i:s');

        // insert ke tabel pengajuan_pemohon        
        foreach($data_pejabat as $key => $value){
            $array_pejabat = $value;
        }
        // $this->db->insert('pengajuan_pemohon', $array_pejabat);
        // $sql = $this->db->set($array_pejabat)->get_compiled_insert('pengajuan_pemohon');
        //$this->db->insert_id(); // ambil id dari tabel pengajuan_pemohon yang baru saja diinsert
        
        // ambil id terakhir dari tabel pengajuan_pemohon
        $sql = "SELECT MAX(id) AS id_pengajuan_pemohon FROM pengajuan_pemohon";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $id_pengajuan_pemohon = $row['id_pengajuan_pemohon']; // increment id terakhir
        }
        //$id_pengajuan_pemohon = $this->db->insert_id();

        // buat kode_grup untuk mengidentifikasi record2 tertenti dalam satu pengajuan
        $kode_grup = time();
        
        // insert ke tabel rincian pengajuan
        foreach($this->input->post('data') as $key => $value) {            
            $data_rincian[] = $value;
            // sisipkan id_pengajuan_rincian
            $data_rincian[$key]['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
            //$data_rincian[$key]['no_bukti'] = $no_bukti;
            $data_rincian[$key]['tanggal'] = date('Y-m-d');
            $data_rincian[$key]['created_at'] = date('Y-m-d H:i:s');
            $data_rincian[$key]['created_by'] = $this->session->userdata('logged_anggaran')['username'];
            $data_rincian[$key]['kode_grup'] = $kode_grup;
			$data_rincian[$key]['kode_unit'] = $kode_unit;

			// ganti kode dpsj
			$kode_kegiatan = $data_rincian[$key]['kode_kegiatan'];
			$kode_akun = $data_rincian[$key]['kode_akun'];
			$kode_dana = $data_rincian[$key]['kode_dana'];
			
			$sql = "SELECT kode_dpsj, deskripsi_dpsj FROM rka WHERE kode_kegiatan = '$kode_kegiatan' AND kode_akun = '$kode_akun' AND kode_dana = '$kode_dana' ";
			$query = $this->db->query($sql);
        	$result = $query->result_array();
			foreach($result as $row){
				$data_rincian[$key]['kode_dpsj'] = $this->input->post('kode_dpsj');
				$data_rincian[$key]['deskripsi_dpsj'] = $deskripsi_dpsj;
			}
        }
        //print_r($data_rincian); exit();
        // insert ke tabel pengjuan rincian
        foreach($data_rincian as $key => $value) {            
            $this->db->insert('mutasi', $value);
            //echo $sql = $this->db->set($value)->get_compiled_insert('mutasi');
        }
    }

    public function periode() {
        $sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function lock_data() 
    {
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $sql = "SELECT * FROM periode WHERE tahun = ? ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql, array($tahun));
        $result = $query->result_array();
        if($query->num_rows() == 0){
            $lock_data = 1; // default terkunci
        } else {
            foreach($result as $row){
                $lock_data = $row['lock_data'];            
            }
        }
        echo $lock_data;
    }

	public function konfirmasi_pengajuan() 
    {
        $kode_grup = $this->input->post('kode_grup');
        $kode_dpsj = $this->input->post('kode_dpsj');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');

        // set kode bidang
        $sql = "SELECT kode_bidang FROM unit_kerja WHERE kode_dpsj = ?";
        $query = $this->db->query($sql, array($kode_dpsj));
        $result = $query->result_array();
        foreach($result as $row){
            $kode_bidang = $row['kode_bidang'];
        }        
        
        $data['title'] = 'Form Edit Mutasi';
        $data['nama'] = 'xxx';
        
        // set periode
        $data['periode'] = $this->periode();

        foreach($data['periode'] as $row){
            $tahun =$row['tahun'];
            $bulan =$row['bulan'];
        }

        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();
         
        $data['kode_bidang'] = $kode_bidang; // $this->session->userdata('logged_anggaran')['kode_bidang'];
       
        // ambil identitas pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT * FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = ? ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql, array($data['kode_bidang']));
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data_pejabat[] = array(
                'nip' => $row['nip'],
                'nama' => $row['nama'],
                'jabatan' => $row['jabatan'],
                'telp' => $row['telp']
            );
        }
        $data['pejabat'] = $data_pejabat;
        
        // ambil nama unit
        $sql = "SELECT * FROM units WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $nama_unit = $row['nama_unit'];            
        }
        $data['nama_unit'] = $nama_unit;

        // ambil kode_ddpsj
        $sql = "SELECT * FROM unit_kerja WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_dpsj[] = $row;  
            $kode_unit = $row['kode_unit'];
			$array_kode_dpsj[$row['kode_dpsj']] = $row['kode_dpsj'];  
        }
        $data['array_dpsj'] = $array_dpsj;        
        $data['kode_unit'] = $kode_unit;
        
        $data['preview_nomor'] = '';

        // ambil detail rincian mutasi        
        $sql = "SELECT * FROM mutasi WHERE kode_grup = ?";
        $query = $this->db->query($sql, array($kode_grup));
        $result_rincian = $query->result_array();

        // ambil data sisa anggaran dari tabel view_anggaran_mutasi
        foreach($result_rincian as $row){
            $sql = "SELECT * FROM view_anggaran_mutasi WHERE tahun_anggaran = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query = $this->db->query($sql, array($tahun, $row['kode_kegiatan'], $row['kode_akun'], $row['kode_dana']));
            $result_sisa = $query->result_array();
            //$data = array();
            foreach($result_sisa as $row_sisa){
                $sisa_anggaran = $row_sisa['sisa_anggaran'];            
            }
            $array_rincian[] = array(                
                'id' => $row['id'],
                'kode_kegiatan' => $row['kode_kegiatan'],
                'nama_kegiatan' => $row['nama_kegiatan'],
                'kode_akun' => $row['kode_akun'],
                'deskripsi_akun' => $row['deskripsi_akun'],
                'kode_dana' => $row['kode_dana'],
                'mutasi' => $row['mutasi'],
                'keterangan' => $row['keterangan'],
                'sisa_anggaran' => $sisa_anggaran
            );
        }

        $data['result'] = $array_rincian;
        $data['sql'] = $sql;
        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
        $data['kode_grup'] = $kode_grup;
        $this->load->view('mutasi/mutasi_konfirmasi_pengajuan',$data);
    }
    
    // Tambahkan method baru untuk autocomplete
    public function search_project()
    {
		$kata = $this->input->post('kata');
        $dpsj = $this->input->post('dpsj');
        $tahun_anggaran = $this->input->post('tahun_anggaran');
        
		$sql = "SELECT kode_kegiatan, nama_kegiatan, kode_dana, kategori_kegiatan 
                FROM view_anggaran_mutasi 
                WHERE tahun_anggaran = '$tahun_anggaran' AND nama_kegiatan LIKE '%$kata%'
                GROUP BY nama_kegiatan ";
		$array = $this->db->query($sql);
        
		if($array->num_rows()>0){
			$kotaksuggest ='
			
			<table class="autocomplete-pc" id="test" >
				<tr>
					<th width="100px">KODE KEGIATAN</th>
					<th width="200px">NAMA KEGIATAN</th>
					<th width="100px">KODE DANA</th>
					<th width="200px">KATEGORI KEGIATAN</th>
				</tr>';
				foreach ($array->result_array() as $row){
					$kotaksuggest.= '
					<tr>
						<td><div class="isi_pc" data-value="'.$row['nama_kegiatan'].'">'.$row['kode_kegiatan'].'</div></td>
						<td><div class="isi_pc" data-value="'.$row['nama_kegiatan'].'">'.$row['nama_kegiatan'].'</div></td>
						<td><div class="isi_pc" data-value="'.$row['nama_kegiatan'].'">'.$row['kode_dana'].'</div></td>
						<td><div class="isi_pc" data-value="'.$row['nama_kegiatan'].'">'.$row['kategori_kegiatan'].'</div></td>
					</tr>';
				}
			
			$kotaksuggest.='</table>';
		} else {
			$kotaksuggest = '';
		}
		echo $kotaksuggest;
    }
}