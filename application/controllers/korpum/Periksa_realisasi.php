<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periksa_realisasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper('tanggal_helper');
        $this->load->helper('status_helper');        
        $this->load->helper('menu_helper');
        $this->load->helper('dashboard_helper');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('Ajax_pagination_realisasi');
		$this->perPage = 2;
        
        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        
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
        
        $data['title'] = 'Daftar Rekap Realisasi UMKO';
        $data['nama'] = 'test nama';
        $data['info_boxes'] = dashboard_status($this->session->userdata('logged_anggaran')['role']);

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

    // Function to save the realisasi data
    // This function will be called when the form is submitted 
    public function simpanCek()
    {
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
        
        // Load the form for creating a new realisasi
		$this->load->view('korpum/realisasi_periksa', $data);
    }

    public function view()
    {		
        // get id
        $id = $this->input->post('id');

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

        $data['sql'] = $sql;
        $data['result'] = $result;
        
        // ambil rincian realisasi
        $sql_realisasi = "SELECT * FROM realisasi WHERE id_pengajuan_rincian = ?";
        $query_realisasi = $this->db->query($sql_realisasi, array($id));
        $result_realisasi = $query_realisasi->result_array();
        $data['sql_realisasi'] = $sql_realisasi;
        $data['result_realisasi'] = $result_realisasi;

        // Load the form for creating a new realisasi

		$this->load->view('verifikator/realisasi_view', $data);
    }

    public function updateFlagCek(){
        echo $id_realisasi = $this->input->post('id_realisasi');
        echo $flag_cek = $this->input->post('flag_cek');
        
        // Update the flag_cek in the realisasi table
        $data = array(
            'flag_cek' => $flag_cek
        );

        $sql = "UPDATE realisasi SET flag_cek = ? WHERE id = ?";
        $this->db->query($sql, array($flag_cek, $id_realisasi));
        
        if ($this->db->affected_rows() > 0) {
            // If the update was successful, return a success response
            echo 'Flag cek updated successfully.';
        } else {
            // If the update failed, return an error response
            echo 'Failed to update flag cek.';
        }
    }

    public function viewCatatan()
    {
        $id = $this->input->post('id');
        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        $sql = "SELECT * FROM monitoring WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();

        if (empty($result)) {
            echo 'Tidak ada catatan untuk ditampilkan.';
            return;
        }

        // Load the view to display the catatan
        $data['result'] = $result;
        $this->load->view('verifikator/view_catatan', $data);
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
            //$teks_catatan_perbaikan .= 'Nama Procost: ' . $catatan['nama_kegiatan'] . '<br>';
            $teks_catatan_perbaikan .= 'Deskripsi Akun: ' . $catatan['deskripsi_akun'] . '<br>';
            //$teks_catatan_perbaikan .= 'Keterangan: ' . $catatan['keterangan'] . '<br>';
            $teks_catatan_perbaikan .= 'Catatan Perbaikan: ' . $catatan['catatan_perbaikan'] . '<br><br>';
        }
        
        $data['teks_catatan_perbaikan'] = $teks_catatan_perbaikan;  
        $data['id'] = $this->input->post('id'); 
        $data['realisasi'] = $this->input->post('realisasi');
        //$data['data_rincian'] = $this->input->post('data_rincian');

        //siapkan data rincian yang diambil dari tabel pengajuan_rincian
        $array_id = explode(', ', $this->input->post('id'));
        $id = $this->input->post('id');
        $data_rincian = array();
        $sql_rincian = "SELECT * FROM pengajuan_rincian WHERE id IN ($id)";
        $query_rincian = $this->db->query($sql_rincian);
        $result_rincian = $query_rincian->result_array();
        $data['data_rincian'] = $result_rincian;
        $this->load->view('korpum/konfirmasi_persetujuan', $data);
        
	}

	public function updateCatatanPerbaikan()
    {
        $id = $this->input->post('id');
		$catatan_perbaikan = $this->input->post('catatan_perbaikan');

        $data = array(
            'id' => $id,
            'catatan_perbaikan' => $catatan_perbaikan,
            'tgl_catatan_perbaikan' => date('Y-m-d')
        );
        $this->db->where('id', $id);
        $this->db->update('realisasi', $data);
            
	}

    public function lanjutProses()
    {
        // update  ke tabel pengajuan_rincian
        $id_input = $this->input->post('id_input');
        $realisasi_input = $this->input->post('realisasi_input');

        $array_id_input = explode(', ', $id_input);
        $realisasi_input = explode(', ', $realisasi_input);

		echo $korpum_tanggal = $this->hariTanggalToDb($this->input->post('korpum_tanggal'));
		echo $korpum_waktu = $this->input->post('korpum_waktu');
        
        // loop melalui setiap item dan update ke database
        foreach ($array_id_input as $index => $id) {
            // hilangkan karakter non-numeric dari realisasi
            $realisasi = preg_replace('/[^\d]/', '', $realisasi_input[$index]);
            $data_realisasi = array(
                'realisasi' => $realisasi
            );
            echo $id;
            $sql = "UPDATE pengajuan_rincian SET realisasi = ? WHERE id = ?";
            $this->db->query($sql, array($realisasi, $id));
        }

        // update ke tabel monitoring
        $id_monitoring = $this->input->post('id_monitoring');
        $korpum_keterangan_disetujui = htmlentities($this->input->post('catatan_perbaikan'));
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';
        $data = array(
            'kode_status' => 13, // Set status to 'membuat procos oleh unit anggaran'            
            'korpum_realisasi_tgl_disetujui' => $korpum_tanggal.' '.$korpum_waktu,
            'korpum_realisasi_keterangan_disetujui' => $korpum_keterangan_disetujui,
            'korpum_realisasi_username' => $username
        );

        //print_r($data);exit();
        $this->db->where('id', $id_monitoring);
        $this->db->update('monitoring', $data);

        // update ke tabel pengajuan_pemohon
        $sql = "UPDATE pengajuan_pemohon SET kode_status = 13 WHERE id = (SELECT id_pengajuan_pemohon FROM monitoring WHERE id = ?)";
        $this->db->query($sql, array($id_monitoring));

        // update flag_disetujui di tabel realisasi
        $sql = "UPDATE realisasi SET flag_disetujui = 1 WHERE id_pengajuan_rincian IN ($id_input)";
        $this->db->query($sql);
    }

    public function pending()
    {
        // dikembalikan ke pum untuk perbaikan realisasi atau SPJ
        $id_monitoring = $this->input->post('id_monitoring');
        $korpum_keterangan_pending = htmlentities($this->input->post('catatan_perbaikan'));
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';

		$korpum_tanggal = $this->hariTanggalToDb($this->input->post('korpum_tanggal'));
		$korpum_waktu = $this->input->post('korpum_waktu');

        $form = $this->input->post('form');
        
        if($form == 'D01'){
            $kode_status = 63;
        } else if($form == 'D02'){
            $kode_status = 63;
        }

        $data = array(
            'kode_status' => $kode_status, // Set status to 'perbaikan SPJ oleh PUM'  
            'korpum_realisasi_tgl_retur' => $korpum_tanggal.' '.$korpum_waktu,
            'korpum_realisasi_keterangan_pending' => $korpum_keterangan_pending,
            'korpum_realisasi_username' => $username
        );
        $this->db->where('id', $id_monitoring);
        $this->db->update('monitoring', $data);

        // update ke tabel pengajuan_pemohon
        $sql = "UPDATE pengajuan_pemohon SET kode_status = $kode_status WHERE id = (SELECT id_pengajuan_pemohon FROM monitoring WHERE id = ?)";
        $this->db->query($sql, array($id_monitoring));

    }
    
    public function pendingVerifikasi()
    {
        $id_monitoring = $this->input->post('id_monitoring');
        $catatan_perbaikan = htmlentities($this->input->post('catatan_perbaikan'));
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';
        
        // Update ke tabel monitoring
        $data = array(
            'kode_status' => 62, 
            'korpum_keterangan_pending' => $catatan_perbaikan,
            // Tambahkan field update tanggal jika diperlukan
        );
        $this->db->where('id', $id_monitoring);
        $this->db->update('monitoring', $data);
    
        // Update ke tabel pengajuan_pemohon (mengikuti logika flow Anda)
        $sql = "UPDATE pengajuan_pemohon SET kode_status = 62 
                WHERE id = (SELECT id_pengajuan_pemohon FROM monitoring WHERE id = ?)";
        $this->db->query($sql, array($id_monitoring));
    
        echo "Status berhasil diubah menjadi Pending (62).";
    }

    public function batal()
    {
        // dikembalikan ke pum untuk perbaikan realisasi atau SPJ
        $id_monitoring = $this->input->post('id_monitoring');
        $korpum_keterangan_batal = htmlentities($this->input->post('catatan_perbaikan'));
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';

		$korpum_tanggal = $this->hariTanggalToDb($this->input->post('korpum_tanggal'));
		$korpum_waktu = $this->input->post('korpum_waktu');

        $form = $this->input->post('form');
        
        $kode_status = 64;

        $data = array(
            'kode_status' => $kode_status, // Set status to 'perbaikan SPJ oleh PUM'  
            'korpum_realisasi_tgl_batal' => $korpum_tanggal.' '.$korpum_waktu,
            'korpum_realisasi_keterangan_batal' => $korpum_keterangan_batal,
            'korpum_realisasi_username' => $username
        );
        $this->db->where('id', $id_monitoring);
        $this->db->update('monitoring', $data);

        // update ke tabel pengajuan_pemohon
        $sql = "UPDATE pengajuan_pemohon SET kode_status = $kode_status WHERE id = (SELECT id_pengajuan_pemohon FROM monitoring WHERE id = ?)";
        $this->db->query($sql, array($id_monitoring));

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
}