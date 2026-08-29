<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_word_with_table extends CI_Controller 
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
        $templatePath = 'template/form_D01_with_table.docx';
        $outputPath = 'output/form_D01_with_table.docx';
        
        // Create an instance of DocxGenerator
        //$docxGenerator = new DocxGeneratorTable($templatePath, $outputPath, $data, $rincianData);       

        // Contoh penggunaan advanced
        /*
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
        */

        // ambil data dari method data()
        $array_data = $this->data();
        $data = array(
            'tanggal' => $this->dbToTanggal($array_data['tanggal']),
            'nomor_pengajuan' => $array_data['nomor_pengajuan'],
            'untuk' => $array_data['untuk'],
            'nip' => $array_data['nip'],
            'penanggung_jawab' => $array_data['penanggung_jawab'],
            'telp' => $array_data['telp'],
            'jabatan' => $array_data['jabatan'],
            'nama_unit' => $array_data['nama_unit'],
            'form' => $array_data['form']
        );

        $rincianData = $array_data['rincian'];
        
        $generator = new DocxGeneratorTable();
        
        // jika nama form adalah D02, gunakan template form_D02_with_table.docx, jika tidak gunakan form_D01_with_table.docx
        if($array_data['form'] == 'D02'){
            $templatePath = 'template/form_D02_with_table.docx';
            $outputPath = 'output/form_D02_with_table.docx';
            $result = $generator->generateDetailedTable(
                'template/form_D02_with_table.docx',
                'output/form_D02_with_table.docx',
                $data,
                $rincianData
            );            
        } else {
            $templatePath = 'template/form_D01_with_table.docx';
            $outputPath = 'output/form_D01_with_table.docx';
            $result = $generator->generateDetailedTable(
                'template/form_D01_with_table.docx',
                'output/form_D01_with_table.docx',
                $data,
                $rincianData
            );     
        }
//echo '<pre>';print_r($data);print_r($rincianData);echo '</pre>'; exit(); 

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
        $data['tanggal'] = $result['tanggal'];
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
        //$this->load->view('unit_kerja/pengajuan_form_edit',$data);
        
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
}