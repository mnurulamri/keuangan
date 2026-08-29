<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi_detail_oprek extends CI_Controller 
{
	public function __construct()
    {
		parent::__construct();
		//$this->load->database();
		$this->load->helper('url');
		$this->load->helper('tanggal_helper');
		//$this->remun_db = $this->load->database('remun', TRUE);	
		$this->load->model('Presensi_model');
		$this->load->library('session');
	}
		
	public function index()
	{
		$data = $this->dataPresensi();
		$this->load->view('kehadiran/presensi_detail_oprek', $data);
	}
	
	public function dataPresensi()
	{

		// Database configuration remunerasi
		$dbHost     = "localhost";
		$dbUsername = "remu_web_ijd";
		$dbPassword = "8hMhN!M-^Pgk+jL";
		$dbName     = "remu_web_ijd";
		
		// Create database connection
		$remun_db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
		
		// Check connection
		if ($remun_db->connect_error) {
			die("Connection failed: " . $db->connect_error);
		}
			
		// Database configuration sdm
		$dbHost     = "localhost";
		$dbUsername = "sdm_web2022";
		$dbPassword = "-7yjz9wwkNtc7cCx";
		$dbName     = "sdm_web2022";
		
		// Create database connection
		$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
		
		// Check connection
		if ($db->connect_error) {
			die("Connection failed: " . $db->connect_error);
		}

		$flag = $this->input->post('flag');

		//ambil data presensi
		//$tahun=2022; $bulan='01';
		$flag = 2;
		$data = array();
		if($flag == 1){

			$nip = '090613091';// $this->input->post('nip');
			$tahun = 2023; $this->input->post('tahun');
			$bulan = '10'; $this->input->post('bulan');
			$nama = $this->input->post('nama');
			$tanggal1 = '2023-10-01';$tahun.'-'.substr($bulan, 0, 3).'01';
			$tanggal2 = '2023-10-30';$tahun.'-'.$bulan;
			//$bulan=substr($bulan, 0, 2);

			$sql = "SELECT DISTINCT b.nip as nip, nama_bergelar, date_time, DATE(date_time) as tanggal, TIME_FORMAT(date_time, '%H:%i') as waktu
				FROM  presensi_load_data a
				LEFT JOIN presensi_master_pegawai_pns b ON a.nip = b.nip
				WHERE a.nip = '$nip' AND YEAR(date_time) = '$tahun' AND MONTH(date_time)='$bulan'";
		} else {
			$nip = $this->input->post('nip');
			$tanggal1 = $tanggal2 = $tanggal = $this->input->post('tanggal');
			$nama = $this->session->userdata['logged_in_presensi']['nama_presensi'];
			$tahun = date('Y');
			$bulan = date('m');
			$sql = "SELECT DISTINCT b.nip as nip, nama_bergelar, date_time, DATE(date_time) as tanggal, TIME_FORMAT(date_time, '%H:%i') as waktu
						FROM  presensi_load_data a
						LEFT JOIN presensi_master_pegawai_pns b ON a.nip = b.nip
						WHERE a.nip = '$nip' AND date(date_time) = '$tanggal'";
		}

		$result = $db->query($sql);
		while ($row = mysqli_fetch_object($result))
		{
			$data[$row->tanggal][] = $row;
			//$nama = $row->nama_bergelar;
		}
		
		//ambil kode jenis Cuti, Sakit dan izin
		$array = array();
		$data_array_itm = array();
		$sql = "SELECT KodeJenis, TglAwal, TglAkhir
					From TblCutiSakitIzin 
					WHERE NIP = '$nip' AND TglAwal BETWEEN '$tanggal1' AND '$tanggal2'";  //AND DATE(TglAwal) BETWEEN '$tanggal1' AND '$tanggal2' -> result cuti nggak lengkap?
		$result = $remun_db->query($sql);
		while ($row = mysqli_fetch_object($result))
		{
			$data_array_itm[$row->TglAwal][] = $row ;
		}
		
		//untuk masing-masing tanggal awal dan akhir pada baris data cuti, sakit dan izin masukkan array kode jenis ke variabel pembantu ($data_array_itm)
		$iterasi_tgl_kd_jenis = array();
		foreach($data_array_itm as $keys => $values){
			foreach($values as $k => $v){
				$iterasi_tgl_kd_jenis[] = $this->iterasi_tgl($v->TglAwal, $v->TglAkhir, $v->KodeJenis);
			}
		}

		//masukkan ke variabel array_itm (menghilangkan array[0,1,1,...] untuk menampung kode jenis berdasarkan key tanggal 
		foreach ($iterasi_tgl_kd_jenis as $key => $value) {
			foreach ($value as $k => $v) {
				$array_itm[$k] = $v;
			}
		}

		//ambil data hari libur
		$sql = "SELECT * FROM TblHariLibur WHERE YEAR(TglMulai) <= YEAR(CURDATE())";
		$result = $remun_db->query($sql);
		while ($row = mysqli_fetch_assoc($result)){
			$array_hari_libur[] = $row;
		}
		//set key sebagai tanggal dan valuenya
		foreach ($array_hari_libur as $k => $v) {
			//untuk masing-masing record pecah lagi menjadi array karena adanya interval tanggal. Masing-masing interval tanggal 1 record akan menjadi key
			$array_libur_perhari[] = $this->iterasi_tgl_libur($v['TglMulai'], $v['TglAkhir'], $v['Uraian']);
		}

		//menghilangkan array level 1
		foreach ($array_libur_perhari as $key => $value) {
			foreach ($value as $k => $v) {
				$array_libur[$k] = $v;
			}
		}

		/*ambil data shift*/
		$array_shift = array();
		$sql = "SELECT TglAwal, TglAkhir, H01, H02, H03, H04, H05, H06, H07
				FROM TblShift
				WHERE KodeShift = 'PNS'";
		$result = $remun_db->query($sql);

		if (isset($result)){
			while ($row = mysqli_fetch_assoc($result))
			{
				$array_shift = $row;
			}
		} else {
			$array_shift = array();
		}
		

		/*if(isset($array_shift)){
			$shift = shift($array_shift);
		} else {
			$shift = array();
		}*/

		//$array_shift = array();
		$shift = $this->shift($array_shift, $tanggal1, $tanggal2);
		
		//Ambil data waktu kerja
		$array_waktu_kerja = array();
		$sql = "SELECT KodeWaktuKerja, JadwalMasuk, JadwalKeluar
				FROM TblWaktuKerja";
		$result = $remun_db->query($sql) or die($db->error());
		while ($row = mysqli_fetch_assoc($result))
		{
			$array_waktu_kerja[$row['KodeWaktuKerja']] = $row;
		}

		//set waktu
		$array_waktu = array();
		if (isset($data)){
			foreach ($data as $kTanggal => $vTanggal) {
				$i = 0;
				foreach ($vTanggal as $k => $v) {
					//set array waktu supaya waktu dapat dipisah berdasarkan kolom
					//dan array yg digunakan untuk mencetak baris pada report kehadiran
					$array_waktu[$kTanggal][$i] = $v->waktu;
					$i++;
				}
			}
		} else {
			$array_waktu = array();
		}	

		# iterasi tanggal -> untuk menggenerate tanggal berdasarkan periode yang dipilih
		$item_tgl = array();
		$begin = new DateTime( $tanggal1 ); 
		$end = new DateTime( $tanggal2 ); 
		for($i = $begin; $i <= $end; $i->modify('+1 day'))
		{ 
			//echo $i->format("Y-m-d");
			if (isset($array_waktu[$i->format("Y-m-d")])){
				$item_tgl[$i->format("Y-m-d")] = $array_waktu[$i->format("Y-m-d")];
			} else {
				$item_tgl[$i->format("Y-m-d")] = array();
			}
			
		}

		$total_aktual = '';
		$jml_hadir = 0;
		$ket = '';
		$waktu_masuk = '00:00';
		
		$background_color = '';
		$color_tgl = '';
		$masuk = '';
		$pulang = '';

		//menghitung total jam
		$temp_jam = 0;
		$temp_menit = 0;
		$total_telat_masuk_jam = 0;
		$total_telat_masuk_menit = 0;

		$total_cepat_plg_jam = 0;
		$total_cepat_plg_menit = 0;

		$total_lebih_jam = 0;
		$total_lebih_menit = 0;

		$total_aktual_jam = 0;
		$total_aktual_menit = 0;

		$total_beban_jam = 0;
		$total_beban_menit = 0;
		
		foreach ($item_tgl as $kDate => $vDate) 
		{

			$jml_kolom = 1; //buat nampilin garis di cell yang ga ada datanya
			if (isset($array_itm[$kDate])){
				$itm = $array_itm[$kDate];
			} else {
				$itm = array();
			}

					if($this->namaHari($kDate) == 'Sabtu' or $this->namaHari($kDate)== 'Minggu'){
						$background_color = 'background-color:#eebbf0;';
					} elseif(isset($array_libur[$kDate])){
						$color_tgl = 'color:#A901DB;';
					} else {
						$background_color = '';
					}

			# $array item berisi data array kode jenis speerti Cuti dsj
			if (isset($array_itm[$kDate])){
				//$itm = $array_itm[$kDate];
				$item_tgl[$kDate]['itm'] = $array_itm[$kDate] ;
			} else {
				//$itm = array();
				$item_tgl[$kDate]['itm'] = '';
			}

			foreach ($vDate as $k => $v) {
				//$html.= '<td>'.$v.'</td>';
				$jml_kolom+=1;
			}

			$kolom_kosong = 5 - $jml_kolom;				
			$j = $jml_kolom-2;

			# set waktu masuk dan pulang
			if(isset($array_waktu[$kDate][0])){
				$masuk 		= $kDate.' '.$array_waktu[$kDate][0];
				$pulang 	= $kDate.' '.$array_waktu[$kDate][$j]; //$pulang 	= $kDate.' '.$array_waktu[$kDate][$j];
			} else {
				$masuk = '';
				$pulang = '';
			}

			$item_tgl[$kDate]['masuk'] = $masuk;
			$item_tgl[$kDate]['pulang'] = $pulang;

			# set aturan waktu masuk
			if(isset($array_waktu_kerja[$shift[$kDate]]['JadwalMasuk'])){
				$waktu_masuk = $array_waktu_kerja[$shift[$kDate]]['JadwalMasuk'];
				$item_tgl[$kDate]['waktu_masuk'] = $waktu_masuk;
			} else {
				$waktu_masuk = '';
				$item_tgl[$kDate]['waktu_masuk'] = '';
			}
			
			# set aturan waktu pulang
			if(isset($array_waktu_kerja[$shift[$kDate]]['JadwalKeluar'])){
				$waktu_pulang = $array_waktu_kerja[$shift[$kDate]]['JadwalKeluar'];
				$item_tgl[$kDate]['waktu_pulang'] = $waktu_pulang;
			} else {
				$waktu_pulang = '';
				$item_tgl[$kDate]['waktu_pulang'] = '';
			}

			# set variabel sementara untuk menyimpan data masuk dan pulang
			$temp_masuk = $kDate.' '.$waktu_masuk;
			$temp_pulang = $kDate.' '.$waktu_pulang;

			# set format ke tanggal waktu untuk memudahkan operasi perhitungan jam
			$masuk 		= new DateTime( $masuk );
			$pulang 	= new DateTime( $pulang );
			$temp_masuk = new DateTime( $temp_masuk );
			$temp_pulang = new DateTime( $temp_pulang );

			# set jam masuk dan pulang
			if(isset($array_waktu[$kDate][0])){
				$jam_masuk = $array_waktu[$kDate][0];
				$jam_pulang = ($j > 0) ? $array_waktu[$kDate][$j] : '' ;	
			} else {
				$jam_masuk = 0;
				$jam_pulang = 0;
			}
			$item_tgl[$kDate]['jam_masuk'] = $jam_masuk;
			$item_tgl[$kDate]['jam_pulang'] = $jam_pulang;

			$aktual 		 = $masuk->diff($pulang);
			$telat_masuk 	 = $masuk->diff($temp_masuk);
			$lebih 			 = $pulang->diff($temp_pulang);
			$cepat_plg 		 = $temp_pulang->diff($pulang);

			$item_tgl[$kDate]['aktual_jam'] = $aktual->h;
			$item_tgl[$kDate]['aktual_menit'] = $aktual->i;

			# set telat masuk
			if($itm != 'ITM')
			{
				$telat_masuk_jam = ( $masuk > $temp_masuk AND ($this->namaHari($kDate) != 'Sabtu' AND $this->namaHari($kDate) != 'Minggu') ) ? $telat_masuk->h : 0 ;
				$telat_masuk_menit = ( $masuk > $temp_masuk AND ($this->namaHari($kDate) != 'Sabtu' AND $this->namaHari($kDate) != 'Minggu') ) ? $telat_masuk->i : 0 ; 
				$telat_masuk_permenit = ($telat_masuk_jam * 60) + $telat_masuk_menit;
			} else {
				$telat_masuk_jam = 0;
				$telat_masuk_menit = 0;
				$telat_masuk_permenit = 0;
			}

			# jika in out nya kosong maka netralkan jam telat masuk
			if($jam_masuk == 0 and $jam_pulang==0){
	
				$item_tgl[$kDate]['telat_masuk_jam'] = 0;
				$item_tgl[$kDate]['telat_masuk_menit'] = 0;
				$item_tgl[$kDate]['telat_masuk_permenit'] = 0;

			} else {

				$item_tgl[$kDate]['telat_masuk_jam'] = $telat_masuk_jam;
				$item_tgl[$kDate]['telat_masuk_menit'] = $telat_masuk_menit;
				$item_tgl[$kDate]['telat_masuk_permenit'] = $telat_masuk_permenit;

			}

			
			# pulang cepat -> selain hari sabtu dan minggu
			//$cepat_plg_jam	 = ( ($pulang < $temp_pulang) AND ($pulang > $temp_masuk)  AND (namaHari($kDate) != 'Sabtu' AND namaHari($kDate) != 'Minggu' AND $array_libur[$kDate] != 'LN') ) ? $cepat_plg->h : 0 ;  
			//$cepat_plg_menit = ( ($pulang < $temp_pulang) AND ($pulang > $temp_masuk)  AND (namaHari($kDate) != 'Sabtu' AND namaHari($kDate) != 'Minggu' AND $array_libur[$kDate] != 'LN') ) ? $cepat_plg->i : 0 ;
			$cepat_plg_jam = 0; $cepat_plg_menit = 0;
			$item_tgl[$kDate]['cepat_plg_jam'] = $cepat_plg_jam;
			$item_tgl[$kDate]['cepat_plg_menit'] = $cepat_plg_menit;

			# kelebihan jam kerja
			$lebih_jam_hari_kerja 	= ( ($pulang > $temp_pulang) ) ? $lebih->h : 0 ;
			$lebih_menit_hari_kerja = ( ($pulang > $temp_pulang) ) ? $lebih->i : 0;
			$lebih_jam_hari_libur	= $aktual->h;
			$lebih_menit_hari_libur	= $aktual->i;

			if (($this->namaHari($kDate) != 'Sabtu' AND $this->namaHari($kDate) != 'Minggu')) {
				$lebih_jam = $lebih_jam_hari_kerja;
				$lebih_menit = $lebih_menit_hari_kerja;
			} else {
				$lebih_jam = $lebih_jam_hari_libur;
				$lebih_menit = $lebih_menit_hari_libur;			
			}
			
			
			if ($jam_pulang == 0) {
				# code...			
				$item_tgl[$kDate]['lebih_jam'] = 0;			
				$item_tgl[$kDate]['lebih_menit'] = 0;
			} else {
				$item_tgl[$kDate]['lebih_jam'] = $lebih_jam;			
				$item_tgl[$kDate]['lebih_menit'] = $lebih_menit;
			}

			# beban kerja
			//$beban_jam = ( $aktual > 0  AND (namaHari($kDate) != 'Sabtu' AND namaHari($kDate) != 'Minggu') ) ? 8  :  0 ;
			if($itm == 'DL') {
				$beban_jam = 8;
			} else if($jam_masuk == '' and $jam_pulang == '' ){
				$beban_jam = 0;
			} else if(  ($this->namaHari($kDate) == 'Sabtu' or $this->namaHari($kDate) == 'Minggu')){
				$beban_jam = 0;
			} else if($itm == ' DL') {
				$beban_jam = 8;
			} else {
				$beban_jam = 8;
			}
			$beban_menit = 0;  //sementara aja

			$item_tgl[$kDate]['beban_jam'] = $beban_jam;
			$item_tgl[$kDate]['beban_menit'] = $beban_menit;
			
			# flag hadir aktual
			if( $beban_jam > 0 or ($jam_masuk > 0 or $jam_pulang > 0) ){
				$hadir_aktual = 1;
			} else {
				$hadir_aktual = 0;
			}
			$item_tgl[$kDate]['hadir_aktual'] = $hadir_aktual;

			//total hadir
			if( $beban_jam > 0 or ($jam_masuk > 0 or $jam_pulang > 0) ){
				$jml_hadir += 1;
			}
			

			//menghitung total jam
			$total_telat_masuk_jam += $telat_masuk_jam;
			$total_telat_masuk_menit += $telat_masuk_menit;
			$temp_jam = floor($total_telat_masuk_menit/60);
			$temp_menit = $temp_jam * 60;
			$total_telat_masuk_jam += $temp_jam;
			$total_telat_masuk_menit -= $temp_menit;

			$total_cepat_plg_jam += $cepat_plg_jam;
			$total_cepat_plg_menit += $cepat_plg_menit;
			$temp_jam = floor($total_cepat_plg_menit/60);
			$temp_menit = $temp_jam * 60;
			$total_cepat_plg_jam += $temp_jam;
			$total_cepat_plg_menit -= $temp_menit;

			$total_lebih_jam += $lebih_jam;
			$total_lebih_menit += $lebih_menit;
			$temp_jam = floor($total_lebih_menit/60);
			$temp_menit = $temp_jam * 60;
			$total_lebih_jam += $temp_jam;
			$total_lebih_menit -= $temp_menit;

			$total_aktual_jam += $aktual->h;
			$total_aktual_menit += $aktual->i;
			$temp_jam = floor($total_aktual_menit/60);
			$temp_menit = $temp_jam * 60;
			$total_aktual_jam += $temp_jam;
			$total_aktual_menit -= $temp_menit;

			$total_beban_jam += $beban_jam;
			$total_beban_menit += $beban_menit;

			$item_tgl[$kDate]['total_telat_masuk_jam'] = $total_telat_masuk_jam;
			$item_tgl[$kDate]['total_telat_masuk_menit'] = $total_telat_masuk_menit;
			$item_tgl[$kDate]['total_cepat_plg_jam'] = $total_cepat_plg_jam;
			$item_tgl[$kDate]['total_cepat_plg_menit'] = $total_cepat_plg_menit;
			$item_tgl[$kDate]['total_lebih_jam'] = $total_lebih_jam;
			$item_tgl[$kDate]['total_lebih_menit'] = $total_lebih_menit;
			$item_tgl[$kDate]['total_aktual_jam'] = $total_aktual_jam;
			$item_tgl[$kDate]['total_aktual_menit'] = $total_aktual_menit;
			$item_tgl[$kDate]['total_beban_jam'] = $total_beban_jam;
			$item_tgl[$kDate]['total_beban_menit'] = $total_beban_menit;
			$item_tgl[$kDate]['jml_hadir'] = $jml_hadir;

			$item_tgl[$kDate]['color_tgl'] = $color_tgl;
			$item_tgl[$kDate]['background_color'] = $background_color;
		}

		$array_total_presensi = array(
			'total_telat_masuk_jam' => $total_telat_masuk_jam,
			'total_telat_masuk_menit' => $total_telat_masuk_menit,
			'total_cepat_plg_jam' =>$total_cepat_plg_jam ,
			'total_cepat_plg_menit' => $total_cepat_plg_menit,
			'total_lebih_jam' => $total_lebih_jam,
			'total_lebih_menit' => $total_lebih_menit,
			'total_aktual_jam' => $total_aktual_jam,
			'total_aktual_menit' => $total_aktual_menit,
			'total_beban_jam' => $total_beban_jam,
			'total_beban_menit' => $total_beban_menit,
			'jml_hadir' => $jml_hadir
		);

			//echo 'test';
		$data['nip'] = $nip;
		$data['nama'] = $nama;
		$data['tahun'] = $tahun;
		$data['bulan'] = $bulan;
		$data['array_presensi'] = $item_tgl;
		$data['array_waktu'] = $array_waktu;
		$data['array_waktu_kerja'] = $array_waktu_kerja;
		$data['shift'] = $shift;
		$data['array_shift'] = $array_shift;
		//$this->load->view('presensi/presensi_detail', $data);
		//echo '<pre>'; print_r($item_tgl);echo '</pre>';
		//return $item_tgl;		
		return $data;
	}

	//include('../../assets/css/table_presensi.php');
	//$shift = shift($array_shift);
	//print_r($shift);
	function shift($array_shift, $tanggal1, $tanggal2){
		$end = new DateTime($tanggal2); //new DateTime( date('Y-m-d') ); 
		if(!empty($array_shift)){
			$begin = new DateTime( $array_shift['TglAwal'] ); 
			
			$shift[0] = $array_shift['H01'];
			$shift[1] = $array_shift['H02'];
			$shift[2] = $array_shift['H03'];
			$shift[3] = $array_shift['H04'];
			$shift[4] = $array_shift['H05'];
			$shift[5] = $array_shift['H06'];
			$shift[6] = $array_shift['H07'];
		} else {
			
			$begin = new DateTime( '2017-01-02' ); 
			
			$shift[0] = '01';
			$shift[1] = '01';
			$shift[2] = '01';
			$shift[3] = '01';
			$shift[4] = '02';
			$shift[5] = '00';
			$shift[6] = '00';
		}
		//iterasi tanggal
		$j=0;
		for($i = $begin; $i <= $end; $i->modify('+1 day'))
		{    
			if ($j > 6) {
				$j = 0;
			}
			$shift_tgl[$i->format("Y-m-d")] = $shift[$j];
			$j++;
		}
		return $shift_tgl;
		
	}

	function namaHari($tgl){
		$array_hari = array('Sun'=>'Minggu', 'Mon'=>'Senin', 'Tue'=>'Selasa', 'Wed'=>'Rabu', 'Thu'=>'Kamis', 'Fri'=>'Jumat', 'Sat'=>'Sabtu');
		$tgl_arr = explode('-',$tgl);
		$tahun = $tgl_arr[0]; 
		$bulan = $tgl_arr[1]; 
		$hari   = $tgl_arr[2]; 
		$kd_hari  = date('D', strtotime($tgl));
		$nama_hari = $array_hari[$kd_hari];
		return $nama_hari;
	}

	function tanggal($tgl) { // fungsi atau method untuk mengubah tanggal ke format indonesia
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
		$result = $nama_hari.', '.$hari . " " . $BulanIndo[$bulan] . " ". $tahun;
		return($result);
	}

	function tanggalToDb($tgl_kegiatan)
	{
		$bulan = array('Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember');
		$tgl_array = explode(" ", $tgl_kegiatan);
		$d = $tgl_array[0]; 
		$month = array_search($tgl_array[1], $bulan) + 1;
		$m = (strlen($month)==2) ? $month : '0'.$month; 
		$y = $tgl_array[2];
		$tgl = $y."-".$m."-".$d;
		$tgl_kegiatan = $tgl;
		return $tgl;
	}

	function iterasi_tgl($tanggal1, $tanggal2, $KodeJenis)
	{
		# siapkan array untuk menyimpan kode jenis
		$begin = new DateTime( $tanggal1 ); 
		$end = new DateTime( $tanggal2 ); 
		for($i = $begin; $i <= $end; $i->modify('+1 day')){ 
			//echo $i->format("Y-m-d").'<br>';
			$array[$i->format("Y-m-d")] = $KodeJenis;
			//$item_tgl[$i->format("Y-m-d")] = $array_waktu[$i->format("Y-m-d")];
		}
		return $array;
	}

	function iterasi_tgl_libur($tanggal1, $tanggal2, $uraian)
	{
		# siapkan array untuk menyimpan hari libur
		$array = array();
		$begin = new DateTime( $tanggal1 ); 
		$end = new DateTime( $tanggal2 ); 
		for($i = $begin; $i <= $end; $i->modify('+1 day')){ 
			$array[$i->format("Y-m-d")] = 'LN';
		}
		return $array;
	}
}