<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('hariTanggalToDb'))
{
	function hariTanggalToDb($tgl_kegiatan)
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
}

if (! function_exists('tanggalToDb'))
{
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
}

if (! function_exists('format_tanggal'))
{
	function format_tanggal($_tgl_kegiatan){
		$tgl = explode('/', $_tgl_kegiatan);
		$d = $tgl[1];
		$m = $tgl[0];
		$y = $tgl[2];
		$tgl_kegiatan = $y.'-'.$m.'-'.$d;
		return $tgl_kegiatan;
	}
}

if (! function_exists('today'))
{
	function today()
	{
		//set tanggal
        $d = date('d');
        $m = date('n');
        $y = date('Y');
		//set hari
		$nama_hari = array( 0 => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu' );
		$kd_hari = date("w", mktime(0, 0, 0, $m, $d, $y));
		$hari = $nama_hari[$kd_hari];
		//set bulan
		$nama_bulan = array(' ','Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember');
		$bulan = $nama_bulan[$m];
        $tanggal = $hari.', '.$d.' '.$bulan.' '.$y;
        return $tanggal;
	}	
}

if (! function_exists('tanggal_sekarang'))
{
	function tanggal_sekarang(){
		$array_bulan = array(
			'01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
		);
		//$tgl = explode('/', $_tgl_kegiatan);
		$d = date('d');
		$m = $array_bulan[date('m')];
		$y = date('Y');
		$tgl = $d.' '.$m.' '.$y;
		return $tgl;
	}
}

if (! function_exists('dbToTanggal'))
{
	function dbToTanggal($tanggal)
	{
		if ($tanggal=='0000-00-00' or $tanggal=='' or $tanggal==null) {
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

if (! function_exists('formatDb'))
{
	function formatDb($tanggal){
		$array_bulan = array(
			'Januari'=>'01','Februari'=>'02','Maret'=>'03','April'=>'04','Mei'=>'05', 'Juni'=>'06','Juli'=>'07','Agustus'=>'08','September'=>'09','Oktober'=>'10','November'=>'11','Desember'=>'12'
		);
		$array = explode(' ', $tanggal);
		$d = $array[0];
		$m = $array_bulan[$array[1]];
		$y = $array[2];
		$tgl = $y.'-'.$m.'-'.$d;
		return $tgl;
	}
}

if (! function_exists('dateTimeToTanggal'))
{
	function dateTimeToTanggal($parameter)
	{		
		if ($parameter == '0000-00-00 00:00:00') {
			$tanggal = '';
		} else {			
		
			$_tanggal = explode(" ", $parameter);
			$tanggal = $_tanggal[0];
			$array = explode("-", $tanggal);
			//set tanggal
		    $d = $array[2];
		    $m = $array[1];
		    $y = $array[0];
			
			//set bulan
			$nama_bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
			$bulan = $nama_bulan[$m];
		    $tanggal = $d.' '.$bulan.' '.$y;
		}
	    return $tanggal;
	}
}

if (! function_exists('dateTimeToTanggalWaktu'))
{
	function dateTimeToTanggalWaktu($parameter){
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
	    $tanggal = $d.' '.$bulan.' '.$y.'<br>'.$_tanggal[1];
	    return $tanggal;
	}
}

if (! function_exists('tanggalSidang'))
{
	function tanggalSidang($start_date_sidang, $end_date_sidang){
		# rubah ke format tanggal
		if ($start_date_sidang == '0000-00-00 00:00:00') {
			$tgl_sidang = '';
			$jam_mulai = '';
			$menit_mulai = '';
			$jam_selesai = '';
			$menit_selesai = '';
		} else {
			$tgl_sidang = dateTimeToTanggal($start_date_sidang).'<div>Pukul: ';
			$jam_mulai = substr($start_date_sidang, 11, 2).':';
			$menit_mulai = substr($start_date_sidang, 14, 2).' - ';
			$jam_selesai = substr($end_date_sidang, 11, 2).':';
			$menit_selesai = substr($end_date_sidang, 14, 2).'</div>';
		}
		
		$jadwal_sidang = $tgl_sidang.' '.$jam_mulai.$menit_mulai.$jam_selesai.$menit_selesai;
		return $jadwal_sidang;
	}
}
/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */