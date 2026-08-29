<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper('tanggal_helper');
        $this->load->helper('status_helper');
		$this->load->helper('url');
		$this->load->helper('menu_helper');
		$this->load->library('session');
		$this->load->library('Ajax_pagination_verifikator');
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

        
        $data['title'] = 'Daftar Rekap Realisasi UMKO';
        $data['nama'] = 'Kasir (verifikator)';

        // ambil kode_status dari input get
        $data['kode_status'] = $this->input->get('kode_status');

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE kasir = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('kasir/pengajuan-ajax-index', $data);        
        $this->load->view('template/footer');
    }

    public function data()
    {
		$this->load->database();
		// get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];


//print_r($kode_dpsj); exit();

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

        $where = "WHERE SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan' ";

		if ($status == 'Diretur'){
			$where .= " AND kode_status IN (12,22,32,33,42,52,62,63) AND nomor_pengajuan LIKE '%$keywords%' AND SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan'";
		} else if ($status == 'Disetujui'){
			$where .= " AND kode_status IN (13,21,23,51,61,71) AND nomor_pengajuan LIKE '%$keywords%' AND SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan'";
		} else {
	        if(isset($keywords) && $keywords !== '' && isset($status) && $status !== '' && $status !== 'Semua') {  // jika ada keyword pencarian 		
				$where = "WHERE nomor_pengajuan LIKE '%$keywords%' AND kode_status = '$status' AND SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan'";
	        } elseif(isset($keywords) && $keywords !== '') {
	            $where = "WHERE nomor_pengajuan LIKE '%$keywords%' AND SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan'";
	        } elseif(isset($status) && $status !== '' && $status !== 'Semua') {
	            $where = "WHERE kode_status = '$status' AND SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan'";
	        }
		}

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

        $sql = "SELECT * FROM monitoring $where ORDER BY nomor_pengajuan DESC $limit";
        $data['sql2'] = $sql;
        # get records
        $query = $this->db->query($sql); //$query = $this->db->get();
        
        $sql_count = "SELECT id FROM monitoring $where";
        $query_count = $this->db->query($sql_count);

        # set tampungan data
        $daftar_pengajuan = array();
        $array_id_pengajuan = array();
        
        $get_value_id_pengajuan = 0;

        $array_rincian = array();
        $array_realisasi = array();
        $array_monitoring = array();
        $array_deskripsi_dpsj = array();
        
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
        $config['base_url']    = 'monitoring_ajax';
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

        $this->ajax_pagination_verifikator->initialize($config);


		//$data['data_petugas'] = $array_petugas;
		//$data['username'] = $this->username;

        # load the view
        $this->load->view('kasir/pengajuan-ajax-data', $data, false);
    	//echo '<pre>';print_r($data);
    }
    
    public function periksa()
    {
		// get id
        $id = $this->input->post('id');
        $id_monitoring = $this->input->post('id_monitoring');
        
        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        // get nomor_pengajuan
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');
        if (!$nomor_pengajuan) {
            // If no nomor_pengajuan is provided, redirect to the index page
            //redirect('realisasi');
        }

        $data['id'] = $id; $data['nomor_pengajuan'] = $nomor_pengajuan;  
        $sql = "SELECT * FROM pengajuan_rincian WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();

        // retrieve realisasi data
        $sql_realisasi = "SELECT * FROM realisasi WHERE id_pengajuan_rincian = ?";
        $query_realisasi = $this->db->query($sql_realisasi, array($id));
        $result_realisasi = $query_realisasi->result_array();

        // retrieve monitoring data
        $sql_monitoring = "SELECT * FROM monitoring WHERE id = ?";
        $query_monitoring = $this->db->query($sql_monitoring, array($id_monitoring));
        $result_monitoring = $query_monitoring->result_array();

        $data['sql'] = $sql;
        $data['result'] = $result;        
        $data['sql_realisasi'] = $sql_realisasi;
        $data['result_realisasi'] = $result_realisasi;
        $data['id_monitoring'] = $id_monitoring;
        $data['result_monitoring'] = $result_monitoring;
		$data['nomor_pengajuan'] = $nomor_pengajuan;
        
        // Load the form for creating a new realisasi
		$this->load->view('kasir/pengajuan_periksa', $data);
    }
    
    public function approval()
    {
        $id_monitoring = $this->input->post('id_monitoring');
		$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $status = $this->input->post('status');
        $kasir_keterangan = $this->input->post('$kasir_keterangan');
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';
        
        // jikas status adalah 'setujui'
        if ($status == 'setujui') {
            $data = array(
                'kode_status' => 51, // Set status to 'Menunggu Pemeriksaan Verifikator'
                'kasir_spj_keterangan_disetujui' => $kasir_keterangan,
                'kasir_username' => $username,
                'kasir_spj_tgl_selesai_verifikasi' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $id_monitoring);
            $this->db->update('monitoring', $data);
            
            // update kode_status pada tabel pengajuan_pemohon
            $data_pengajuan = array(
                'kode_status' => '51'
            );
            $this->db->where('id', $id_pengajuan_pemohon);
            $this->db->update('pengajuan_pemohon', $data_pengajuan);

        } elseif ($status == 'retur') {
            $data = array(
                'kode_status' => 42, // Set status to 'Diretur Verifikator'
                'kasir_spj_keterangan_pending' => $kasir_keterangan,
                'kasir_username' => $username,
                'kasir_spj_tgl_pending' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $id_monitoring);
            $this->db->update('monitoring', $data);
            
            // update kode_status pada tabel pengajuan_pemohon
            $data_pengajuan = array(
                'kode_status' => '42'
            );
            $this->db->where('id', $id_pengajuan_pemohon);
            $this->db->update('pengajuan_pemohon', $data_pengajuan);
        }

        // Check if the update was successful
        if ($this->db->affected_rows() > 0) {
            // If the update was successful, return a success response
            echo 'Approval berhasil disimpan.';
        } else {
            // If the update failed, return an error response
            echo 'Terjadi kesalahan saat menyimpan approval.';
        }
    }

	public function konfirmasiLanjutProses()
    {
        $id_monitoring = $this->input->post('id_monitoring');
		$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');

        // retrieve monitoring data
        $sql_monitoring = "SELECT * FROM monitoring WHERE id = ?";
        $query_monitoring = $this->db->query($sql_monitoring, array($id_monitoring));
        $result_monitoring = $query_monitoring->result_array();
        $data['result_monitoring'] = $result_monitoring;  

        // ambil id_pengajuan rincian dari tabel pengajuan_rincian
        $sql = "SELECT id FROM pengajuan_rincian WHERE id_pengajuan_pemohon = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $result = $query->result_array();

        foreach ($result as $row){
            $array_id_pengajuan_rincian[] = $row['id'];
        }
        $value_id_pengajuan_rincian = implode(',', $array_id_pengajuan_rincian);

        //ambil data dari tabel realisasi berdasarkan value_id_pengajuan_rincian
        $sql_realisasi = "SELECT catatan_perbaikan, keterangan, nama_kegiatan, deskripsi_akun FROM realisasi WHERE id_pengajuan_rincian IN ($value_id_pengajuan_rincian)";
        $query_realisasi = $this->db->query($sql_realisasi);
        $result_realisasi = $query_realisasi->result_array();
        
        // masukkan ke dalam array untuk catatan perbaikan yang ada nilainya
        $data_catatan_perbaikan = array();
        foreach ($result_realisasi as $row){
            if (!empty($row['catatan_perbaikan'])) {
                $data_catatan_perbaikan[] = array(
                    'catatan_perbaikan' => $row['catatan_perbaikan'],
                    'keterangan' => $row['keterangan'],
                    'nama_kegiatan' => $row['nama_kegiatan'],
                    'deskripsi_akun' => $row['deskripsi_akun']
                );
            }
        }

        // gabungkan hasil array data_catatan_perbaikan ke dalam bentuk teks untuk ditampilkan
        $teks_catatan_perbaikan = '';
        foreach ($data_catatan_perbaikan as $index => $catatan) {
            $teks_catatan_perbaikan .= '<strong>Catatan ' . ($index + 1) . ':</strong><br>';
            $teks_catatan_perbaikan .= 'Nama Procost: ' . $catatan['nama_kegiatan'] . '<br>';
            $teks_catatan_perbaikan .= 'Deskripsi Akun: ' . $catatan['deskripsi_akun'] . '<br>';
            $teks_catatan_perbaikan .= 'Keterangan: ' . $catatan['keterangan'] . '<br>';
            $teks_catatan_perbaikan .= 'Catatan Perbaikan: ' . $catatan['catatan_perbaikan'] . '<br><br>';
        }
        
        $data['teks_catatan_perbaikan'] = $teks_catatan_perbaikan;  
        $this->load->view('kasir/pengajuan_konfirmasi_lanjut_proses', $data);
        
	}

    public function lanjutProses()
    {
        $id_monitoring = $this->input->post('id_monitoring');
        $kasir_spj_keterangan_disetujui = htmlentities($this->input->post('catatan_perbaikan'));
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';

        $kasir_tanggal = $this->dateTimeToTanggalWaktu($this->input->post('kasir_tanggal'));
        $kasir_waktu = $this->input->post('kasir_waktu');

        $data = array(
            'kode_status' => 51, // Set status to 'Menunggu Pemeriksaan korpum'            
            'kasir_spj_tgl_disetujui' => $kasir_tanggal . ' ' . $kasir_waktu,
            'kasir_spj_keterangan_disetujui' => $kasir_spj_keterangan_disetujui,
            'kasir_username' => $username
        );
        $this->db->where('id', $id_monitoring);
        $this->db->update('monitoring', $data);

        // update kode_status pada tabel pengajuan_pemohon
        $sql_monitoring = "SELECT id_pengajuan_pemohon FROM monitoring WHERE id = ?";
        $query_monitoring = $this->db->query($sql_monitoring, array($id_monitoring));
        $result_monitoring = $query_monitoring->result_array();

        if (!empty($result_monitoring)) {
            $id_pengajuan_pemohon = $result_monitoring[0]['id_pengajuan_pemohon'];
            $data_pengajuan = array(
                'kode_status' => 51 // Set status to 'Menunggu Pemeriksaan verifikator'
            );
            $this->db->where('id', $id_pengajuan_pemohon);
            $this->db->update('pengajuan_pemohon', $data_pengajuan);
        }

    }
    
    function tanggalToDb($tgl_kegiatan)
	{
		$bulan = array('Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember');
		$tgl_array = explode(" ", $tgl_kegiatan);
		$d = $tgl_array[0];
		$month = array_search($tgl_array[1], $bulan)+1;
		$m = (strlen($month)==2) ? $month : '0'.$month; 
		$y = $tgl_array[2];
		$tgl = $y."-".$m."-".$d;
		$tgl_kegiatan = $tgl;
		return $tgl;
	}

    function dbToTanggal($tanggal)
	{
		if ($tanggal=='0000-00-00') {
			$tanggal = '';
		} else {
			$array = explode('-', $tanggal);
			//set tanggal
	        $d = $array[2];
	        $m = $array[1];
	        $y = $array[0];
			//set hari
			$nama_hari = array( 0 => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu' );
			$kd_hari = date("w", mktime(0, 0, 0, $m, $d, $y));
			$hari = $nama_hari[$kd_hari];
			//set bulan
			$nama_bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
			$bulan = $nama_bulan[$m];
	        $tanggal_hari = $hari.', '.$d.' '.$bulan.' '.$y;
	        $tanggal = $d.' '.$bulan.' '.$y;
		}

        return $tanggal;
	}	

    public function hariTanggalToDb($tgl_kegiatan)
	{
		$bulan = array('Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember');
		$tgl_array = explode(" ", $tgl_kegiatan);
		$d = $tgl_array[1];
		$month = array_search($tgl_array[2], $bulan)+1;
		$m = (strlen($month)==2) ? $month : '0'.$month; 
		$y = $tgl_array[3];
		$tgl = $y."-".$m."-".$d;
		$tgl_kegiatan = $tgl;
		return $tgl;
	}

	public function dateTimeToTanggalWaktu($parameter){
        // proses jika paraameter tidak kosong atau null atau tidak bernilai 0000-00-00 00:00:00
        if(empty($parameter) || $parameter == '0000-00-00 00:00:00'){
            return '';
        } else {
            $_tanggal = explode(' ', $parameter);
            $tanggal = $_tanggal[0];
            $array = explode('-', $tanggal);
            //set tanggal
            $d = $array[2];
            $m = $array[1];
            $y = $array[0];
            //set hari
            $nama_hari = array( '0' => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu' );
            $kd_hari = date("w", mktime(0, 0, 0, $m, $d, $y));
            $hari = $nama_hari[$kd_hari];
            //set bulan
            $nama_bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
            $bulan = $nama_bulan[$m];
            $tanggal = $hari.', '.$d.' '.$bulan.' '.$y.'<br>'.$_tanggal[1];
            return $tanggal;
        }
	}

    public function konfirmasiApprovalSPJ() {	
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        //$kode_bidang = $this->session->userdata('kode_bidang');
        
        // set kode_bidang melalui kode_dpsj dari input POST
        $kode_dpsj = $this->input->post('kode_dpsj');
        $sql_kode_bidang = "SELECT kode_bidang FROM unit_kerja WHERE kode_dpsj = ?";        
        $query = $this->db->query($sql_kode_bidang, array($kode_dpsj));
        $result = $query->row_array();
        $kode_bidang = $result['kode_bidang'];        
        $data['kode_bidang'] = $kode_bidang;        
        
        // Load model untuk mendapatkan data unit
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();

        // ambil id pengajuan dari input POST
        $id_monitoring = $this->input->post('id_monitoring');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
        $data['id_monitoring'] = $id_monitoring;
        
        if (!$id_pengajuan_pemohon) {
            show_error('Nomor pengajuan tidak ditemukan.');
            return;
        }

        // ambil data tanggal dari database
        $sql = "SELECT * FROM pengajuan_pemohon WHERE id = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $result = $query->row_array();
        if (!$result) {
            show_error('Data pengajuan tidak ditemukan.');
            return;
        }
        $data['tanggal'] = $result['tanggal'];
        $data['nomor_pengajuan'] = $result['nomor_pengajuan'];
        $data['preview_nomor'] = $result['nomor_pengajuan'];
        $data['untuk'] = $result['untuk'];
        $data['tgl_diajukan'] = $result['tanggal'];
        $data['deskripsi_dpsj'] = $result['deskripsi_dpsj'];
        $data['nama_unit'] = $result['nama_unit'];

        // siapkan data pejabat
        $data_pejabat[] = array(
            'nip' => $result['nip'],
            'nama' => $result['penanggung_jawab'],
            'telp' => $result['telp'],
            'tgl_diajukan' => $result['tgl_diajukan']
        );
        
        // ambil data jabatan pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT jabatan FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$kode_bidang' ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data_pejabat[] = array(
                'jabatan' => $row['jabatan']
            );
        }

        $data['pejabat'] = $data_pejabat;
                
        // ambil nama unit
        $sql = "SELECT * FROM units WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            //$nama_unit = $row['nama_unit'];            
        }
        //$data['nama_unit'] = $nama_unit;

        // ambil kode_ddpsj
        $sql = "SELECT * FROM unit_kerja WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_dpsj[] = $row;  
            $kode_unit = $row['kode_unit']; 
            $array_kode_dpsj[$row['kode_dpsj']] = $row['kode_dpsj'];
            $array_kode_dpsj_value[] = $row['kode_dpsj']; 
        }

        $data['array_dpsj'] = $array_dpsj;        
        $data['kode_unit'] = $kode_unit;

        $kode_dpsj = implode("','", $array_kode_dpsj_value);
        $kode_dpsj = "'".$kode_dpsj."'";

        // ambil data dari tabel pengajuan_rincian berdasarkan id_pengajuan_pemohon
        $sql = "SELECT * FROM pengajuan_rincian WHERE id_pengajuan_pemohon = '$id_pengajuan_pemohon'";
        $query = $this->db->query($sql);
        $rincian = $query->result_array();
        $data['rincian'] = $rincian;

        // ambil data anggaraan dari tabel rka berdasarkan kode_dpsj
        $sql = "SELECT * FROM rka WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_akun[$row['kode_akun']] = $row['anggaran'];
        }
        //echo '<pre>';print_r($data);echo '</pre>';

        // hitung sisa anggaran
        $array_sisa_anggaran = array();
        foreach ($rincian as $row) {
            $kode_dpsj = $row['kode_dpsj'];
            $kode_kegiatan = $row['kode_kegiatan'];
            $kode_akun = $row['kode_akun'];
            $kode_dana = $row['kode_dana'];
            /*
            // ambil data anggaran awal
            $sql_anggaran = "SELECT sisa_anggaran FROM rka WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_anggaran = $this->db->query($sql_anggaran, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $anggaran_awal = $query_anggaran->row_array()['sisa_anggaran'] ?? 0;

            // ambil data total komitmen
            $sql_komitmen = "SELECT SUM(komitmen) AS total_komitmen FROM pengajuan_rincian WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_komitmen = $this->db->query($sql_komitmen, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $total_komitmen = $query_komitmen->row_array()['total_komitmen'] ?? 0;

            // ambil data realisasi
            $sql_realisasi = "SELECT SUM(jumlah) AS total_realisasi FROM realisasi WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_realisasi = $this->db->query($sql_realisasi, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $total_realisasi = $query_realisasi->row_array()['total_realisasi'] ?? 0;

            // hitung sisa anggaran
            if ($total_realisasi == 0) {
                $sisa_anggaran = number_format($anggaran_awal - $total_komitmen);
            } else {
                $sisa_anggaran = number_format($anggaran_awal - $total_realisasi);
            }

            $array_sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana] = $sisa_anggaran;
            */
            
            $sql_anggaran = "SELECT sisa_anggaran FROM view_anggaran_mutasi WHERE kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_anggaran = $this->db->query($sql_anggaran, array($kode_kegiatan, $kode_akun, $kode_dana));           
            $result = $query_anggaran->result_array();
            foreach($result as $row){
                $sisa_anggaran = $row['sisa_anggaran'];
                $array_sisa_anggaran[$kode_kegiatan][$kode_akun][$kode_dana] = number_format($sisa_anggaran);
                $key = $kode_kegiatan.$kode_akun.$kode_dana;
                $test_sisa[$key] = number_format($sisa_anggaran);
            }
        }

        $data['sisa_anggaran'] = $array_sisa_anggaran;

        // ambil data anggaran_tgl_disetujui, anggaran_keterangan dari tabel monitoring berdasarkan id_pengajuan pemohon
        $sql = "SELECT * FROM monitoring WHERE id_pengajuan_pemohon = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $monitoring = $query->row_array();
        $data['kasir_tgl_disetujui'] = $monitoring['kasir_tgl_disetujui'] ?? null;
        $data['kasir_penerima'] = $monitoring['kasir_penerima'] ?? null;
        $data['kasir_keterangan'] = $monitoring['kasir_keterangan'] ?? null;
        $data['nominal_umko_cair'] = $monitoring['nominal_disetujui_umko'] ?? '0'; //$monitoring['nominal_umko_cair'] ?? '0';
        $data['nominal_disetujui_umko'] = $monitoring['nominal_disetujui_umko'] ?? '0';        
        $data['untuk_pembayaran'] = $monitoring['nomor_pengajuan'].', '.$monitoring['uraian'];
        $data['yang_menyerahkan'] = $this->session->userdata('logged_anggaran')['username'];

        $this->load->view('kasir/pengajuan_terima_spj_konfirmasi',$data);
    }
}