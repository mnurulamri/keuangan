<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_PerJenisBiaya extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load library Excel
        $this->load->library('excel');
        $this->load->database();
		$this->load->helper('url');
    }

    public function index (){	
        // get id
        $id = $this->input->post('id');
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');

        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        // ambil data pejabat        
        $sql = "SELECT DISTINCT id_pengajuan_pemohon FROM pengajuan_rincian WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();
        $id_pengajuan_pemohon = isset($result[0]['id_pengajuan_pemohon']) ? $result[0]['id_pengajuan_pemohon'] : '';
                
        $sql = "SELECT penanggung_jawab, kode_bidang, nip FROM pengajuan_pemohon WHERE id = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $result = $query->result_array();
        $penanggung_jawab = isset($result[0]['penanggung_jawab']) ? $result[0]['penanggung_jawab'] : '';
        $nip = isset($result[0]['nip']) ? $result[0]['nip'] : '';

        $data['penanggung_jawab'] = $penanggung_jawab;
        $data['nip'] = $nip;
        
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT jabatan FROM pejabat WHERE end_date > date(now()) AND nip = '$nip'";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        $jabatan = isset($result[0]['jabatan']) ? $result[0]['jabatan'] : '';
        $data['jabatan'] = $jabatan;

        // ambil data pengajuan rincian
        $data['id'] = $id;
        $sql = "SELECT * FROM pengajuan_rincian WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();

        $data['sql'] = $sql;
        $data['result'] = $result;
        
        // ambil rincian realisasi
        $sql_realisasi = "SELECT * FROM realisasi WHERE id_pengajuan_rincian = ?";
        $query_realisasi = $this->db->query($sql_realisasi, array($id));
        $result_realisasi = $query_realisasi->result_array();
        $data['sql_realisasi'] = $sql_realisasi;
        $data['result_realisasi'] = $result_realisasi;

        $id_pengajuan_rincian = isset($result[0]['id']) ? $result[0]['id'] : '';
        $kode_akun = isset($result[0]['kode_akun']) ? $result[0]['kode_akun'] : '';
        $deskripsi_akun = isset($result[0]['deskripsi_akun']) ? $result[0]['deskripsi_akun'] : '';
        $kode_kegiatan = isset($result[0]['kode_kegiatan']) ? $result[0]['kode_kegiatan'] : '';
        $nama_kegiatan = isset($result[0]['nama_kegiatan']) ? $result[0]['nama_kegiatan'] : '';
        $nomor_pengajuan = isset($result[0]['nomor_pengajuan']) ? $result[0]['nomor_pengajuan'] : '';
        $kode_dana = isset($result[0]['kode_dana']) ? $result[0]['kode_dana'] : '';
        $kegiatan = isset($result[0]['kegiatan']) ? $result[0]['kegiatan'] : '';
        $jadwal = isset($result[0]['jadwal']) ? $result[0]['jadwal'] : '';
        $jenis_biaya = isset($result[0]['jenis_biaya']) ? $result[0]['jenis_biaya'] : '';
        //echo '<pre>'; print_r($data); echo '</pre>';exit();

        // Buat object PHPExcel
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()
                    ->setCreator("Keuangan FISIP UI")
                    ->setLastModifiedBy("Keuangan FISIP UI")
                    ->setTitle("Rekap Per Jenis Biaya")
                    ->setSubject("Rekap")
                    ->setDescription("Rekap Per Jenis Biaya Export");

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

        // set width kolom
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(7);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);

        // Buat header
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C2', 'KEGIATAN ')
                    ->setCellValue('C3', 'NOMOR/NAMA AKUN ')
                    ->setCellValue('C4', 'NOMOR/NAMA PROCOST ')
                    ->setCellValue('D2', ': '.$kegiatan)
                    ->setCellValue('D3', ': '.$kode_akun .'/'.$deskripsi_akun)
                    ->setCellValue('D4', ': '.$kode_kegiatan .'/'.$nama_kegiatan)
                    ->setCellValue('A6', 'REKAP BIAYA');
        
        //$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($style_col);

        // merge header dari A1 sampai J1        
        $objPHPExcel->getActiveSheet()->mergeCells('A7:A8')->getStyle('A7:A8')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->mergeCells('B7:B8')->getStyle('B7:B8')->applyFromArray($style_header);   
        $objPHPExcel->getActiveSheet()->mergeCells('C7:C8')->getStyle('C7:C8')->applyFromArray($style_header);    
        $objPHPExcel->getActiveSheet()->mergeCells('D7:F7')->getStyle('D7:F7')->applyFromArray($style_header);    
        $objPHPExcel->getActiveSheet()->mergeCells('G7:G8')->getStyle('G7:G8')->applyFromArray($style_header);    
        $objPHPExcel->getActiveSheet()->mergeCells('J7:J8')->getStyle('J8:J8')->applyFromArray($style_header);    
        $objPHPExcel->getActiveSheet()->mergeCells('H7:I7')->getStyle('H7:I7')->applyFromArray($style_header);   
        

        // set array kolom header
        $columns_header_1 = ['A7'=>'NO', 'B7'=>'TANGGAL', 'C7'=>'KETERANGAN', 'D7'=>'SATUAN', 'G7'=>'BRUTO', 'H7'=>'TARIF PAJAK', 'J7'=>'NETTO'];
        $columns_header_2 = ['D8'=>'VOL', 'E8'=>'KET VOL', 'F8'=>'HARGA (RP)', 'H8'=>'%', 'I8'=>'PPh (RP)'];
        
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        foreach($columns_header_1 as $k=>$v){
            $objPHPExcel->getActiveSheet()->getStyle($k)->applyFromArray($style_col);
        }
        foreach($columns_header_2 as $k=>$v){
            $objPHPExcel->getActiveSheet()->getStyle($k)->applyFromArray($style_col);
        }

        // set isi data header
        foreach($columns_header_1 as $k=>$v){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($k , $v );
        }

        foreach($columns_header_2 as $k=>$v){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($k , $v );
        }

        $objPHPExcel->getActiveSheet(0)->getStyle('A8')->applyFromArray($style_col);        
        $objPHPExcel->getActiveSheet(0)->getStyle('B8')->applyFromArray($style_col);     
        $objPHPExcel->getActiveSheet(0)->getStyle('E7')->applyFromArray($style_col);     
        $objPHPExcel->getActiveSheet(0)->getStyle('F7')->applyFromArray($style_col);      
        $objPHPExcel->getActiveSheet(0)->getStyle('I7')->applyFromArray($style_col);    
        $objPHPExcel->getActiveSheet(0)->getStyle('J8')->applyFromArray($style_col);

        //$objPHPExcel->setActiveSheetIndex(0)->getStyle('G8')->getAlignment()->setWrapText(true);
        //$objPHPExcel->setActiveSheetIndex(0)->getStyle('I8')->getAlignment()->setWrapText(true);

        // Isi data
        $row = 9; // Mulai dari baris ke-8 karena header sudah ada di baris 1-6
        $no = 1;
        $total_bruto = 0;
        $total_pph = 0;
        $total_netto = 0;

        foreach ($result_realisasi as $item) {  

            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('A'.$row, $no, PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('A'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('B'.$row, $this->dbToTanggal($item['tanggal']), PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('B'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.$row)->getAlignment()->setWrapText(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('C'.$row, (string)$item['keterangan'], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('C'.$row)->applyFromArray($style_row);             
	        $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('D'.$row, (int)$item['volume'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('D'.$row)->applyFromArray($style_row_middle);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('E'.$row, (string)$item['ket_volume'], PHPExcel_Cell_DataType::TYPE_STRING)->getStyle('E'.$row)->applyFromArray($style_row_middle);              
	        $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('F'.$row, (int)$item['harga'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('F'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);                
	        $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G'.$row, (int)$item['bruto'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('G'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);            
	        $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('H'.$row, (int)$item['persen_pajak'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('H'.$row)->applyFromArray($style_row_middle);              
	        $objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($style_row);

            if($item['pph'] == 0){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I'.$row, (int)$item['pph'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('I'.$row)->applyFromArray($style_row_middle); 
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I'.$row, (int)$item['pph'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('I'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle); 
            }       
	        
            $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->applyFromArray($style_row);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J'.$row, (int)$item['netto'], PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('J'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);
            $row++;
            $no++;

            // hitung total
            $total_bruto += (int)$item['bruto'];
            $total_pph += (int)$item['pph'];
            $total_netto += (int)$item['netto'];
        }
        
        // isi total
        //$row += 1;
        $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($style_row);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row, 'TOTAL '); 

        // total bruto
        $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($style_row);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('G'.$row, $total_bruto, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('G'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);
           
        // total pph
        $objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($style_row);
        if($item['pph'] == 0){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I'.$row, $total_pph, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('I'.$row)->applyFromArray($style_row_middle);
        } else {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I'.$row, $total_pph, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('I'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);
        }        
           
        // total netto
        $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->applyFromArray($style_row);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J'.$row, $total_netto, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('J'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row_middle);
        
        // isi tanggal sekarang
        $row = $row + 2;    
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, 'Depok, '.$this->tanggal_sekarang());

        $row = $row + 1;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, $jabatan); 

        $row = $row + 4;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, $penanggung_jawab); 

        $row = $row + 1;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, 'NIP/NUP '.$nip); 

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
        header('Content-Disposition: attachment;filename="RekapPerJenisBiaya' . $nomor_pengajuan . '.xls"');
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