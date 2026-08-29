<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

class Pengajuan_cetak_D02 extends CI_Controller 
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
        load_vendor();
    }

    public function index() {
        // Load the DocxGenerator class
        require_once 'application/controllers/DocxGeneratorTable.php';
        
        // Define template and output paths
        $templatePath = 'template/form_D02_with_table.docx';
        $outputPath = 'output/form_D02_with_table.docx';

        // ambil data dari method data()
        $array_data = $this->data();
        $data = array(
            'tanggal' => $this->dbToTanggal($array_data['tanggal']),
            'nomor_pengajuan' => (string) $array_data['nomor_pengajuan'],
            'untuk' => (string) $array_data['untuk'],
            'nip' => (string) $array_data['nip'],
            'penanggung_jawab' => (string) $array_data['penanggung_jawab'],
            'telp' => (string) $array_data['telp'],
            'jabatan' => (string) $array_data['jabatan'],
            'nama_unit' => (string) $array_data['nama_unit'],
            'form' => (string) $array_data['form']
        );

        $rincianData = $array_data['rincian'];
//echo '<pre>';print_r($data);print_r($rincianData);echo '</pre>'; exit();         
        $generator = new DocxGeneratorTable();
        
        
        $result = $generator->generateDetailedTable(
                'template/form_D02_with_table.docx',
                'output/form_D02_with_table.docx',
                $data,
                $rincianData
            );



        if ($result['success']) {
            echo "Document berhasil dibuat!";
            echo "Summary: ";
            //print_r($result['summary']);

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
    }

    public function data(){
        
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];
        $data['kode_bidang'] = $kode_bidang;
        
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();

        // ambil id pengajuan dari input POST
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        
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
        $data['tanggal'] = $result['tanggal']; //substr($result['tgl_diajukan'], 0, 10);
        $data['nomor_pengajuan'] = $result['nomor_pengajuan'];
        $data['preview_nomor'] = $result['nomor_pengajuan'];
        $data['untuk'] = $result['untuk'];
        $data['nip'] = $result['nip'];
        $data['penanggung_jawab'] = $result['penanggung_jawab'];
        $data['telp'] = $result['telp'];
        $data['form'] = $result['form'];
        
        // ambil data jabatan pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT jabatan FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$kode_bidang' ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data['jabatan'] = $row['jabatan'];
        }
                
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

        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;

        return $data;
        
    }
    
	function dbToTanggal($tanggal)
	{
		if ($tanggal=='0000-00-00' or $tanggal=='' or $tanggal==null) {
			$tanggal = '';
		} else {
			$array = explode('-', $tanggal);
			//set tanggal
	        $d = $array[2];
	        $m = $array[1];
	        $y = $array[0];
			//set hari
			$nama_hari = array( 0 => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu' );
			$kd_hari = date("w", mktime(0, 0, 0, $m, $d, $y));
			$hari = $nama_hari[$kd_hari];
			//set bulan
			$nama_bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
			$bulan = $nama_bulan[$m];
	        $tanggal_hari = $hari.', '.$d.' '.$bulan.' '.$y;
	        $tanggal = $d.' '.$bulan.' '.$y;
		}

        return $tanggal;
	}	

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
                $template->setValue('NAMA_KEGIATAN#' . $rowNumber, $item['nama_kegiatan'] ?? '');
                $template->setValue('KODE_AKUN#' . $rowNumber, $item['kode_akun'] ?? '');
                $template->setValue('DESKRIPSI_AKUN#' . $rowNumber, $item['deskripsi_akun'] ?? '');
                $template->setValue('KODE_DANA#' . $rowNumber, $item['kode_dana'] ?? '');
                $template->setValue('KOMITMEN#' . $rowNumber, $this->formatCurrency($item['komitmen'] ?? 0));
                $template->setValue('KOMITMEN_DISETUJUI#' . $rowNumber, $this->formatCurrency($item['komitmen_disetujui'] ?? 0));
                $template->setValue('KETERANGAN#' . $rowNumber, $item['keterangan'] ?? '');
            }
            
            // Hitung summary
            $summary = $this->calculateSummary($rincianData);
            
            $template->setValue('TOTAL_KOMITMEN', $this->formatCurrency($summary['total_komitmen']));
            $template->setValue('TOTAL_DISETUJUI', $this->formatCurrency($summary['total_disetujui']));
            $template->setValue('SELISIH', $this->formatCurrency($summary['selisih']));
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
}