<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Section;

class Kuitansi_docx extends CI_Controller {
    
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
    
    private function get_kuitansi_data() 
    {
        $this->load->database();

        // set id_monitoring dari POST 
        $id = $this->input->post('id_monitoring');

        // set sql query untuk mendapatkan data berdasarkan id_monitoring
        $sql = "SELECT nomor_pengajuan, nominal_umko_cair, uraian FROM monitoring WHERE id IN ($id)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        
        foreach ($result as $row) {
            $data_kuitansi[] = [
                'telah_terima_dari' => 'PUM CASH CARD',
                'jumlah_uang' => $row['nominal_umko_cair'],
                'terbilang' => $this->terbilang($row['nominal_umko_cair']), // Anda bisa menambahkan fungsi untuk mengubah angka ke terbilang
                'untuk_pembayaran' => $row['uraian'].' ' . $row['nomor_pengajuan'],
                'diterima_oleh' => '__________________',
                'diserahkan_oleh' => $this->session->userdata['logged_anggaran']['username'],
                'tanggal' => date('d F Y'),
                'nomor_kuitansi' => 'KUIT/' . date('Y') . '-' . $row['nomor_pengajuan']
            ];
        }

        return $data_kuitansi;
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
        $cell1 = $headerTable->addCell(5000);
        $cell2 = $headerTable->addCell(4000, ['align' => 'right']);
        
        $cell1->addText('No: ' . $data['nomor_kuitansi'], ['bold' => false], ['align' => 'left']);
        $cell2->addText($copy_type, ['bold' => true, 'color' => 'FF0000'], ['align' => 'right']);
        
        //$section->addTextBreak(1);
        
        // Judul KUITANSI
        // tambahkan logo fisip ui
        /*$section->addImage('images/logo_fisip.jpg', ['width' => 100, 'height' => 100, 'align' => 'center']);
        $section->addText(
            'TANDA TERIMA PENGELUARAN UMKO', 
            ['bold' => true, 'size' => 15], 
            ['align' => 'center', 'spaceAfter' => 200]
        );*/

        // table untuk judul kuitansi
        $judulTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 50
        ]);

        $judulTable->addRow();
        // tambahkan image logo fisip ui di kolom kiri
        $cell = $judulTable->addCell(2000);
        $cell->addImage('images/logo_fisip.jpg', ['width' => 100, 'height' => 40, 'align' => 'left']);
        // tambahkan judul di kolom kanan
        $cell = $judulTable->addCell(8000,['valign' => 'center']);
        $cell->addText(
            'TANDA TERIMA PENGELUARAN UMKO',
            ['bold' => true, 'size' => 12],
            ['align' => 'center']
        );
        // tambahkan baris kedua untuk subjudul
        /*$judulTable->addRow();
        $cell = $judulTable->addCell(2000);
        $cell = $judulTable->addCell(8000);
        $cell->addText(
            'FAKULTAS ILMU SOSIAL DAN ILMU POLITIK UNIVERSITAS INDONESIA',
            ['bold' => true, 'size' => 12],
            ['align' => 'center']
        );*/


        /*$cell = $judulTable->addCell(8000);
        // tambahkan image logo fisip ui di samping kiri
        $cell->addImage('images/logo_fisip.jpg', ['width' => 100, 'height' => 100, 'align' => 'left']);

        $cell->addText(
            'KUITANSI UNTUK PENGELUARAN UANG MUKA KAS OPERASIONAL (UMKO) FAKULTAS ILMU SOSIAL DAN ILMU POLITIK UNIVERSITAS INDONESIA',
            ['bold' => true, 'size' => 12],
            ['align' => 'center']
        );*/

        $judulTable->addRow();
        
        // Table untuk content kuitansi
        $table = $section->addTable([
            'borderSize' => 1,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 50
        ]);
        
        // Row 1: Telah Terima Dari
        $table->addRow();
        $table->addCell(2000)->addText('Telah terima dari', null, ['align' => 'left']);
        $table->addCell(7000)->addText(': ' . $data['telah_terima_dari'], null, ['align' => 'left']);
        
        // Row 2: Jumlah Uang
        $table->addRow();
        $table->addCell(2000)->addText('Jumlah Uang', null, ['align' => 'left']);
        $table->addCell(7000)->addText(': Rp. ' . number_format($data['jumlah_uang'], 0, ',', '.'), ['bold' => true], ['align' => 'left']);
        
        // Row 3: Terbilang
        $table->addRow();
        $table->addCell(2000)->addText('Terbilang', null, ['align' => 'left']);
        $table->addCell(7000)->addText(': ' . $data['terbilang'], ['italic' => true], ['align' => 'left']);
        
        // Row 4: Untuk Pembayaran
        $table->addRow();
        $table->addCell(2000)->addText('Untuk Pembayaran', null, ['align' => 'left']);
        $table->addCell(7000)->addText(': ' . $data['untuk_pembayaran'], null, ['align' => 'left']);
        
        $section->addTextBreak(1);
        
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

        /*
        // tambahkan footer
        $section->addTextBreak(3);
        $section->addText('UNIVERSITAS INDONESIA', ['size' => 8], ['align' => 'center']);
        $section->addText('FAKULTAS ILMU SOSIAL DAN ILMU POLITIK', ['size' => 8], ['align' => 'center']);
        $section->addText('KAMPUS UI DEPOK 16424', ['size' => 8], ['align' => 'center']);
        $section->addText('Telp. (021) 7270006 Fax. (021) 7872820', ['size' => 8], ['align' => 'center']);
        */

    }
    
    private function add_separator_line($section) {
        $lineTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF'
        ]);
        
        $lineTable->addRow();
        $cell = $lineTable->addCell(8000);
        
        $cell->addText(
            '---------------------------------------- GARIS PEMISAH - POTONG DI SINI ----------------------------------------',
            ['size' => 8, 'color' => 'CCCCCC', 'italic' => true],
            ['align' => 'center']
        );
        
        /*$cell->addText(
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
        );*/
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

    function terbilang($number)
    {
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        if ($number < 12)
            return " " . $huruf[$number];
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
}
