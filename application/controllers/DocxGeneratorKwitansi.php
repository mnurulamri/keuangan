<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

class DocxGeneratorKwitansi {
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

// contohPenggunaan(); // Uncomment untuk test
?>