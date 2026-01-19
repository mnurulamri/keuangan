<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

class Form_ko_docx_generator {
    public function generateDetailedTable($templatePath, $outputPath, $data, $rincianData, $array, $array_tgl) {
        try {
            $template = new TemplateProcessor($templatePath);
            
            // Replace data utama
            foreach ($data as $key => $value) {
                $template->setValue($key, $value);
            }
            
            // Clone row untuk tabel rincian
            $template->cloneRow('row_number', count($rincianData));
            
            // Isi data tabel
            foreach ($rincianData as $index => $item) {
                $rowNumber = $index + 1;
                
                $template->setValue('row_number#' . $rowNumber, $rowNumber);
                $template->setValue('tgl_pengajuan#' . $rowNumber, $this->dbToTanggal($array_tgl[$array[$item['id']]] ?? ''));
                $template->setValue('nomor_pengajuan#' . $rowNumber, $item['nomor_pengajuan'] ?? '');
                $template->setValue('uraian#' . $rowNumber, $item['uraian'] ?? '');
                $template->setValue('nominal_pengajuan#' . $rowNumber, number_format($item['nominal_disetujui_umko']) ?? '');
                $template->setValue('nominal_diterima#' . $rowNumber, number_format($item['nominal_disetujui_umko']) ?? '');
            }
            
            // Hitung summary
            //$summary = $this->calculateSummary($rincianData);
            
            $template->setValue('total_pengajuan', $data['total_pengajuan']);
            $template->setValue('total_penarikan', $data['total_penarikan']);
            
            $template->saveAs($outputPath);
            
            return [
                'success' => true,
                'file_path' => $outputPath,
                'summary' => $summary
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function formatCurrency($amount) {
        return number_format($amount, 0, ',', '.');
    }
    
    /*private function calculateSummary($rincianData) {
        // hitung total pengajuan dan total penarikan
        $total_pengajuan = 0;
        $total_penarikan = 0;
        foreach ($result as $row) {
            $total_pengajuan += $row['nominal_disetujui_umko'];
            $total_penarikan += $row['nominal_umko_cair'];
        }
        return [
            'total_pengajuan' => $total_pengajuan,
            'total_penarikan' => $total_penarikan
        ];
    }*/
    
    private function terbilang($number) {
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        if ($number < 12)
            return "" . $huruf[$number];
        elseif ($number < 20)
            return $this->terbilang($number - 10) . " Belas";
        elseif ($number < 100)
            return $this->terbilang($number / 10) . " Puluh" . $this->terbilang($number % 10);
        elseif ($number < 200)
            return " Seratus" . $this->terbilang($number - 100);
        elseif ($number < 1000)
            return $this->terbilang($number / 100) . " Ratus" . $this->terbilang($number % 100);
        elseif ($number < 2000)
            return " Seribu" . $this->terbilang($number - 1000);
        elseif ($number < 1000000)
            return $this->terbilang($number / 1000) . " Ribu" . $this->terbilang($number % 1000);
        elseif ($number < 1000000000)
            return $this->terbilang($number / 1000000) . " Juta" . $this->terbilang($number % 1000000);
        elseif ($number < 1000000000000)
            return $this->terbilang($number / 1000000000) . " Milyar" . $this->terbilang($number % 1000000000);
    }

    private function dbToTanggal($tanggal)
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
?>
