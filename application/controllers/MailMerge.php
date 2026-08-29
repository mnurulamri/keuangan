<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Section;

class MailMerge extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('download');
        $this->load->library('session');
    }
    
    public function index() {
        $this->load->view('kuitansi_form');
    }
    
    public function generate_kuitansi() {
        try {
            // Data kuitansi (bisa dari form atau database)
            $data_kuitansi = $this->get_kuitansi_data();
            
            // Generate document
            $filename = $this->create_kuitansi_document($data_kuitansi);
            
            // Download file
            $this->download_file($filename);
            
        } catch (Exception $e) {
            log_message('error', 'Kuitansi Generation Error: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Gagal membuat kuitansi: ' . $e->getMessage());
            redirect('kuitansi');
        }
    }
    
    private function get_kuitansi_data() {
        // Contoh data - bisa diganti dengan data dari database
        return [
            [
                'telah_terima_dari' => 'PT. ABC Indonesia',
                'jumlah_uang' => 2500000,
                'terbilang' => 'Dua Juta Lima Ratus Ribu Rupiah',
                'untuk_pembayaran' => 'Pembayaran invoice No. INV/2024/001',
                'diterima_oleh' => 'Budi Santoso',
                'diserahkan_oleh' => 'Ahmad Wijaya',
                'tanggal' => date('d F Y'),
                'nomor_kuitansi' => 'KUIT/2024/001'
            ],
            [
                'telah_terima_dari' => 'CV. Maju Jaya',
                'jumlah_uang' => 3750000,
                'terbilang' => 'Tiga Juta Tujuh Ratus Lima Puluh Ribu Rupiah',
                'untuk_pembayaran' => 'Pembayaran sewa kantor bulan April 2024',
                'diterima_oleh' => 'Siti Rahayu',
                'diserahkan_oleh' => 'Dewi Anggraini',
                'tanggal' => date('d F Y'),
                'nomor_kuitansi' => 'KUIT/2024/002'
            ],
            [
                'telah_terima_dari' => 'UD. Sejahtera Bersama',
                'jumlah_uang' => 1500000,
                'terbilang' => 'Satu Juta Lima Ratus Ribu Rupiah',
                'untuk_pembayaran' => 'Pembayaran pengiriman barang',
                'diterima_oleh' => 'Rudi Hermawan',
                'diserahkan_oleh' => 'Maya Sari',
                'tanggal' => date('d F Y'),
                'nomor_kuitansi' => 'KUIT/2024/003'
            ]
        ];
    }
    
    private function create_kuitansi_document($data) {
        $phpWord = new PhpWord();
        
        // Set document properties
        $phpWord->getDocInfo()->setCreator('System');
        $phpWord->getDocInfo()->setCompany('Perusahaan');
        $phpWord->getDocInfo()->setTitle('Kuitansi Pembayaran');
        
        // Set page margins
        $section = $phpWord->addSection([
            'marginTop' => Converter::cmToTwip(1),
            'marginBottom' => Converter::cmToTwip(1),
            'marginLeft' => Converter::cmToTwip(2.5),
            'marginRight' => Converter::cmToTwip(1),
        ]);
        
        foreach ($data as $index => $kuitansi) {
            if ($index > 0) {
                // Add page break untuk record selanjutnya
                $section->addPageBreak();
            }
            
            // Add first copy (atas)
            $this->add_kuitansi_copy($section, $kuitansi, 'ASLI');
            
            // Add separator line
            $section->addTextBreak(1);
            $this->add_separator_line($section);
            $section->addTextBreak(1);
            
            // Add second copy (bawah) - duplicate
            $this->add_kuitansi_copy($section, $kuitansi, 'COPY');
        }
        
        // Save file
        $filename = 'kuitansi_' . time() . '.docx';
        $filepath = FCPATH . '/output/' . $filename;
        
        if (!is_dir(FCPATH . '/output/')) {
            mkdir(FCPATH . '/output/', 0777, true);
        }
        
        $phpWord->save($filepath, 'Word2007');
        
        return $filepath;
    }
    
    private function add_kuitansi_copy($section, $data, $copy_type) {
        // Header dengan nomor kuitansi dan copy type
        $headerTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 50
        ]);
        
        $headerTable->addRow();
        $cell1 = $headerTable->addCell(2000);
        $cell2 = $headerTable->addCell(7000, ['align' => 'right']);
        
        $cell1->addText('No: ' . $data['nomor_kuitansi'], ['bold' => true], ['align' => 'left']);
        $cell2->addText($copy_type, ['bold' => true, 'color' => 'FF0000'], ['align' => 'right']);
        
        $section->addTextBreak(1);
        
        // Judul KUITANSI
        $section->addText(
            'KUITANSI', 
            ['bold' => true, 'size' => 16], 
            ['align' => 'center', 'spaceAfter' => 200]
        );
        
        // Table untuk content kuitansi
        $table = $section->addTable([
            'borderSize' => 1,
            'borderColor' => '000000',
            'cellMargin' => 50
        ]);
        
        // Row 1: Telah Terima Dari
        $table->addRow();
        $table->addCell(2000)->addText('Telah terima dari', null, ['align' => 'left']);
        $table->addCell(7000)->addText(': ' . $data['telah_terima_dari'], null, ['align' => 'left']);
        
        // Row 2: Jumlah Uang
        $table->addRow();
        $table->addCell(2000)->addText('Jumlah Uang', null, ['align' => 'left']);
        $table->addCell(7000)->addText(': Rp ' . number_format($data['jumlah_uang'], 0, ',', '.'), ['bold' => true], ['align' => 'left']);
        
        // Row 3: Terbilang
        $table->addRow();
        $table->addCell(2000)->addText('Terbilang', null, ['align' => 'left']);
        $table->addCell(7000)->addText(': ' . $data['terbilang'], ['italic' => true], ['align' => 'left']);
        
        // Row 4: Untuk Pembayaran
        $table->addRow();
        $table->addCell(2000)->addText('Untuk Pembayaran', null, ['align' => 'left']);
        $table->addCell(7000)->addText(': ' . $data['untuk_pembayaran'], null, ['align' => 'left']);
        
        $section->addTextBreak(2);
        
        // Table untuk tanda tangan
        $signTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'align' => 'right'
        ]);
        
        $signTable->addRow();
        $leftCell = $signTable->addCell(4000);
        $rightCell = $signTable->addCell(04000);
        
        //$leftCell->addText('', null, ['align' => 'left']);
        //$leftCell->addTextBreak(1);
        $leftCell->addText('', null, ['align' => 'left']);

        //$rightCell->addText('', null, ['align' => 'right']);
        //$rightCell->addTextBreak(2);
        $rightCell->addText('Jakarta, ' . $data['tanggal'], null, ['align' => 'left']);

        $signTable->addRow();
        $leftCell = $signTable->addCell(4000);
        $rightCell = $signTable->addCell(4000);

        // Bagian kiri: Diterima Oleh
        $leftCell->addText('Diterima Oleh:', null, ['align' => 'left']);
        $leftCell->addTextBreak(2);
        $leftCell->addText($data['diterima_oleh'], ['bold' => true, 'underline' => 'single'], ['align' => 'left']);
        
        // Bagian kanan: Tanggal dan Diserahkan Oleh
        //$rightCell->addText('Jakarta, ' . $data['tanggal'], null, ['align' => 'right']);
        $rightCell->addText('Diserahkan Oleh:', null, ['align' => 'left']);
        $rightCell->addTextBreak(2);
        $rightCell->addText($data['diserahkan_oleh'], ['bold' => true, 'underline' => 'single'], ['align' => 'left']);
    }
    
    private function add_separator_line($section) {
        $lineTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF'
        ]);
        
        $lineTable->addRow();
        $cell = $lineTable->addCell(8000);
        
        $cell->addText(
            '---------------------------------------------------------------------------------------------------',
            ['color' => 'CCCCCC'],
            ['align' => 'center']
        );
        
        $cell->addText(
            'GARIS PEMISAH - POTONG DI SINI',
            ['size' => 8, 'color' => 'CCCCCC', 'italic' => true],
            ['align' => 'center']
        );
        
        $cell->addText(
            '---------------------------------------------------------------------------------------------------',
            ['color' => 'CCCCCC'],
            ['align' => 'center']
        );
    }
    
    private function download_file($filepath) {
        if (file_exists($filepath)) {
            $filename = basename($filepath);
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            header('Cache-Control: max-age=0');
            readfile($filepath);
            
            // Hapus file setelah didownload
            unlink($filepath);
            exit;
        } else {
            throw new Exception('File tidak ditemukan');
        }
    }
}