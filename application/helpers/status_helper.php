<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('nama_status'))
{
	function nama_status($kode_status)
	{
        $array_nama_status = array(
			// Unit Kerja / PUM
            0=>'Belum Diajukan', 
            1=>'Menunggu Verifikasi Anggaran', // sudah diajukan unit kerja

			// Anggaran
            11=> 'Menunggu Verifikasi KORPUM', //'Disetujui Unit Anggaran', 
            12=>'Dipending Unit Anggaran',
			13=>'Membuat Procost',  // proses akhir ke junior akuntan

			// Korpum
            21=>'Menunggu Persetujuan Manajer Keuangan', // disetujui Korpum
            22=>'Dipending KORPUM', 
			23=>'Lanjut Procost', // proses akhir ke junior akuntan

			// Manajer Keuangan
			31=>'Proses Pencairan Dana', // disetujui Manajer Keuangan
			32=>'Dipending Manajer Keuangan', 

			// Kasir
			41=>'Pencairan UMKO', // disetujui Kasir
			42=>'Dipending Kasir', 

			// Unit Kerja / PUM
			

			// Verifikator
			51=>'Menunggu Pemeriksaan Verifikator', // disetujui Verifikator
			52=>'Diretur Verifikator',

			// Junior Akuntan
			61=>'Membuat PP', // disetujui Verifikator
			62=>'Dipending Junior Akuntan', // disetujui Verifikator

            7 =>'selesai'
        );
        return $array_nama_status[$kode_status] ?? 'Status Tidak Dikenal';
    }    
	
	function array_status()
	{
        $array_status = array(
			'0' => 'Belum Diajukan',
			'1' => 'Menunggu Verifikasi Anggaran',
			'11' => 'Menunggu Verifikasi KORPUM',
			//'12' => 'Dipending Unit Anggaran',
			'21' => 'Menunggu Persetujuan Manajer Keuangan',
			//'22' => 'Dipending KORPUM',
			'31' => 'Proses Pencairan Dana',
			//'32' => 'Dipending Manajer Keuangan',
			'41' => 'Pencairan UMKO',
			//'42' => 'Dipending Kasir',
			'51' => 'Proses Pencatatan oleh Junior Akuntan',
			'52' => 'Diretur Verifikator',
			'61' => 'Mmebuat PP',
			//'62' => 'Dipending Junior Akuntan',
			'7' => 'Selesai');

        return $array_status;
    }  
}
