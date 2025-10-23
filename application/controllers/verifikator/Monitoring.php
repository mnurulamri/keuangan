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

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
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
}