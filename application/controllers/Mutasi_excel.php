<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_excel extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load library Excel
        $this->load->library('excel');
        $this->load->database();
    }
    
    public function index() {
        $kode_grup = $this->input->post('kode_grup');
        # get data untuk header laporan
        $sql = "SELECT * FROM view_mutasi_pengajuan WHERE kode_grup = ?";
        $query = $this->db->query($sql, array($kode_grup));
        $header = $query->row_array();

        // jika tidak ada nomor pengajuan, set $nomor_pengajuan menjadi '-'
        if(empty($header['nomor_pengajuan'])) {
            $header['nomor_pengajuan'] = '-';
        }

        // jika tidak ada tanggal pengajuan, set $tanggal_pengajuan menjadi '-'
        if(empty($header['tgl_pengajuan']) || $header['tgl_pengajuan'] == '0000-00-00') {
            $header['tgl_pengajuan'] = '-';
        }

        # get data rincian mutasi
        $sql_rincian = "SELECT * FROM view_mutasi_rincian WHERE kode_grup = ?";
        $query_rincian = $this->db->query($sql_rincian, array($kode_grup));
        $result_rincian = $query_rincian->result_array();
        $total_mutasi = 0;
        foreach($result_rincian as $row) {
            // hitung jika $row['mutasi'] bernilai positif
            if((int)$row['mutasi'] > 0) {
                $total_mutasi += $row['mutasi'];
            }
            $nomor_pengajuan = $row['nomor_pengajuan'];
        }

        // array bulan untuk konversi angka bulan ke nama bulan
        $bulan_array = array(
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'Mei',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Agus',
            '09' => 'Sept',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Des'
        );
        //echo '<pre>';print_r($header);print_r($result_rincian);print_r($total_mutasi);echo '</pre>'; exit();
        // Buat object PHPExcel
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()
                    ->setCreator("Keuangan FISIP UI")
                    ->setLastModifiedBy("Keuangan FISIP UI")
                    ->setTitle("Laporan Data Mutasi")
                    ->setSubject("Laporan")
                    ->setDescription("Laporan Data Mutasi Export");
        
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        
        $style_header = array(
        'font' => array('bold' => true), // Set font nya jadi bold
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ));

        $style_col = array(
        'font' => array('bold' => true), // Set font nya jadi bold
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ),
        'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
        ),

                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DDDDDD')
                )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
        'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ),
        'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
        )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row_middle = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle),
            ),
            'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        $horizontal = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
        ));

        // set width kolom
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);

        // Buat header
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'MUTASI ANGGARAN')
                    ->setCellValue('A2', 'NOMOR: '.$nomor_pengajuan)
                    ->setCellValue('A3', 'Tahun                      : '.$header['tahun'])
                    ->setCellValue('A4', 'Entitas Anggaran   : FISIP')
                    ->setCellValue('A5', 'Tanggal Pengajuan: '.$header['tgl_pengajuan'])
                    ->setCellValue('A6', 'Total Mutasi          : '.number_format($total_mutasi));
        
        // merge header dari A1 sampai J1        
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1')->getStyle('A1:J1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->mergeCells('A2:J2')->getStyle('A2:J2')->applyFromArray($style_header);   
        

        // set array kolom header
        $columns_header = ['A8'=>'No', 'B8'=>'Entitas Anggaran', 'C8'=>'Kegiatan', 'D8'=>'Dana', 'E8'=>'Natural Akun', 'F8'=>'Bulan', 'G8'=>'Anggaran Sebelum', 'H8'=>'Perubahan', 'I8'=>'Anggaran Setelah', 'J8'=>'Kebutuhan'];
        
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        foreach($columns_header as $k=>$v){
            $objPHPExcel->getActiveSheet()->getStyle($k)->applyFromArray($style_col);
        }

        // set isi data header
        foreach($columns_header as $k=>$v){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($k , $v );
        }
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('G8')->getAlignment()->setWrapText(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('I8')->getAlignment()->setWrapText(true);
        // Isi data
        $row = 9; // Mulai dari baris ke-8 karena header sudah ada di baris 1-6
        $no = 1;
        foreach ($result_rincian as $item) {   
            $sisa_anggaran = (int)$item['anggaran'] + (int)$item['mutasi'];
                     
            $kegiatan = $item['kode_kegiatan'] . ': ' . $item['nama_kegiatan'];
            $natural_akun = $item['kode_akun'] . ': ' . $item['deskripsi_akun'];
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('A'.$row, $no, PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('A'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('B'.$row, (string)$item['deskripsi_dpsj'], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('B'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.$row)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('C'.$row, (string)$kegiatan, PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('C'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('D'.$row, (string)$item['kode_dana'], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('D'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('E'.$row, (string)$natural_akun, PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('E'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F'.$row, $bulan_array[$item['bulan']], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('F'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G'.$row, (int)$item['anggaran'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('G'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);                
	        $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('H'.$row, (int)$item['mutasi'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('H'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);            
	        $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I'.$row, (int)$sisa_anggaran, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('I'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);            
	        $objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J'.$row, (string)$item['keterangan'], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('J'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J'.$row)->getAlignment()->setWrapText(true);
            $no++;
            $row++;
        }

        /*foreach ($result_rincian as $item) {
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $no++)
                        ->setCellValue('B' . $row, (string)$item['kode_kegiatan'])
                        ->setCellValue('C' . $row, (string)$item['nama_kegiatan'])
                        ->setCellValue('D' . $row, (string)$item['kode_akun'])
                        ->setCellValue('E' . $row, (string)$item['deskripsi_akun'])
                        ->setCellValue('F' . $row, (string)$item['bulan'])
                        ->setCellValue('G' . $row, (int)$item['anggaran'])
                        ->setCellValue('H' . $row, (int)$item['mutasi'])
                        ->setCellValue('I' . $row, (int)$item['sisa_anggaran'])
                        ->setCellValue('J' . $row, (string)$item['keterangan']);
            $row++;
        }*/
        
        // Set orientasi kertas dan ukuran
        $objPHPExcel->getActiveSheet()->getPageSetup()
                    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        
        // Set judul sheet
        $objPHPExcel->getActiveSheet()->setTitle('Laporan Data');
        
        // Set active sheet index
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Output ke browser
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_data_' . $nomor_pengajuan . '.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}