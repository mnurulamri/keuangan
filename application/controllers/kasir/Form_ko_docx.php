<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_ko_docx extends CI_Controller 
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
        load_vendor();
    }

    public function index() {
        //print_r($this->data()); exit();
        // Load the DocxGenerator class
        require_once 'application/controllers/kasir/Form_ko_docx_generator.php';
        
        // Define template and output paths
        $templatePath = 'template/form_ko.docx';
        $outputPath = 'output/form_ko.docx';

        // ambil data dari method data()
        $array_data = $this->data();
        $data = array(
            'periode' => $array_data['periode'],
            'saldo_akhir' => $array_data['saldo_akhir'],
            'total_pengajuan' => $array_data['total_pengajuan'],            
            'total_penarikan' => $array_data['total_penarikan'],
            'diajukan_oleh' => $array_data['diajukan_oleh'],
            'diperiksa_oleh' => $array_data['diperiksa_oleh'],
            'disetujui_oleh' => $array_data['disetujui_oleh']
        );

        $rincianData = $array_data['rincian'];
        $array = $array_data['array'];
        $array_tgl = $array_data['array_tgl'];
        
       //echo '<pre>';print_r($data);print_r($array_tgl);echo '</pre>'; exit(); 
        $generator = new Form_ko_docx_generator();
        $result = $generator->generateDetailedTable(
            'template/form_ko.docx',
            'output/form_ko.docx',
            $data,
            $rincianData,
            $array,
            $array_tgl
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
    }

    public function data(){
        
        // Ambil data dari AJAX
        $id_monitoring = $this->input->post('id_monitoring'); // Menggunakan 'id_monitoring' sesuai perubahan di view
        $periode = $this->input->post('periode');
        $saldo_akhir = $this->input->post('saldo_akhir');
        $diajukan_oleh = $this->input->post('diajukan_oleh');
        $diperiksa_oleh = $this->input->post('diperiksa_oleh');
        $disetujui_oleh = $this->input->post('disetujui_oleh');

        // Lakukan proses penyimpanan atau tindakan lain dengan data yang diterima
        // ambil data kuitansi dari database berdasarkan ids_kuitansi
        $sql = "SELECT * FROM monitoring WHERE id IN ($id_monitoring)";        
        $query = $this->db->query($sql);
        $result = $query->result_array();

        // set data id_pengajuan_pemohon dari hasil query
        foreach ($result as $key => $row) {
            $array_id_pengajuan_pemohon[$row['id']] = $row['id_pengajuan_pemohon'];
        }
        $id_pengajuan_pemohon = implode(",", $array_id_pengajuan_pemohon);

        // ambil data tanggal pengajuan dari tabel pengajuan_pemohon
        $sql_tgl = "SELECT id, tanggal FROM pengajuan_pemohon WHERE id IN ($id_pengajuan_pemohon)";        
        $query_tgl = $this->db->query($sql_tgl);
        $result_tgl = $query_tgl->result_array();
        foreach ($result_tgl as $key => $row) {
            $array_id_pengajuan_pemohon_tgl[$row['id']] = $row['tanggal'];
        }

        $data['form_ko_data'] = $result;
        $data['id_monitoring'] = $id_monitoring;
        $data['periode'] = $periode;
        $data['saldo_akhir'] = $saldo_akhir;
        $data['diajukan_oleh'] = $diajukan_oleh;
        $data['diperiksa_oleh'] = $diperiksa_oleh;
        $data['disetujui_oleh'] = $disetujui_oleh;
        // hitung total pengajuan dan total penarikan
        $total_pengajuan = 0;
        $total_penarikan = 0;
        foreach ($result as $row) {
            $total_pengajuan += $row['nominal_disetujui_umko'];
            $total_penarikan += $row['nominal_umko_cair'];
        }
        $data['total_pengajuan'] = number_format($total_pengajuan);
        $data['total_penarikan'] = number_format($total_penarikan);
        // siapkan data rincian untuk tabel
        $data['rincian'] = $result;
        $data['array'] = $array_id_pengajuan_pemohon;
        $data['array_tgl'] = $array_id_pengajuan_pemohon_tgl;
        
        return $data;
        //$this->load->view('unit_kerja/pengajuan_form_edit',$data);
        
    }
}
