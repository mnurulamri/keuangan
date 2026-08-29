<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_excel extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load library Excel
        $this->load->library('excel');  //third_party/PHPExcel-1.8/Classes/PHPExcel.php
    }
    
    public function export_excel() {
        // Buat object PHPExcel
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()
                    ->setCreator("Nama Anda")
                    ->setLastModifiedBy("Nama Anda")
                    ->setTitle("Laporan Data")
                    ->setSubject("Laporan")
                    ->setDescription("Laporan Data Export");
        
        // Buat header
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Nama')
                    ->setCellValue('C1', 'Email')
                    ->setCellValue('D1', 'Telepon');
        
        /*// Ambil data dari database (contoh)
        $this->load->model('M_data');
        $data = $this->M_data->get_all_data();
        
        // Isi data
        $row = 2;
        $no = 1;
        foreach ($data as $item) {
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $no++)
                        ->setCellValue('B' . $row, $item->nama)
                        ->setCellValue('C' . $row, $item->email)
                        ->setCellValue('D' . $row, $item->telepon);
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
        header('Content-Disposition: attachment;filename="laporan_data_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    public function export_excel_with_style() 
    {
        //require_once APPPATH . 'third_party/PHPExcel-1.8/Classes/PHPExcel.php';
        
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()
                    ->setCreator("Nama Anda")
                    ->setLastModifiedBy("Nama Anda")
                    ->setTitle("Laporan Data")
                    ->setSubject("Laporan")
                    ->setDescription("Laporan Data Export");
        
        // Buat header dengan style
        $style_header = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '4CAF50'),
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        
        // Set header
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Nama')
                    ->setCellValue('C1', 'Email')
                    ->setCellValue('D1', 'Telepon');
        
        // Apply style to header
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($style_header);
        
        /*// Ambil data
        $this->load->model('M_data');
        $data = $this->M_data->get_all_data();
        
        // Isi data
        $row = 2;
        $no = 1;
        foreach ($data as $item) {
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $no++)
                        ->setCellValue('B' . $row, $item->nama)
                        ->setCellValue('C' . $row, $item->email)
                        ->setCellValue('D' . $row, $item->telepon);
            
            // Border untuk setiap baris data
            $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':D' . $row)
                        ->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $row++;
        }*/
        
        // Auto size
        foreach (range('A', 'D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
        }
        
        // Output
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_data_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    public function export_xlsx() {
        // Buat object PHPExcel
        $objPHPExcel = new PHPExcel();
        
        // Set properties
        $objPHPExcel->getProperties()
                    ->setCreator("Nama Anda")
                    ->setLastModifiedBy("Nama Anda")
                    ->setTitle("Laporan Data")
                    ->setSubject("Laporan")
                    ->setDescription("Laporan Data Export");
        
        // Buat header
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Nama')
                    ->setCellValue('C1', 'Email')
                    ->setCellValue('D1', 'Telepon');
        
        /*// Ambil data dari database (contoh)
        $this->load->model('M_data');
        $data = $this->M_data->get_all_data();
        
        // Isi data
        $row = 2;
        $no = 1;
        foreach ($data as $item) {
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $no++)
                        ->setCellValue('B' . $row, $item->nama)
                        ->setCellValue('C' . $row, $item->email)
                        ->setCellValue('D' . $row, $item->telepon);
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

        // 1. DEKLARASIKAN VARIABEL $filename
        $filename = 'laporan_data_' . date('YmdHis');

        // Output ke browser
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0'); // 2. TAMBAHKAN CACHE CONTROL
        
        $objWriter->save('php://output');
        exit;
    }
}