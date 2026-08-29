<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_rka extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load library Excel
        $this->load->library('excel');
        $this->load->database();
		$this->load->helper('url');
        $this->load->model('export_model');
        $this->load->library('session');
    }
    
    public function index() {
        $data['tahun'] = $this->export_model->get_tahun_anggaran();
        $this->load->view('export_view', $data);
    }

    public function export_to_excel() {
        // Get filters from POST
        $filters = array(
            'tahun_anggaran' => $this->input->post('tahun_anggaran'),
            'kode_dana' => $this->input->post('kode_dana'),
            'kategori_kegiatan' => $this->input->post('kategori_kegiatan')
        );
        
        // Remove empty filters
        $filters = array_filter($filters);
        
        // Get data
        $data = $this->export_model->get_export_data($filters);
        
        if (empty($data)) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk diexport');
            redirect('export_excel');
        }
        // Buat object PHPExcel
        $objPHPExcel = new PHPExcel();        
        $sheet = $objPHPExcel->getActiveSheet();
        
        // Set properties
        $objPHPExcel->getProperties()
                    ->setCreator("Keuangan FISIP UI")
                    ->setLastModifiedBy("Keuangan FISIP UI")
                    ->setTitle("Rekap Total Biaya")
                    ->setSubject("Rekap")
                    ->setDescription("Rekap Per Total Biaya Export");

        // set logo
        /*$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
        $objDrawing->setName('Logo FIISP UI');        //set name to image
        $objDrawing->setDescription('Logo FISIP UI'); //set description to image
        //$signature = $reportdetails[$rowCount][$value];    //Path to signature .jpg file
        $objDrawing->setPath(FCPATH . 'images/logo_fisip.jpg'); 
        $objDrawing->setOffsetX(7);                       //setOffsetX works properly
        $objDrawing->setOffsetY(10);                       //setOffsetY works properly
        $objDrawing->setCoordinates('B1');        //set image to cell
        $objDrawing->setWidth(60);                 //set width, height
        $objDrawing->setHeight(60);  

        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save*/
        
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

        
        // Set column headers
        $headers = array(
            'A' => 'ID',
            'B' => 'Tahun Anggaran',
            'C' => 'Kode DPSJ',
            'D' => 'Deskripsi DPSJ',
            'E' => 'Kode Kegiatan',
            'F' => 'Nama Kegiatan Pendek',
            'G' => 'Nama Kegiatan',
            'H' => 'Kode Dana',
            'I' => 'Deskripsi Dana',
            'J' => 'Kategori Kegiatan',
            'K' => 'Kode Akun',
            'L' => 'Deskripsi Akun',
            'M' => 'RUP',
            'N' => 'Anggaran',
            'O' => 'Komitmen',
            'P' => 'Aktual',
            'Q' => 'Mutasi',
            'R' => 'Sisa Anggaran',
            'S' => 'Tgl Update Realisasi',
            'T' => 'ID Kegiatan',
            'U' => 'Flag Payroll'
        );
        
        // Apply headers
        foreach ($headers as $column => $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getStyle($column . '1')->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E0E0E0');
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Fill data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['tahun_anggaran']);
            $sheet->setCellValue('C' . $row, $item['kode_dpsj']);
            $sheet->setCellValue('D' . $row, $item['deskripsi_dpsj']);
            $sheet->setCellValue('E' . $row, $item['kode_kegiatan']);
            $sheet->setCellValue('F' . $row, $item['nama_kegiatan_pendek']);
            $sheet->setCellValue('G' . $row, $item['nama_kegiatan']);
            $sheet->setCellValue('H' . $row, $item['kode_dana']);
            $sheet->setCellValue('I' . $row, $item['deskripsi_dana']);
            $sheet->setCellValue('J' . $row, $item['kategori_kegiatan']);
            $sheet->setCellValue('K' . $row, $item['kode_akun']);
            $sheet->setCellValue('L' . $row, $item['deskripsi_akun']);
            $sheet->setCellValue('M' . $row, $item['rup']);
            $sheet->setCellValue('N' . $row, $item['anggaran']);
            $sheet->setCellValue('O' . $row, $item['komitmen']);
            $sheet->setCellValue('P' . $row, $item['aktual']);
            $sheet->setCellValue('Q' . $row, $item['mutasi']);
            $sheet->setCellValue('R' . $row, $item['sisa_anggaran']);
            $sheet->setCellValue('S' . $row, $item['tgl_update_realisasi']);
            $sheet->setCellValue('T' . $row, $item['id_kegiatan']);
            $sheet->setCellValue('U' . $row, $item['flag_payroll']);
            
            // Format currency columns
            $sheet->getStyle('N' . $row . ':R' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
            
            $row++;
        }

        // Set column widths for text columns
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('L')->setWidth(30);

        $filename = date('Y-m-d_H-i-s');
        // Output ke browser
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0'); // 2. TAMBAHKAN CACHE CONTROL
        $objWriter->save('php://output');
    }
}