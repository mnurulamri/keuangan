<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_RealisasiUmko extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load library Excel
        $this->load->library('excel');
        $this->load->database();
		$this->load->helper('url');
    }

    public function index (){	
        // get id
        $id =$this->input->post('id');

        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        // ambil data dari tabel pengajuan_pemohon
        $sql = "SELECT nama_unit, penanggung_jawab, kode_bidang, nip FROM pengajuan_pemohon WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->row_array();
        $nama_unit = $result['nama_unit'];
        $penanggung_jawab = $result['penanggung_jawab'];
        $kode_bidang = $result['kode_bidang'];
        $nip = $result['nip'];
        
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT jabatan FROM pejabat WHERE end_date > date(now()) AND nip = '$nip'";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        $jabatan = isset($result[0]['jabatan']) ? $result[0]['jabatan'] : '';

        // ambil data dari tabel monitoring
        $sql = "SELECT nomor_pengajuan, uraian
                FROM monitoring 
                WHERE id_pengajuan_pemohon = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->row();
        $nomor_pengajuan = $result->nomor_pengajuan;
        $kegiatan = $result->uraian;

        // ambil data dari tabel view_pengajuan_rincian_realisasi
        $sql = "SELECT kode_akun, deskripsi_akun, keterangan, komitmen, aktual_report as realisasi, (sisa_komitmen + pph) as sisa_umko, netto 
                FROM view_pengajuan_rincian_realisasi 
                WHERE id_pengajuan_pemohon = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();

        //echo '<pre>'; print_r($result); exit();
        // Buat object PHPExcel
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()
                    ->setCreator("Keuangan FISIP UI")
                    ->setLastModifiedBy("Keuangan FISIP UI")
                    ->setTitle("Rekap Total Biaya")
                    ->setSubject("Rekap")
                    ->setDescription("Rekap Per Total Biaya Export");

        // set logo
        $objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
        $objDrawing->setName('Logo FIISP UI');        //set name to image
        $objDrawing->setDescription('Logo FISIP UI'); //set description to image
        //$signature = $reportdetails[$rowCount][$value];    //Path to signature .jpg file
        $objDrawing->setPath(FCPATH . 'images/logo_fisip.jpg'); 
        $objDrawing->setOffsetX(7);                       //setOffsetX works properly
        $objDrawing->setOffsetY(10);                       //setOffsetY works properly
        $objDrawing->setCoordinates('B1');        //set image to cell
        $objDrawing->setWidth(60);                 //set width, height
        $objDrawing->setHeight(60);  

        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save
        
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

        $style_total = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // set width kolom
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

        // Buat header
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C2', '                         DEP/PRODI/UNIT KERJA ')
                    ->setCellValue('C3', '                         KEGIATAN ')
                    ->setCellValue('C4', '                         NOMOR PENGAJUAN ')
                    ->setCellValue('D2', ': '.strtoupper($nama_unit))
                    ->setCellValue('D3', ': '.strtoupper($kegiatan))
                    ->setCellValue('D4', ': '.$nomor_pengajuan)
                    ->setCellValue('A6', 'REKAP REALISASI UMKO');
        
        //$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($style_col);

        // merge header dari A1 sampai J1        
        $objPHPExcel->getActiveSheet()->mergeCells('B7:C7')->getStyle('B7:C7')->applyFromArray($style_header);

        // set array kolom header
        $columns_header_1 = ['A7'=>'NO', 'B7'=>'NOMOR DAN NAMA AKUN', 'D7'=>'KETERANGAN', 'E7'=>'JML UMKO (RP)', 'F7'=>'REALISASI (RP)', 'G7'=>'SISA UMKO (RP)'];
        
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        foreach($columns_header_1 as $k=>$v){
            $objPHPExcel->getActiveSheet()->getStyle($k)->applyFromArray($style_col);
        }

        // set isi data header
        foreach($columns_header_1 as $k=>$v){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($k , $v );
        }

        $objPHPExcel->getActiveSheet(0)->getStyle('C7')->applyFromArray($style_col);  

        //$objPHPExcel->setActiveSheetIndex(0)->getStyle('G8')->getAlignment()->setWrapText(true);
        //$objPHPExcel->setActiveSheetIndex(0)->getStyle('I8')->getAlignment()->setWrapText(true);

        // Isi data
        $row = 8; // Mulai dari baris ke-8 karena header sudah ada di baris 1-6
        $no = 1;
        $total_komitmen = 0;
        $total_realisasi = 0;
        $total_sisa_umko = 0;
        $total_netto = 0;

        foreach ($result as $item) {  

            // set nilai $sisa_umko
            $sisa_umko = (int)$item['komitmen'] - (int)$item['realisasi'];

            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('A'.$row, $no, PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('A'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('B'.$row, $item['kode_akun'], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('B'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.$row)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('C'.$row, (string)$item['deskripsi_akun'], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('C'.$row)->applyFromArray($style_row);             
	        $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.$row)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('D'.$row, (string)$item['keterangan'], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('D'.$row)->applyFromArray($style_row);           
	        $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('E'.$row, (int)$item['komitmen'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('E'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);               
	        $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($style_row);

            if($item['realisasi'] == 0){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F'.$row, (int)$item['netto'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('F'.$row)->applyFromArray($style_row_middle); 
                $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F'.$row, (int)$item['netto'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('F'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle); 
            }       
	                   
	        $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G'.$row, (int)$sisa_umko, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('G'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle); 

            $row++;
            $no++;

            // hitung total
            $total_komitmen += (int)$item['komitmen'];
            $total_realisasi += (int)$item['realisasi'];
            $total_sisa_umko += (int)$sisa_umko;
            $total_netto += (int)$item['netto'];
        }
        
        // isi total
        //$row += 1;
        $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, 'TOTAL ');

        // total bruto
        $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->applyFromArray($style_total);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('E'.$row, $total_komitmen, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('E'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_total);
           
        // total pph
        $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($style_total);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F'.$row, $total_netto, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('F'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_total);
           
        // total netto
        $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($style_total);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G'.$row, $total_sisa_umko, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('G'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_total);
        
        // isi tanggal sekarang
        $row = $row + 2;    
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, 'Depok, '.$this->tanggal_sekarang());

        $row = $row + 1;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $jabatan); 

        $row = $row + 4;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $penanggung_jawab); 

        $row = $row + 1;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, 'NIP/NUP '.$nip); 

        // Set orientasi kertas dan ukuran
        $objPHPExcel->getActiveSheet()->getPageSetup()
                    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        
        // Set judul sheet
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Per Jenis Biaya');
        
        // Set active sheet index
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Output ke browser
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="RekapRealisasiUmko_' . $nomor_pengajuan . '.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
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