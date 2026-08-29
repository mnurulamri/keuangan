<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_kwitansi extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');        
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        $this->load->helper('url');
        $this->load->helper('autoload_helper');
        $this->load->helper('tanggal_helper');
        //$this->load->helper('kwitansi_helper');
        $this->load->library('session');
        load_vendor();
    }

    public function index() {
        
        // Load the DocxGenerator class
        require_once 'application/controllers/DocxGeneratorKwitansi.php';
        
        // Define template and output paths
        $templatePath = 'template/kwitansi.docx';
        $outputPath = 'output/kwitansi.docx';
        
        // Create an instance of DocxGenerator
        $docxGenerator = new DocxGeneratorKwitansi($templatePath, $outputPath);
        
        $data = [
            'TELAH_TERIMA_DARI' => 'PUM CASH CARD',
            'NAMA_KASIR' => $this->session->userdata['logged_anggaran']['username'],
            'JUMLAH' => 'Jumlah',
            'TERBILANG' => 'Test Terbilang',
            'PERIHAL' => 'Permohonan Izin',
            'TANGGAL' => tanggal_sekarang(),
            'NAMA_PUM' => 'John Doe'
        ];
        //echo '<pre>';print_r($array_data);echo '</pre>'; exit(); 
        // Generate the document
        $result = $docxGenerator->generateDocument($data);
        
        // Output the result
        //echo json_encode($result);

        if ($result['success']) {
            echo "Success: " . $result['message'];
            
            // Untuk download file
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($outputPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($outputPath));
            readfile($outputPath);
            exit;
            
        } else {
            echo "Error: " . $result['message'];
        }
    }

    public function pengeluaran()
    {
        $data = [
            'TELAH_TERIMA_DARI' => $this->input->post('telah_terima_dari'),
            'YANG_MENYERAHKAN' => $this->input->post('yang_menyerahkan'),
            'JUMLAH' => $this->input->post('nominal_umko_cair'),
            'TERBILANG' => $this->terbilang(str_replace(',', '', $this->input->post('nominal_umko_cair'))),
            'PERIHAL' => $this->input->post('untuk_pembayaran'),
            'TANGGAL' => tanggal_sekarang(),
            'YANG_MENERIMA' => $this->input->post('kasir_penerima')
        ];
        
        // Load the DocxGenerator class
        require_once 'application/controllers/DocxGeneratorKwitansi.php';
        
        // Define template and output paths
        $templatePath = 'template/kwitansi_pengeluaran.docx';
        $outputPath = 'output/kwitansi_pengeluaran.docx';
        
        // Create an instance of DocxGenerator
        $docxGenerator = new DocxGeneratorKwitansi($templatePath, $outputPath);
        $result = $docxGenerator->generateDocument($data);

        // Output the result
        if ($result['success']) {
            echo "Success: " . $result['message'];
            
            // Untuk download file
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($outputPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($outputPath));
            readfile($outputPath);
            exit;
            
        } else {
            echo "Error: " . $result['message'];
        }
    }

    function terbilang($number)
    {
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        if ($number < 12)
            return "" . $huruf[$number];
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