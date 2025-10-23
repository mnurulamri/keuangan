<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

class DocxGenerator {
    private $templatePath;
    private $outputPath;
    
    public function __construct($templatePath, $outputPath) {
        $this->templatePath = $templatePath;
        $this->outputPath = $outputPath;
    }
    
    public function generateDocument($data) {
        try {
            // Load template
            $templateProcessor = new TemplateProcessor($this->templatePath);
            
            // Replace placeholder dengan data
            foreach ($data as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }
            
            // Simpan file hasil
            $templateProcessor->saveAs($this->outputPath);
            
            return [
                'success' => true,
                'message' => 'File berhasil dibuat: ' . $this->outputPath,
                'file_path' => $this->outputPath
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    // Method untuk replace gambar dalam template
    public function replaceImage($placeholder, $imagePath) {
        try {
            $templateProcessor = new TemplateProcessor($this->templatePath);
            $templateProcessor->setImageValue($placeholder, $imagePath);
            $templateProcessor->saveAs($this->outputPath);
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Contoh penggunaan
function contohPenggunaan() {
    // Path template dan output
    $templatePath = 'template/template_surat.docx';
    $outputPath = 'output/surat_hasil.docx';
    
    // Data yang akan diisi
    $data = [
        'NAMA_PENGGUNA' => 'John Doe',
        'NOMOR_SURAT' => '001/ST/2024',
        'TANGGAL' => date('d F Y'),
        'PERIHAL' => 'Permohonan Izin',
        'ISI_SURAT' => 'Dengan hormat, kami mengajukan permohonan izin untuk melakukan kegiatan sebagaimana tersebut di bawah ini.',
        'JABATAN' => 'Manager',
        'NAMA_PEJABAT' => 'Budi Santoso',
        'NIP' => '198012312345678901'
    ];
    
    // Generate document
    $generator = new DocxGenerator($templatePath, $outputPath);
    $result = $generator->generateDocument($data);
    
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

// contohPenggunaan(); // Uncomment untuk test
?>