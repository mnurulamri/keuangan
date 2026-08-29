<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Rekam_kehadiran extends CI_Controller 
{
	public function __construct()
    {
		parent::__construct();
        $this->load->helper('url');
		$this->load->model('Presensi_model');
        $this->load->library('session');		
	}

    public function index()
    {
        $data['nama'] = $this->session->userdata('nama_presensi');
        $this->load->view('layout/header', $data);
		$this->load->view('layout/sidebar');
        $this->load->view('kehadiran/rekam_kehadiran_view');
        $this->load->view('layout/footer');
    }

    public function jamDinding()
    {
        $array_hari = array('Sun'=>'Minggu', 'Mon'=>'Senin', 'Tue'=>'Selasa', 'Wed'=>'Rabu', 'Thu'=>'Kamis', 'Fri'=>'Jumat', 'Sat'=>'Sabtu');
        $BulanIndo = array("01"=>"Januari", "02"=>"Februari", "03"=>"Maret",
                           "04"=>"April", "05"=>"Mei", "06"=>"Juni",
                           "07"=>"Juli", "08"=>"Agustus", "09"=>"September",
                           "10"=>"Oktober", "11"=>"November", "12"=>"Desember");
        //$tgl_arr = explode('-',$tgl);
        $tahun = date('Y');
        $bulan = date('m'); 
        $hari = date('d');
        $kd_hari  = date('D', strtotime(date('Y-m-d')));
        $nama_hari = $array_hari[$kd_hari];
        $tgl_indo = $nama_hari.", ".$hari . " " . $BulanIndo[$bulan] . " ". $tahun;

        $tgl_server = date('Y-m-d');
        $data = array('fulldate'=>date('d-m-Y H:i:s'),
            'date'=>date('d'),
            'month'=>date('m'),
            'year'=>date('Y'),
            'hour'=>date('H'),
            'minute'=>date('i'),
            'second'=>date('s'),
            'tgl_indo'=>$tgl_indo,
            'tgl_server'=>$tgl_server
        );
        echo json_encode($data);
    }

    public function simpan()
    {
        echo '<h3>U N D E R C O N S T R U C T I O N</h3>';
		$nip = $this->session->userdata['logged_in_presensi']['nip_presensi']; //'123';
		
		echo $date_time = $this->input->post('date_time');
		echo $tgl1 = $this->input->post('tgl1');
		echo $this->Presensi_model->simpanPresensiLoadData($nip,$date_time);
    }

	public function _tanggal($tgl) { // fungsi atau method untuk mengubah tanggal ke format indonesia
		// variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
		$array_hari = array('Sun'=>'Minggu', 'Mon'=>'Senin', 'Tue'=>'Selasa', 'Wed'=>'Rabu', 'Thu'=>'Kamis', 'Fri'=>'Jumat', 'Sat'=>'Sabtu');
		$BulanIndo = array("01"=>"Januari", "02"=>"Februari", "03"=>"Maret",
						   "04"=>"April", "05"=>"Mei", "06"=>"Juni",
						   "07"=>"Juli", "08"=>"Agustus", "09"=>"September",
						   "10"=>"Oktober", "11"=>"November", "12"=>"Desember");
		$tgl_arr = explode('-',$tgl);
		$tahun = $tgl_arr[0]; 
		$bulan = $tgl_arr[1]; 
		$hari   = $tgl_arr[2]; 
		$kd_hari  = date('D', strtotime($tgl));
		$nama_hari = $array_hari[$kd_hari];
		$result = $hari . " " . $BulanIndo[$bulan] . " ". $tahun;
		return($result);
	}
}