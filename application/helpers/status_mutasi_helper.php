<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('nama_status_mutasi'))
{
	function nama_status_mutasi($kode_status)
	{
        $array_nama_status = array(
            0 => 'Belum Diajukan', 
            1 => 'Menunggu Konfirmasi', //Sudah Diajukan
            2 => 'Disetujui', //Disetujui Unit Anggaran
            3 => 'Dikembalikan', //Dikembalikan ke PUM
            4 => 'Dibatalkan', //'Ditolak Unit Anggaran'
        );
        return $array_nama_status[$kode_status] ?? 'Status Tidak Dikenal';
    }    
}	