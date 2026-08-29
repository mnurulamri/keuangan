<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load PHPExcel
require_once APPPATH . 'third_party/PHPExcel-1.8/Classes/PHPExcel.php';

class Excel {
    
    private $excel;
    
    public function __construct() {
        $this->excel = new PHPExcel();
    }
    
    /**
     * Read Excel file and return array data
     * @param string $file_path Path to Excel file
     * @return array
     */
    public function read($file_path) {
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($file_path);
            $worksheet = $objPHPExcel->getActiveSheet();
            return $worksheet->toArray();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Create Excel file from array data
     * @param array $data 2D array data
     * @param array $headers Array of headers (optional)
     * @return PHPExcel object
     */
    public function create($data, $headers = null) {
        $this->excel->setActiveSheetIndex(0);
        $sheet = $this->excel->getActiveSheet();
        
        $row = 1;
        
        // Set headers jika ada
        if ($headers) {
            $col = 0;
            foreach ($headers as $header) {
                $sheet->setCellValueByColumnAndRow($col, $row, $header);
                $sheet->getStyleByColumnAndRow($col, $row)->getFont()->setBold(true);
                $col++;
            }
            $row++;
        }
        
        // Isi data
        foreach ($data as $row_data) {
            $col = 0;
            foreach ($row_data as $cell_value) {
                $sheet->setCellValueByColumnAndRow($col, $row, $cell_value);
                $col++;
            }
            $row++;
        }
        
        // Auto size columns
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($col = 0; $col < $highestColumnIndex; $col++) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }
        
        return $this->excel;
    }
    
    /**
     * Download Excel file
     * @param PHPExcel $excel PHPExcel object
     * @param string $filename Nama file tanpa ekstensi
     * @param string $format Format: 'Excel2007' (xlsx) atau 'Excel5' (xls)
     */
    public function download($excel, $filename, $format = 'Excel5') {
        if ($format == 'Excel2007') {
            $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        } else {
            $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        }
        
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit;
    }
    
    /**
     * Import Excel file to database (example)
     * @param string $file_path
     * @param array $column_mapping Mapping kolom Excel ke database
     * @return array Result
     */
    public function import($file_path, $column_mapping = null) {
        try {
            $data = $this->read($file_path);
            
            // Hapus header (baris pertama)
            array_shift($data);
            
            $result = [
                'status' => true,
                'total' => 0,
                'errors' => []
            ];
            
            foreach ($data as $row_index => $row) {
                // Skip empty row
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Mapping data jika ada
                $mapped_data = [];
                if ($column_mapping) {
                    foreach ($column_mapping as $excel_col => $db_field) {
                        $mapped_data[$db_field] = isset($row[$excel_col]) ? $row[$excel_col] : null;
                    }
                } else {
                    $mapped_data = $row;
                }
                
                // Proses insert ke database...
                // $this->db->insert('table_name', $mapped_data);
                // if ($this->db->affected_rows() > 0) $result['total']++;
                
                $result['total']++;
            }
            
            return $result;
            
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
?>