<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//require_once APPPATH . 'third_party/PHPExcel-1.8/Classes/PHPExcel.php';

class Export_excel extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('export_model');
        //$this->load->helper('download');
        $this->load->library('excel');
		$this->load->helper('url');
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
        
        // Create Excel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        
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
        
        // Freeze the header row
        $sheet->freezePane('A2');
        
        // Set auto filter
        $sheet->setAutoFilter('A1:U1');
        
        // Create Excel file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        
        // Set filename
        $filename = 'test'; 'export_data_' . date('Ymd_His') . '.xlsx';
        
        // Set headers for download
        /*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');*/
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_data_' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Save to output
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit();
    }
}
?>