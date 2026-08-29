<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

class DocxGeneratorTable {
    public function generateDetailedTable($templatePath, $outputPath, $data, $rincianData) {
        try {
            $template = new TemplateProcessor($templatePath);
            
            // Replace data utama
            foreach ($data as $key => $value) {
                $template->setValue($key, $value);
            }
            
            // Clone row untuk tabel rincian
            $template->cloneRow('ROW_NUMBER', count($rincianData));
            
            // Isi data tabel
            foreach ($rincianData as $index => $item) {
                $rowNumber = $index + 1;
                
                $template->setValue('ROW_NUMBER#' . $rowNumber, $rowNumber);
                $template->setValue('KODE_DPSJ#' . $rowNumber, $item['kode_dpsj'] ?? '');
                $template->setValue('DESKRIPSI_DPSJ#' . $rowNumber, $item['deskripsi_dpsj'] ?? '');
                $template->setValue('KODE_KEGIATAN#' . $rowNumber, $item['kode_kegiatan'] ?? '');
                $template->setValue('NAMA_KEGIATAN#' . $rowNumber, $this->sanitizeText($item['nama_kegiatan']) ?? '');
                $template->setValue('KODE_AKUN#' . $rowNumber, $item['kode_akun'] ?? '');
                $template->setValue('DESKRIPSI_AKUN#' . $rowNumber, $item['deskripsi_akun'] ?? '');
                $template->setValue('KODE_DANA#' . $rowNumber, $item['kode_dana'] ?? '');
                $template->setValue('KOMITMEN#' . $rowNumber, number_format($item['komitmen'] ?? 0));
                $template->setValue('KOMITMEN_DISETUJUI#' . $rowNumber, number_format($item['komitmen_disetujui'] ?? 0));
                $template->setValue('KETERANGAN#' . $rowNumber, $item['keterangan'] ?? '');
            }
            
            // Hitung summary
            $summary = $this->calculateSummary($rincianData);
            
            $template->setValue('TOTAL_KOMITMEN', number_format($summary['total_komitmen']));
            $template->setValue('TOTAL_DISETUJUI', number_format($summary['total_disetujui']));
            $template->setValue('SELISIH', number_format($summary['selisih']));
            $template->setValue('JUMLAH_ITEM', $summary['jumlah_item']);
            $template->setValue('TERBILANG', $summary['terbilang_komitmen']);
            
            $template->saveAs($outputPath);
            
            return [
                'success' => true,
                'file_path' => $outputPath,
                'summary' => $summary
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function formatCurrency($amount) {
        return number_format($amount, 0, ',', '.');
    }
    
    private function calculateSummary($rincianData) {
        $totalKomitmen = 0;
        $totalDisetujui = 0;
        
        foreach ($rincianData as $item) {
            $totalKomitmen += $item['komitmen'] ?? 0;
            $totalDisetujui += $item['komitmen_disetujui'] ?? 0;
        }
        // tambahkan text terbilang untuk total komitmen dan total disetujui jika diperlukan
        
        return [
            'total_komitmen' => $totalKomitmen,
            'total_disetujui' => $totalDisetujui,
            'selisih' => $totalDisetujui - $totalKomitmen,
            'jumlah_item' => count($rincianData),
            'terbilang_komitmen' => trim($this->terbilang($totalKomitmen)) . ' Rupiah',
            'terbilang_disetujui' => trim($this->terbilang($totalDisetujui)) . ' Rupiah'
        ];
    }
    
    private function terbilang($number) {
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        if ($number < 12)
            return "" . $huruf[$number];
        elseif ($number < 20)
            return $this->terbilang($number - 10) . " Belas";
        elseif ($number < 100)
            return $this->terbilang($number / 10) . " Puluh " . $this->terbilang($number % 10);
        elseif ($number < 200)
            return " Seratus" . $this->terbilang($number - 100);
        elseif ($number < 1000)
            return $this->terbilang($number / 100) . " Ratus " . $this->terbilang($number % 100);
        elseif ($number < 2000)
            return " Seribu" . $this->terbilang($number - 1000);
        elseif ($number < 1000000)
            return $this->terbilang($number / 1000) . " Ribu " . $this->terbilang($number % 1000);
        elseif ($number < 1000000000)
            return $this->terbilang($number / 1000000) . " Juta " . $this->terbilang($number % 1000000);
        elseif ($number < 1000000000000)
            return $this->terbilang($number / 1000000000) . " Milyar " . $this->terbilang($number % 1000000000);
    }
    
    private function sanitizeText($text) {
        if ($text === null) return '';
        // Hapus karakter kontrol yang tidak valid di XML
        $text = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $text);
        // Escape karakter khusus XML
        return htmlspecialchars($text, ENT_XML1, 'UTF-8');
    }
}

/*
// Contoh penggunaan advanced
$rincianData = [
    [
        'id' => 38,
        'tahun_anggaran' => 2025,
        'kode_dpsj' => '09000900',
        'deskripsi_dpsj' => 'Fasilitas Umum',
        'kode_kegiatan' => 'F0100.03.02.5.001',
        'nama_kegiatan' => 'Pemeliharaan/Perbaikan Sarana Prasarana',
        'kode_akun' => '723102',
        'deskripsi_akun' => 'Beban Bahan Bakar Kendaraan Operasional',
        'kode_dana' => '51',
        'komitmen' => 500000,
        'komitmen_disetujui' => 500000,
        'keterangan' => ''
    ],
    [
        'id' => 39,
        'kode_dpsj' => '09000900',
        'deskripsi_dpsj' => 'Fasilitas Umum',
        'kode_kegiatan' => 'F0100.03.02.5.002',
        'nama_kegiatan' => 'Pemeliharaan Kendaraan Dinas',
        'kode_akun' => '723103',
        'deskripsi_akun' => 'Beban Pemeliharaan Kendaraan',
        'kode_dana' => '51',
        'komitmen' => 750000,
        'komitmen_disetujui' => 700000,
        'keterangan' => 'Disesuaikan dengan kebutuhan'
    ]
];


        $data = [
            'tanggal' => '2025-10-14 14:16:01',
            'nomor_pengajuan' => '001/ANG.10/2025-PAF',
            'preview_nomor' => '001/ANG.10/2025-PAF',
            'untuk' => 'untuk kegiatan operasional dan pemeliharaan fasilitas fakultas',
            'nip' => '196606161993031004',
            'penanggung_jawab' => 'Drs. Dadang Sudiadi, M.Si.',
            'telp' => '008123456789',
            'jabatan' => 'Manajer Operasi dan Pemeliharaan Fasilitas',
            'nama_unit' => 'Unit Operasi dan Pemeliharaan Fakultas'
        ];

$generator = new DocxGeneratorTable();
$result = $generator->generateDetailedTable(
    'template/form_D01_with_table.docx',
    'output/surat_detailed.docx',
    $data,
    $rincianData
);

if ($result['success']) {
    echo "Document berhasil dibuat!";
    echo "Summary: ";
    print_r($result['summary']);
    
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
    echo "Error: " . $result['error'];
}
*/
?>