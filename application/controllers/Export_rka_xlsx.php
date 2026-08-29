<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_rka_xlsx extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('export_rka_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'session'));
        $this->load->helper('menu_helper');
        $this->load->library('excel');
        
        // Cek login jika diperlukan
        // if(!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
    }
    
    /**
     * Index page with filter form
     */
    public function index() {
        $data['title'] = 'Export Data RKA';
        $data['tahun_list'] = $this->export_rka_model->get_tahun();
        // Get initial data without search
        $data['kode_dpsj_list'] = $this->export_rka_model->get_kode_dpsj('', 1, 50);
        
        $data['title'] = 'Invoice PP';
        $data['nama'] = $this->session->userdata['logged_anggaran']['username'];
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('laporan/export-rka', $data);
        $this->load->view('template/footer');
    }
    
    /**
     * AJAX endpoint for searching kode_dpsj
     * Format response harus sesuai dengan yang diharapkan Select2
     */
    public function search_kode_dpsj() {
        // Ambil parameter dari Select2
        $search = $this->input->get('q');
        $page = $this->input->get('page') ?: 1;
        $limit = 20;
        
        // Log untuk debugging
        log_message('debug', 'Search kode_dpsj: ' . $search);
        
        // Get data dari model
        $results = $this->export_rka_model->get_kode_dpsj($search, $page, $limit);
        $total = $this->export_rka_model->get_total_kode_dpsj($search);
        
        // Format untuk Select2 - PASTIKAN FORMAT INI
        $response = array(
            'results' => array(),
            'pagination' => array(
                'more' => ($page * $limit) < $total
            )
        );
        
        // Jika ada hasil, format dengan benar
        if (!empty($results)) {
            foreach ($results as $row) {
                // Pastikan id dan text ada
                $response['results'][] = array(
                    'id' => (string) $row['kode_dpsj'],  // Pastikan string
                    'text' => $row['kode_dpsj'] . ' - ' . $row['deskripsi_dpsj'],
                    'kode' => $row['kode_dpsj'],
                    'deskripsi' => $row['deskripsi_dpsj']
                );
            }
        } else {
            // Jika tidak ada hasil, kirim array kosong
            $response['results'] = array();
        }
        
        // Set header JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    /**
     * Get kode_dpsj detail by ID - untuk menampilkan pilihan yang sudah dipilih
     */
    public function get_kode_dpsj_detail() {
        $kode = $this->input->get('id');
        
        if (empty($kode)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('error' => 'Kode DPSJ tidak ditemukan')));
            return;
        }
        
        $this->db->select('kode_dpsj, deskripsi_dpsj');
        $this->db->distinct();
        $this->db->where('kode_dpsj', $kode);
        $query = $this->db->get('rka');
        $result = $query->row_array();
        
        if ($result) {
            $response = array(
                'id' => $result['kode_dpsj'],
                'text' => $result['kode_dpsj'] . ' - ' . $result['deskripsi_dpsj']
            );
        } else {
            $response = array('error' => 'Kode DPSJ tidak ditemukan');
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    /**
     * Get total data for AJAX
     */
    public function get_total_data() {
        $tahun = $this->input->post('tahun');
        $kode_dpsj = $this->input->post('kode_dpsj');
        
        $total = $this->export_rka_model->get_total_records($tahun, $kode_dpsj);
        
        echo json_encode(['total' => $total]);
    }

    /**
     * Export to Xlsx
     */
    public function export_xlsx() {

        $tanggal_cetak = $this->input->post('tanggal_cetak');
        $tahun = $this->input->post('tahun');
        $kode_dpsj_start = $this->input->post('kode_dpsj_start');
        $kode_dpsj_end = $this->input->post('kode_dpsj_end');
        //$data = $this->export_rka_model->get_export_data($tahun, $kode_dpsj_start, $kode_dpsj_end);
        //echo '<pre>';print_r($data);echo '</pre>';exit;
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
        // Set headers with styling
        $headers = [
            'A1' => 'DPSJ',
            'B1' => 'NAMA DPSJ',
            'C1' => 'KODE PROCOST',
            'D1' => 'PROCOST',
            'E1' => 'AKUN',
            'F1' => 'DESC AKUN',
            'G1' => 'KODE DANA',
            'H1' => 'TYPE PROCOST',
            'I1' => 'ANGGARAN',
            'J1' => 'MUTASI',
            'K1' => 'ANGGARAN SETELAH MUTASI',
            'L1' => 'KOMITMEN',
            'M1' => 'AKTUAL',
            'N1' => 'SISA ANGGARAN'
        ];
        
        // Apply headers
        foreach ($headers as $cell => $value) {
            $objPHPExcel->getActiveSheet()->setCellValue($cell, $value);
        }
        
        // Style header
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
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Center text vertically
            ),
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => 'BBBBBB') // Color must go inside the border side
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => 'BBBBBB')
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => 'BBBBBB')
                ),
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => 'BBBBBB') // Color must go inside the border side
                )
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row_middle = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle),
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'BBBBBB')), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'BBBBBB')),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'BBBBBB')), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'BBBBBB'))
            )
        );

        $horizontal = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
        ));

        // Buat style untuk baris genap
        $style_row_even = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F2F2F2') // Warna abu-abu muda
            )
        );

        // Buat style untuk baris ganjil
        $style_row_odd = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFFFF') // Warna putih
            )
        );

        // buat style untuk baris total
        $style_row_total = array(
            'font' => array('bold' => true, 'size' => 10),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'BBBBBB')),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'BBBBBB')),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'BBBBBB')),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'BBBBBB'))
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'DDDDDD')
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($style_header);
        $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($style_col);
        
        // Ambil data dari database (contoh)
        $data = $this->export_rka_model->get_export_data($tahun, $kode_dpsj_start, $kode_dpsj_end);
        
        // freeze header row pada baris pertama
        $objPHPExcel->getActiveSheet()->freezePane('A2');

        // Isi data
        $row = 2;
        $no = 1;
        
        // set variabel untuk menampung total kolom anggaran, sisa_setelah_mutasi, mutasi, komitmen, aktual, sisa_anggaran
        $total_anggaran = 0;
        $total_sisa_setelah_mutasi = 0;
        $total_mutasi = 0;
        $total_komitmen = 0;
        $total_aktual = 0;
        $total_sisa_anggaran = 0;

        foreach ($data as $item) {
            // pisah $item['nama_kegiatan'] berdasarkan karakter ":"
            $nama_kegiatan_parts = explode(':', $item['nama_kegiatan']);
            $anggaran = (int)$item['anggaran'];
            $anggaran_setelah_mutasi = (int)$item['anggaran'] + (int)$item['mutasi'];
            $mutasi = (int)$item['mutasi'];
            $komitmen = (int)$item['komitmen'];
            $aktual = (int)$item['aktual'];
            $sisa_anggaran = (int)$item['sisa_anggaran'];

            $sheet_index = $objPHPExcel->setActiveSheetIndex(0);
            $sheet_index->setCellValue('A' . $row, $item['kode_dpsj'])->getStyle('A'.$row)->applyFromArray($style_row_middle);
            $sheet_index->setCellValue('B' . $row, $item['deskripsi_dpsj'])->getStyle('B'.$row)->applyFromArray($style_row);
            $sheet_index->setCellValue('C' . $row, $item['kode_kegiatan'])->getStyle('C'.$row)->applyFromArray($style_row_middle);
            $sheet_index->setCellValue('D' . $row, $nama_kegiatan_parts[1])->getStyle('D'.$row)->applyFromArray($style_row); // Ambil bagian sebelum ":"
            $sheet_index->setCellValue('E' . $row, $item['kode_akun'])->getStyle('E'.$row)->applyFromArray($style_row_middle);
            $sheet_index->setCellValue('F' . $row, $item['deskripsi_akun'])->getStyle('F'.$row)->applyFromArray($style_row);
            $sheet_index->setCellValue('G' . $row, $item['kode_dana'])->getStyle('G'.$row)->applyFromArray($style_row_middle);
            $sheet_index->setCellValue('H' . $row, $item['kategori_kegiatan'])->getStyle('H'.$row)->applyFromArray($style_row_middle);

            $sheet_index = $objPHPExcel->setActiveSheetIndex(0);
            $sheet_index->setCellValueExplicit('I' . $row, $anggaran, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('I'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row);
            $sheet_index->setCellValueExplicit('J' . $row, $mutasi, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('J'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row);
            $sheet_index->setCellValueExplicit('K' . $row, $anggaran_setelah_mutasi, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('K'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row);
            $sheet_index->setCellValueExplicit('L' . $row, $komitmen, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('L'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row);
            $sheet_index->setCellValueExplicit('M' . $row, $aktual, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('M'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row);
            $sheet_index->setCellValueExplicit('N' . $row, $sisa_anggaran, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('N'.$row)->getNumberFormat()->setFormatCode("#,##")->applyFromArray($style_row);
            
            $objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$row)->applyFromArray($style_row);
            $objPHPExcel->getActiveSheet()->getStyle('N'.$row)->applyFromArray($style_row);
            
            // set warna background untuk baris data ganjil dan genap
            if ($row % 2 == 0) {
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':N'.$row)->applyFromArray($style_row_even);
            } else {
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':N'.$row)->applyFromArray($style_row_odd);
            }

            // Tambahkan ke total
            $total_anggaran += $anggaran;
            $total_mutasi += $mutasi;
            $total_sisa_setelah_mutasi += $anggaran_setelah_mutasi;
            $total_komitmen += $komitmen;
            $total_aktual += $aktual;
            $total_sisa_anggaran += $sisa_anggaran;

            $row++;
        }
        

        // ubah ukuran font menjadi 10
        $objPHPExcel->getActiveSheet()->getStyle('A1:N' . ($row - 1))->getFont()->setSize(10);
        
        // Tambahkan baris total di bawah data
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, 'TOTAL')->getStyle('H'.$row)->applyFromArray($style_row_total);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $row, $total_anggaran, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('I'.$row)->getNumberFormat()->setFormatCode("#,##");
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $row, $total_mutasi, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('J'.$row)->getNumberFormat()->setFormatCode("#,##");
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('K' . $row, $total_sisa_setelah_mutasi, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('K'.$row)->getNumberFormat()->setFormatCode("#,##");
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('L' . $row, $total_komitmen, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('L'.$row)->getNumberFormat()->setFormatCode("#,##");
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('M' . $row, $total_aktual, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('M'.$row)->getNumberFormat()->setFormatCode("#,##");
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $row, $total_sisa_anggaran, PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle('N'.$row)->getNumberFormat()->setFormatCode("#,##");

        $objPHPExcel->getActiveSheet()->getStyle('H'.$row.':N'.$row)->applyFromArray($style_row_total);

        // Set lebar kolom
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        
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
    
    /**
     * Export to Excel
     */
    public function export_excel() {
        // Load PhpSpreadsheet
        require_once(APPPATH . 'third_party/vendor/autoload.php');
        
        $tahun = $this->input->post('tahun');
        $kode_dpsj = $this->input->post('kode_dpsj');
        
        // Get data
        $data = $this->export_rka_model->get_export_data($tahun, $kode_dpsj);
        
        if (empty($data)) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk diexport dengan filter yang dipilih.');
            redirect('export_rka_xlsx');
        }
        
        // Create Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Your Company')
            ->setTitle('Export Data RKA')
            ->setDescription('Export Data RKA');
        
        // Set headers with styling
        $headers = [
            'A1' => 'Tahun Anggaran',
            'B1' => 'Kode DPSJ',
            'C1' => 'Deskripsi DPSJ',
            'D1' => 'Kode Kegiatan',
            'E1' => 'Nama Kegiatan',
            'F1' => 'Kode Dana',
            'G1' => 'Deskripsi Dana',
            'H1' => 'Kategori Kegiatan',
            'I1' => 'Kode Akun',
            'J1' => 'Deskripsi Akun',
            'K1' => 'Anggaran',
            'L1' => 'Komitmen',
            'M1' => 'Aktual',
            'N1' => 'Mutasi',
            'O1' => 'Sisa Anggaran',
            'P1' => 'Flag Payroll',
            'Q1' => 'Flag Count',
            'R1' => 'Flag Disetujui',
            'S1' => 'PPH',
            'T1' => 'Netto'
        ];
        
        // Apply headers
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:T1')->applyFromArray($headerStyle);
        
        // Set data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['tahun_anggaran']);
            $sheet->setCellValue('B' . $row, $item['kode_dpsj']);
            $sheet->setCellValue('C' . $row, $item['deskripsi_dpsj']);
            $sheet->setCellValue('D' . $row, $item['kode_kegiatan']);
            $sheet->setCellValue('E' . $row, $item['nama_kegiatan']);
            $sheet->setCellValue('F' . $row, $item['kode_dana']);
            $sheet->setCellValue('G' . $row, $item['deskripsi_dana']);
            $sheet->setCellValue('H' . $row, $item['kategori_kegiatan']);
            $sheet->setCellValue('I' . $row, $item['kode_akun']);
            $sheet->setCellValue('J' . $row, $item['deskripsi_akun']);
            $sheet->setCellValue('K' . $row, $item['anggaran']);
            $sheet->setCellValue('L' . $row, $item['komitmen']);
            $sheet->setCellValue('M' . $row, $item['aktual']);
            $sheet->setCellValue('N' . $row, $item['mutasi']);
            $sheet->setCellValue('O' . $row, $item['sisa_anggaran']);
            $sheet->setCellValue('P' . $row, $item['flag_payroll']);
            $sheet->setCellValue('Q' . $row, $item['flag_count']);
            $sheet->setCellValue('R' . $row, $item['flag_disetujui']);
            $sheet->setCellValue('S' . $row, $item['pph']);
            $sheet->setCellValue('T' . $row, $item['netto']);
            $row++;
        }
        
        // Format number columns
        $numberColumns = ['K', 'L', 'M', 'N', 'O', 'S', 'T'];
        foreach ($numberColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . ($row - 1))
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
        }
        
        // Auto size columns
        foreach (range('A', 'T') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A1:T' . ($row - 1))->applyFromArray($styleArray);
        
        // Set row height
        $sheet->getRowDimension(1)->setRowHeight(25);
        
        // Add filter info
        $sheet->setCellValue('V1', 'Filter Info:');
        $sheet->setCellValue('V2', 'Tahun');
        $sheet->setCellValue('W2', $tahun ?: 'Semua');
        $sheet->setCellValue('V3', 'Kode DPSJ');
        $sheet->setCellValue('W3', $kode_dpsj ?: 'Semua');
        $sheet->setCellValue('V4', 'Total Records');
        $sheet->setCellValue('W4', count($data));
        $sheet->setCellValue('V5', 'Export Date');
        $sheet->setCellValue('W5', date('d/m/Y H:i:s'));
        
        // Style filter info
        $sheet->getStyle('V1:W5')->getFont()->setBold(true);
        $sheet->getColumnDimension('V')->setWidth(20);
        $sheet->getColumnDimension('W')->setWidth(30);
        
        // Create filename
        $filename = 'export_rka_' . date('Ymd_His') . '.xlsx';
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
?>