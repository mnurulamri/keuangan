<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('optBulan'))
{
	function optBulan($bulan)
	{
		$array_bulan = array(
			'Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember'
		);
		$array_bulan2 = array(
			'01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
		);

		$opt_bulan = '';
		foreach($array_bulan2 as $k => $v){
			$selected = ($k == $bulan) ? 'selected' : '' ;
			$opt_bulan .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
		}
		return $opt_bulan;
	}
}

if (! function_exists('optTahun'))
{
	function optTahun($tahun)
	{
		$tahun_1 = (int)$tahun-1;
		$tahun_2 = (int)$tahun+1;

		$opt_tahun = '';
		for($i=$tahun_1; $i <= $tahun_2; $i++ ){
			$selected = ($i == $tahun) ? 'selected' : '' ;
			$opt_tahun .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
		}
		return $opt_tahun;
	}
}

if (!function_exists('check_periode_access')) {
    function check_periode_access($tahun, $bulan) {
        $CI =& get_instance();
        $CI->load->model('periode_lock_model');
        
        if (!$CI->periode_lock_model->is_periode_open($tahun, $bulan)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('get_active_periode')) {
    function get_active_periode() {
        $CI =& get_instance();
        $CI->load->model('periode_lock_model');
        return $CI->periode_lock_model->get_active_periode();
    }
}

if (!function_exists('generate_transaction_number')) {
    function generate_transaction_number($tahun, $bulan) {
        $CI =& get_instance();
        $CI->load->model('periode_lock_model');
        return $CI->periode_lock_model->generate_transaction_number($tahun, $bulan);
    }
}

if (! function_exists('namaBulan'))
{
	function namaBulan($bulan)
	{
		$array_bulan = array(
			'01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
		);
		
		return $array_bulan[$bulan];
	}
}
/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */