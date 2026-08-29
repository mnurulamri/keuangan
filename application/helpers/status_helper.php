<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('nama_status'))
{
	function nama_status($kode_status)
	{
        $array_nama_status = array(
			// Unit Kerja / PUM
            0=>'Belum Diajukan', 
            1=>'Menunggu Verifikasi Anggaran',
            10=>'Menunggu Verifikasi Anggaran', // sudah diajukan unit kerja

			// Anggaran
            11=> 'Menunggu Verifikasi KORPUM', //'Disetujui Unit Anggaran', 
            12=>'Dikembalikan Unit Anggaran',
			13=>'Membuat Procost',  // proses akhir ke junior akuntan
			14=>'Dibatalkan Unit Anggaran',

			// Korpum
            21=>'Menunggu Persetujuan Manajer Anggaran dan Keuangan', // disetujui Korpum
            22=>'Dipending KORPUM', 
			23=>'Lanjut Procost', // proses akhir ke junior akuntan

			// Manajer Keuangan
			31=>'Proses Pencairan Dana', // disetujui Manajer Keuangan
			32=>'Dibatalkan Manajer Anggaran dan Keuangan', 
			33=>'Dikembalikan Manajer Anggaran dan Keuangan', 

			// Kasir
			40=>'Retur Pengajuan',
			41=>'Proses Pengisian SPJ', // disetujui Kasir
			42=>'Retur Pengisian SPJ', 
			43=>'Dibatalkan Kasir',
			44=>'Menunggu Pemeriksaan Kasir',

			// Unit Kerja / PUM
			

			// Verifikator
			51=>'Menunggu Pemeriksaan Verifikator', // ketika user sudah mengajukan SPJ
			52=>'Diretur Verifikator',
			53=>'Dibatalkan Verifikator',
			54=>'Menunggu Pemeriksaan Verifikator', // ketika user sudah mengajukan SPJ

			// Junior Akuntan
			61=>'Verifikasi Kor PUM', // disetujui Verifikator
			62=>'Dipending Korpum', // 
			63=>'Diretur Korpum', //   
			64=>'Dibatalkan Korpum', // 
			65=>'Diretur Verfikator UI', //
			66=>'Menunggu Verifikasi Anggaran', // kembali ke unit anggaran untuk pengajuan ulang
			67=>'Proses Pengisian SPJ', // kembali ke PUM untuk pengisian ulang SPJ 
			70 => 'Menunggu Persetujuan Manajer',
            71 =>'Membuat MDK',
			72 =>'Menunggu pemeriksaan verifikator Dir Keu', // saat input simpan MDK
			73 =>'Menunggu proses pembayaran oleh Dir Keu', // saat input No. PP oleh yunior akuntan,    
			74 => 'Dikembalikan verifikator UI (WC)',  // dengan perbaikan pengajuan
			75 => 'Dikembalikan verifikator UI (WoC)',  // tanpa perbaikan 
			76 => 'Transaksi selesai'
        );
        return $array_nama_status[$kode_status] ?? 'Status Tidak Dikenal';
    }    
}	
	function array_status()
	{
        $array_status = array(
			'0' => 'Belum Diajukan',
			'1' => 'Menunggu Verifikasi Anggaran',
			'10' => 'Menunggu Verifikasi Anggaran',
			'11' => 'Menunggu Verifikasi KORPUM',
			'12' => 'Ditolak Unit Anggaran',
			'13' => 'Membuat Procost',
			'14' => 'Dibatalkan Unit Anggaran',
			'21' => 'Menunggu Persetujuan Manajer Anggaran dan Keuangan',
			'22' => 'Dipending KORPUM',
			'23' => 'Lanjut Procost',
			'31' => 'Proses Pencairan Dana',
			'32' => 'Dibatalkan Manajer Anggaran dan Keuangan',
			'33' => 'Dikembalikan Manajer Anggaran dan Keuangan', 
			'40' => 'Retur Pengajuan',
			'41' => 'Proses Pengisian SPJ',
			'42' => 'Retur Pengisian SPJ',
			'43' => 'Dibatalkan Kasir',
			'44' => 'Menunggu Pemeriksaan Kasir',
			'51' => 'Menunggu Pemeriksaan Verifikator',
			'52' => 'Diretur Verifikator',
			'53' => 'Dibatalkan Verifikator',
			'61' => 'Verifikasi Kor PUM',
			'62' => 'Dipending Korpum',
			'63' => 'Diretur Korpum',
			'64' => 'Dibatalkan Korpum',
			'65' => 'Diretur Verfikator UI',
			'66' => 'Menunggu Verifikasi Anggaran',
			'67' => 'Proses Pengisian SPJ',
			'70' => 'Menunggu Persetujuan Manajer',
			'71' => 'Membuat MDK',    
			'72' => 'Menunggu pemeriksaan verifikator Dir Keu',  // saat input simpan MDK
			'73' => 'Menunggu proses pembayaran oleh Dir Keu',    
			'74' => 'Dikembalikan verifikator UI (WC)',  // dengan perbaikan pengajuan
			'75' => 'Dikembalikan verifikator UI (WoC)',  // tanpa perbaikan 
			'76' => 'Transaksi selesai'
		);

        return $array_status;
    }  

	function dokumen_sekarang($kode_status)
	{
		$array = array(
			'0'=>'PUM',
			'1'=>'Unit Anggaran',
			'10'=>'Unit Anggaran',
			'11'=>'Kor PUM',
			'12'=>'PUM',
			'13'=>'Anggaran',  // proses akhir ke junior akuntan
			'14'=>'PUM',
			'21'=>'Manajer Anggaran dan Keuangan',
			'22'=>'PUM', 
			'23'=>'Junior Akuntan',
			'31'=>'Kasir',
			'32'=>'PUM', 
			'33'=>'PUM', 
			'40'=>'PUM', 
			'41'=>'PUM', 
			'42'=>'PUM',
			'43'=>'PUM',
			'44'=>'Kasir',
			'51'=>'Verifikator',
			'52'=> 'PUM',
			'53'=> 'PUM',
			'61'=>'Kor PUM',
			'62'=>'PUM', // Dipending Korpum			
			'63'=>'PUM', // 		
			'64'=>'PUM', // 
			'65'=>'PUM',
			'66'=>'Unit Anggaran',
			'67'=>'PUM',
			'70' => 'Manajer',    
			'71' => 'Operator MDK',    
			'72' => 'Yunior Akuntan',  // saat input simpan MDK
			'73' => 'Kor PUM',    
			'74' => 'Kor PUM',  // dengan perbaikan pengajuan
			'75' => 'Kor PUM',  // tanpa perbaikan 
			'76' => 'Kor PUM'
		);
		return $array[$kode_status];
	}

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

function array_status_mutasi()
{
    $array_status = array(			
        0 => 'Belum Diajukan', 
        1 => 'Menunggu Konfirmasi', //Sudah Diajukan
        2 => 'Disetujui', //Disetujui Unit Anggaran
        3 => 'Dikembalikan', //Dikembalikan ke PUM
        4 => 'Dibatalkan', //'Ditolak Unit Anggaran'
	);
    return $array_status;
}  