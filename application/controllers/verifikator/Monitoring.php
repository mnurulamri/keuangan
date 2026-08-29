<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

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
        $data['title'] = 'Daftar Rekap Realisasi UMKO';
        $data['nama'] = 'test nama';
        $data['info_boxes'] = dashboard_status($this->session->userdata('logged_anggaran')['role']);
        
        // ambil kode_status dari input get
        $data['kode_status'] = $this->input->get('kode_status');

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE verifikator = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('verifikator/monitoring-ajax-index', $data);        
        $this->load->view('template/footer');
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
		$data['nomor_pengajuan'] = $nomor_pengajuan;
        
        // Load the form for creating a new realisasi
		$this->load->view('verifikator/realisasi_periksa', $data);
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


    public function approval()
    {
        $id_monitoring = $this->input->post('id_monitoring');
		$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $status = $this->input->post('status');
        $verifikator_keterangan = $this->input->post('verifikator_keterangan');
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';
        
        // jikas status adalah 'setujui'
        if ($status == 'setujui') {
            $data = array(
                'kode_status' => 61, // Set status to 'Menunggu Pemeriksaan Verifikator'
                'verifikator_keterangan_disetujui' => $verifikator_keterangan,
                'verifikator_username' => $username,
                'tgl_selesai_verifikasi' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $id_monitoring);
            $this->db->update('monitoring', $data);
            
            // update kode_status pada tabel pengajuan_pemohon
            $data_pengajuan = array(
                'kode_status' => '61'
            );
            $this->db->where('id', $id_pengajuan_pemohon);
            $this->db->update('pengajuan_pemohon', $data_pengajuan);

        } elseif ($status == 'retur') {
            $data = array(
                'kode_status' => 52, // Set status to 'Diretur Verifikator'
                'keterangan_retur' => $verifikator_keterangan,
                'verifikator_username' => $username,
                'tgl_retur_fakultas' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $id_monitoring);
            $this->db->update('monitoring', $data);
            
            // update kode_status pada tabel pengajuan_pemohon
            $data_pengajuan = array(
                'kode_status' => '52'
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

    public function pending(){
        $id_monitoring = $this->input->post('id_monitoring');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $verifikator_keterangan_pending = htmlentities($this->input->post('catatan_perbaikan'));
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';

        $verfikator_tanggal = $this->dateTimeToTanggalWaktu($this->input->post('verifikator_tanggal'));
        $verifikator_waktu = $this->input->post('verifikator_waktu');
        
        $kode_status = '52'; // Set status to 'Dipending Verifikator'

        $data = array(
            'kode_status' => $kode_status, // Set status to 'Dipending Verifikator'                      
            'verifikator_tgl_pending' => $verfikator_tanggal . ' ' . $verifikator_waktu,
            'verifikator_keterangan_pending' => $verifikator_keterangan_pending,
            'verifikator_username' => $username
        );
        $this->db->where('id', $id_monitoring);
        $this->db->update('monitoring', $data);
        // update kode_status pada tabel pengajuan_pemohon
        $data_pengajuan = array(
            'kode_status' => $kode_status
        );
        $this->db->where('id', $id_pengajuan_pemohon);
        $this->db->update('pengajuan_pemohon', $data_pengajuan);
    }

    public function batal(){
        $id_monitoring = $this->input->post('id_monitoring');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $verifikator_keterangan_batal = htmlentities($this->input->post('catatan_perbaikan'));
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';

        $verfikator_tanggal = $this->dateTimeToTanggalWaktu($this->input->post('verifikator_tanggal'));
        $verifikator_waktu = $this->input->post('verifikator_waktu');

        $kode_status = '53'; // Set status to 'Dibatalkan Verifikator'
        
        $data = array(
            'kode_status' => $kode_status, // Set status to 'Dibatalkan Verifikator'                      
            'verifikator_tgl_batal' => $verfikator_tanggal . ' ' . $verifikator_waktu,
            'verifikator_keterangan_batal' => $verifikator_keterangan_batal,
            'verifikator_username' => $username
        );
        $this->db->where('id', $id_monitoring);
        $this->db->update('monitoring', $data);
        // update kode_status pada tabel pengajuan_pemohon
        $data_pengajuan = array(
            'kode_status' => $kode_status
        );
        $this->db->where('id', $id_pengajuan_pemohon);
        $this->db->update('pengajuan_pemohon', $data_pengajuan);
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
            $teks_catatan_perbaikan .= 'Nama Procost: ' . $catatan['nama_kegiatan'] . '<br>';
            $teks_catatan_perbaikan .= 'Deskripsi Akun: ' . $catatan['deskripsi_akun'] . '<br>';
            $teks_catatan_perbaikan .= 'Keterangan: ' . $catatan['keterangan'] . '<br>';
            $teks_catatan_perbaikan .= 'Catatan Perbaikan: ' . $catatan['catatan_perbaikan'] . '<br><br>';
        }
        
        $data['teks_catatan_perbaikan'] = $teks_catatan_perbaikan;  
        $this->load->view('verifikator/konfirmasi_lanjut_proses', $data);
        
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
        $id_monitoring = $this->input->post('id_monitoring');
        $verifikator_keterangan_disetujui = htmlentities($this->input->post('catatan_perbaikan'));
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';

        $verfikator_tanggal = $this->dateTimeToTanggalWaktu($this->input->post('verifikator_tanggal'));
        $verifikator_waktu = $this->input->post('verifikator_waktu');

        $data = array(
            'kode_status' => 61, // Set status to 'Menunggu Pemeriksaan korpum'            
            'verifikator_tgl_disetujui' => $verfikator_tanggal . ' ' . $verifikator_waktu,
            'verifikator_keterangan_disetujui' => $verifikator_keterangan_disetujui,
            'verifikator_username' => $username
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
                'kode_status' => 61 // Set status to 'Menunggu Pemeriksaan korpum'
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
}