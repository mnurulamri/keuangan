<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_word extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('autoload_helper');
        load_vendor();
    }

    public function index() {
        // Load the DocxGenerator class
        require_once 'application/controllers/DocxGenerator.php';
        
        // Define template and output paths
        $templatePath = 'template/form_D01.docx';
        $outputPath = 'output/form_D01.docx';
        
        // Create an instance of DocxGenerator
        $docxGenerator = new DocxGenerator($templatePath, $outputPath);
        
        // Sample data to replace in the template
        $data = [
            'name' => 'John Doe',
            'date' => date('Y-m-d'),
            'content' => 'This is a sample content for the document.'
        ];
        
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
    
    public function test_data(){
        		
        echo $this->input->post('id_pengajuan_pemohon'); exit();

        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];
        $data['kode_bidang'] = $kode_bidang;
        
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();

        // ambil id pengajuan dari input POST
        $id_pengajuan_pemohon = $this->input->get('id_pengajuan_pemohon'); //$this->input->post('id_pengajuan_pemohon');
        
        if (!$id_pengajuan_pemohon) {
            show_error('Nomor pengajuan tidak ditemukan.');
            return;
        }

        // ambil data tanggal dari database
        $sql = "SELECT * FROM pengajuan_pemohon WHERE id = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $result = $query->row_array();
        if (!$result) {
            show_error('Data pengajuan tidak ditemukan.');
            return;
        }
        $data['tanggal'] = $result['tanggal'];
        $data['nomor_pengajuan'] = $result['nomor_pengajuan'];
        $data['preview_nomor'] = $result['nomor_pengajuan'];
        $data['untuk'] = $result['untuk'];

        // siapkan data pejabat
        $data_pejabat[] = array(
            'nip' => $result['nip'],
            'nama' => $result['penanggung_jawab'],
            //'jabatan' => $result['jabatan'],
            'telp' => $result['telp']
        );
        
        // ambil data jabatan pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT jabatan FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$kode_bidang' ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data_pejabat[] = array(
                'jabatan' => $row['jabatan']
            );
        }

        $data['pejabat'] = $data_pejabat;
                
        // ambil nama unit
        $sql = "SELECT * FROM units WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $nama_unit = $row['nama_unit'];            
        }
        $data['nama_unit'] = $nama_unit;

        // ambil kode_ddpsj
        $sql = "SELECT * FROM unit_kerja WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_dpsj[] = $row;  
            $kode_unit = $row['kode_unit']; 
            $array_kode_dpsj[$row['kode_dpsj']] = $row['kode_dpsj'];
            $array_kode_dpsj_value[] = $row['kode_dpsj']; 
        }

        $data['array_dpsj'] = $array_dpsj;        
        $data['kode_unit'] = $kode_unit;

        //$kode_dpsj = implode("','", $array_kode_dpsj_value);
        //$kode_dpsj = "'".$kode_dpsj."'";
        $kode_dpsj = $this->input->post('kode_dpsj');
        $deskripsi_dpsj = $this->input->post('deskripsi_dpsj');
        $data['kode_dpsj'] = $kode_dpsj;
        $data['deskripsi_dpsj'] = $deskripsi_dpsj;

        // ambil data dari tabel pengajuan_rincian berdasarkan id_pengajuan_pemohon
        $sql = "SELECT * FROM pengajuan_rincian WHERE id_pengajuan_pemohon = '$id_pengajuan_pemohon'";
        $query = $this->db->query($sql);
        $rincian = $query->result_array();
        $data['rincian'] = $rincian;

        // ambil data anggaraan dari tabel rka berdasarkan kode_dpsj
        $sql = "SELECT * FROM rka WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_akun[$row['kode_akun']] = $row['anggaran'];
        }
        //echo '<pre>';print_r($data);echo '</pre>';

        // hitung sisa anggaran
        $array_sisa_anggaran = array();
        foreach ($rincian as $row) {
            $kode_dpsj = $row['kode_dpsj'];
            $kode_kegiatan = $row['kode_kegiatan'];
            $kode_akun = $row['kode_akun'];
            $kode_dana = $row['kode_dana'];

            // ambil data anggaran awal
            $sql_anggaran = "SELECT sisa_anggaran FROM rka WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_anggaran = $this->db->query($sql_anggaran, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $anggaran_awal = $query_anggaran->row_array()['sisa_anggaran'] ?? 0;

            // ambil data total komitmen
            $sql_komitmen = "SELECT SUM(komitmen) AS total_komitmen FROM pengajuan_rincian WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_komitmen = $this->db->query($sql_komitmen, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $total_komitmen = $query_komitmen->row_array()['total_komitmen'] ?? 0;

            // ambil data realisasi
            $sql_realisasi = "SELECT SUM(jumlah) AS total_realisasi FROM realisasi WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_realisasi = $this->db->query($sql_realisasi, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $total_realisasi = $query_realisasi->row_array()['total_realisasi'] ?? 0;

            // hitung sisa anggaran
            if ($total_realisasi == 0) {
                $sisa_anggaran = number_format($anggaran_awal - $total_komitmen);
            } else {
                $sisa_anggaran = number_format($anggaran_awal - $total_realisasi);
            }

            $array_sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana] = $sisa_anggaran;
        }

        $data['sisa_anggaran'] = $array_sisa_anggaran;

        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;

        //$this->load->view('unit_kerja/pengajuan_form_edit',$data);
        print_r($data);
    }

    /**
     * Generate a Word document using PHPWord
     */
    public function generate()
    {
        //require_once 'H:/www/keuangan/vendor/autoload.php';
        // Load the autoloader
        if (!class_exists('PhpOffice\PhpWord\PhpWord')) {
            echo "PHPWord class not found. Please check your installation.";
            return;
        }
        
        // Create new PHPWord object
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Add a section
        $section = $phpWord->addSection();
        
        // Add table for "Bagian Untuk Diisi Anggaran"
        $table = $section->addTable(array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80));
        $table->addRow();
        $cell = $table->addCell(8000, array('gridSpan' => 6, 'valign' => 'center'));
        $cell->addText('***Bagian Untuk Diisi Anggaran***', array('bold' => true), array('align' => 'left'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('***Tanggal***', array('bold' => true), array('align' => 'left'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('*** ***', array('bold' => true), array('align' => 'left'));
        
        // Add separator row
        $table->addRow();
        $cell = $table->addCell(12000, array('gridSpan' => 8, 'valign' => 'center'));
        $cell->addText('', array(), array('align' => 'left'));
        
        // Add No. row
        $table->addRow();
        $cell = $table->addCell(12000, array('gridSpan' => 8, 'valign' => 'center'));
        $cell->addText('No. :', array(), array('align' => 'left'));
        
        // Add empty row
        $table->addRow();
        $cell = $table->addCell(12000, array('gridSpan' => 8, 'valign' => 'center'));
        $cell->addText('', array(), array('align' => 'left'));
        
        // Add table for "Bagian Untuk Diisi Pemohon"
        $table = $section->addTable(array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80));
        $table->addRow();
        $cell = $table->addCell(8000, array('gridSpan' => 6, 'valign' => 'center'));
        $cell->addText('***Bagian Untuk Diisi Pemohon***', array('bold' => true), array('align' => 'left'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('***Tanggal***', array('bold' => true), array('align' => 'left'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('*** ***', array('bold' => true), array('align' => 'left'));
        
        // Add Data Pemohon section
        $section->addTextBreak(1);
        $section->addText('Data Pemohon', array('bold' => true));
        
        // Add table for Data Pemohon
        $table = $section->addTable(array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80));
        
        // PAF/Dept/Prog/Unit row
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center'));
        $cell->addText('PAF/Dept/Prog/Unit :', array(), array('align' => 'left'));
        $cell = $table->addCell(9000, array('valign' => 'center'));
        $cell->addText('(dibuat master data unit kerja)', array('italic' => true), array('align' => 'left'));
        
        // Penanggung Jawab row
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center'));
        $cell->addText('Penanggung Jawab/Contact Person :', array(), array('align' => 'left'));
        $cell = $table->addCell(9000, array('valign' => 'center'));
        $cell->addText('(dimasukan di dalam masterdata unit kerja)', array('italic' => true), array('align' => 'left'));
        
        // NPM/NIP/NUP row
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center'));
        $cell->addText('NPM/NIP/NUP:', array(), array('align' => 'left'));
        $cell = $table->addCell(9000, array('valign' => 'center'));
        $cell->addText('(dimasukan di dalam masterdata unit kerja)', array('italic' => true), array('align' => 'left'));
        
        // No. Telepon and Email row
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center'));
        $cell->addText('No. Telepon:', array(), array('align' => 'left'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('', array(), array('align' => 'left'));
        $cell = $table->addCell(1000, array('valign' => 'center'));
        $cell->addText('E-mail:', array(), array('align' => 'left'));
        $cell = $table->addCell(6000, array('valign' => 'center'));
        $cell->addText('', array(), array('align' => 'left'));
        
        // Add Rincian Laporan Pembayaran section
        $section->addTextBreak(1);
        $section->addText('Rincian Laporan Pembayaran', array('bold' => true));
        
        // Untuk dan Atas Nama row
        $table = $section->addTable(array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80));
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center'));
        $cell->addText('Untuk dan Atas Nama :', array(), array('align' => 'left'));
        $cell = $table->addCell(9000, array('valign' => 'center'));
        $cell->addText('diisi dengan nama kegiatan dan DPSJ dalam anggaran (contoh: kebutuhan mingguan rutin S1 Reg Ilmu Komunikasi)', array('italic' => true), array('align' => 'left'));
        
        // Add table header for payment details
        $table = $section->addTable(array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80));
        $table->addRow();
        $cell = $table->addCell(800, array('valign' => 'center'));
        $cell->addText('No', array('bold' => true), array('align' => 'center'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('Nomor dan Nama Project Costing', array('bold' => true), array('align' => 'center'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('Nomor dan Nama akun', array('bold' => true), array('align' => 'center'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('Jumlah (Rp)', array('bold' => true), array('align' => 'center'));
        $cell = $table->addCell(5200, array('valign' => 'center'));
        $cell->addText('Keterangan', array('bold' => true), array('align' => 'center'));
        
        // Add empty rows for payment details
        for ($i = 1; $i <= 8; $i++) {
            $table->addRow();
            if ($i == 1) {
                $cell = $table->addCell(800, array('valign' => 'center'));
                $cell->addText($i, array(), array('align' => 'center'));
                $cell = $table->addCell(2000, array('valign' => 'center'));
                $cell->addText('Diisi dengan nomor lengkap', array('italic' => true), array('align' => 'left'));
                $cell = $table->addCell(2000, array('valign' => 'center'));
                $cell->addText('(di master data Anggaran)', array('italic' => true), array('align' => 'left'));
                $cell = $table->addCell(2000, array('valign' => 'center'));
                $cell->addText('Diisi dengan nama akun yang terdapat dalam kegiatan', array('italic' => true), array('align' => 'left'));
                $cell = $table->addCell(5200, array('valign' => 'center'));
                $cell->addText('Uraian penjelasan jika dibutuhkan', array('italic' => true), array('align' => 'left'));
            } else {
                $cell = $table->addCell(800, array('valign' => 'center'));
                $cell->addText('', array(), array('align' => 'center'));
                $cell = $table->addCell(2000, array('valign' => 'center'));
                $cell->addText('', array(), array('align' => 'left'));
                $cell = $table->addCell(2000, array('valign' => 'center'));
                $cell->addText('', array(), array('align' => 'left'));
                $cell = $table->addCell(2000, array('valign' => 'center'));
                $cell->addText('', array(), array('align' => 'left'));
                $cell = $table->addCell(5200, array('valign' => 'center'));
                $cell->addText('', array(), array('align' => 'left'));
            }
        }
        
        // Add Total row
        $table->addRow();
        $cell = $table->addCell(6800, array('gridSpan' => 4, 'valign' => 'center'));
        $cell->addText('Total', array('bold' => true), array('align' => 'right'));
        $cell = $table->addCell(2000, array('valign' => 'center'));
        $cell->addText('(otomatis)', array('italic' => true), array('align' => 'left'));
        $cell = $table->addCell(5200, array('valign' => 'center'));
        $cell->addText('', array(), array('align' => 'left'));
        
        // Add Terbilang row
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center'));
        $cell->addText('Terbilang', array('bold' => true), array('align' => 'left'));
        $cell = $table->addCell(9000, array('gridSpan' => 5, 'valign' => 'center'));
        $cell->addText('(otomatis)', array('italic' => true), array('align' => 'left'));
        
        // Add approval section
        $section->addTextBreak(2);
        $table = $section->addTable(array('borderSize' => 0, 'cellMargin' => 80));
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Diperiksa Oleh', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Mengetahui', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('', array(), array('align' => 'center'));
        
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Pemohon', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Anggaran', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Keuangan', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Manajer Anggaran dan Keuangan', array(), array('align' => 'center'));
        
        // Add empty rows for signatures
        for ($i = 0; $i < 3; $i++) {
            $table->addRow();
            $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
            $cell->addText('', array(), array('align' => 'center'));
            $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
            $cell->addText('', array(), array('align' => 'center'));
            $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
            $cell->addText('', array(), array('align' => 'center'));
            $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
            $cell->addText('', array(), array('align' => 'center'));
        }
        
        // Add names
        $table->addRow();
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Aep Pujiansah', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Astuti Utaminingtyas', array(), array('align' => 'center'));
        $cell = $table->addCell(3000, array('valign' => 'center', 'borderSize' => 0));
        $cell->addText('Anggiarini,S.E.', array(), array('align' => 'center'));
        
        // Add approval section
        $section->addTextBreak(2);
        $section->addText('Menyetujui', array(), array('align' => 'center'));
        $section->addTextBreak(1);
        $section->addText('WD SUMDAVUM', array(), array('align' => 'center'));
        $section->addTextBreak(1);
        $section->addText('Dwi Ardhanariswari Sundrijo, Ph.D', array(), array('align' => 'center'));
        $section->addTextBreak(1);
        $section->addText('Catatan :', array(), array('align' => 'left'));
        
        // Save file
        $filename = 'form_D01.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

    public function cekPath()
    {
        echo FCPATH . '<br>';
        echo 'Vendor exists: ' . (file_exists(FCPATH . 'vendor\autoload.php') ? 'YES' : 'NO');
        echo '<br>';
    }
}