<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('menu_helper');
        $this->load->helper('status_helper');
        $this->load->library('Ajax_pagination_pengajuan');
        $this->perPage = 2;

        // Load session library
		$this->load->library('session');
        
		// Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() 
    {   
        $data['title'] = 'Daftar Pengajuan';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('unit_kerja/pengajuan-ajax-index', $data);        
        $this->load->view('template/footer');
    }

    public function form() 
    {
        $data['title'] = 'Form Mutasi';
        $data['nama'] = 'test nama';
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();
        
        $data['kode_bidang'] = $this->session->userdata('logged_anggaran')['kode_bidang'];

        // ambil identitas pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT * FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$data[kode_bidang]' ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data_pejabat[] = array(
                'nip' => $row['nip'],
                'nama' => $row['nama'],
                'jabatan' => $row['jabatan'],
                'telp' => $row['telp']
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
        }
        $data['array_dpsj'] = $array_dpsj;        
        $data['kode_unit'] = $kode_unit;

        // Generate nomor pengajuan untuk preview
        //$data['preview_nomor'] = $this->Anggaran_model->generate_nomor_pengajuan($kode_unit); // Default kode
        $data['preview_nomor'] = '';

		// tentukan jumlah anggaran yg sudah ditetapkan dari masing2 akun untuk setiap kode_dpsj
		$array_kode_dpsj = "'".implode("','", $array_kode_dpsj)."'";
		
        $sql = "SELECT kode_akun, anggaran FROM rka WHERE kode_dpsj in ($array_kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_anggaran[$row['kode_akun']] = $row['anggaran'];
        }
		$data['array_anggaran'] = $array_anggaran;

		// tentukan total komitmen dari setiap akun
		$array_komitmen = array();
        $sql = "SELECT DISTINCT kode_akun, SUM(komitmen) as komitmen FROM pengajuan_rincian WHERE kode_dpsj in ($array_kode_dpsj) GROUP BY kode_akun";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_komitmen[$row['kode_akun']] = $row['komitmen'];
        }
		$data['array_komitmen'] = $array_komitmen;
		
		// tentukan total realisasi dari setiap akun
		$array_realisasi = array();
        $sql = "SELECT DISTINCT kode_akun, SUM(jumlah) as realisasi FROM realisasi WHERE kode_dpsj in ($array_kode_dpsj) GROUP BY kode_akun";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_realisasi[$row['kode_akun']] = $row['realisasi'];
        }
		$data['array_realisasi'] = $array_realisasi;
		
		// tentukan sisa anggaran
		foreach($array_anggaran as $key => $value){
			if(isset($array_komitmen[$key])){
				$komitmen = $array_komitmen[$key];
			} else {
				$komitmen = 0;
			}

			if(isset($array_realisasi[$key])){
				$realisasi = $array_realisasi[$key];
			} else {
				$realisasi = 0;
			}

			if($realisasi > 0){
				$sisa_anggaran = $value - $komitmen + ($komitmen - $realisasi);
			} else {
				$sisa_anggaran = $value - $komitmen;
			}
			//
			
			$array_sisa_anggaran[$key] = array('anggaran'=>$value, 'komitmen'=>$komitmen, 'realisasi'=>$realisasi, 'sisa_anggaran'=>$sisa_anggaran);
		}
		$data['array_sisa_anggaran'] = json_encode($array_sisa_anggaran);
		$data['sql'] = $sql;

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('unit_id', 'Unit', 'required');
        $this->form_validation->set_rules('penanggung_jawab', 'Penanggung Jawab', 'required');
        $this->form_validation->set_rules('nomor_identitas', 'NPM/NIP/NUP', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');
        $this->form_validation->set_rules('untuk_nama', 'Untuk dan Atas Nama', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/tes_menu', array('menu' => menu()) );
            $this->load->view('unit_kerja/mutasi_form', $data);
            $this->load->view('template/footer');
			//$this->load->view('anggaran/pengajuan_script');
        } else {
            // Get kode unit
            $unit_id = $this->input->post('unit_id');
            //$unit = $this->Unit_model->get_by_id($unit_id);
            //$unit_kode = $unit ? $unit->kode_unit : 'kom';
            
            // Data Pemohon
            $data_pemohon = array(
                //'nomor_pengajuan' => $this->Anggaran_model->generate_nomor_pengajuan($unit_kode),
                'tanggal' => $this->input->post('tanggal'),
                'unit_id' => $unit_id,
                'penanggung_jawab' => $this->input->post('penanggung_jawab'),
                'nomor_identitas' => $this->input->post('nomor_identitas'),
                'telepon' => $this->input->post('telepon'),
                'untuk_nama' => $this->input->post('untuk_nama'),
                'user_id' => $this->session->userdata('user_id'),
                'status' => 'diajukan',
                'created_at' => date('Y-m-d H:i:s')
            );
            
            // Data Rincian
            $data_rincian = array();
            $project_costings = $this->input->post('project_costing');
            $akuns = $this->input->post('akun');
            $jumlahs = $this->input->post('jumlah');
            $keterangans = $this->input->post('keterangan');
            
            for($i = 0; $i < count($project_costings); $i++) {
                if(!empty($project_costings[$i]) && !empty($akuns[$i]) && !empty($jumlahs[$i])) {
                    $data_rincian[] = array(
                        'project_costing' => $project_costings[$i],
                        'akun' => $akuns[$i],
                        'jumlah' => str_replace(',', '', $jumlahs[$i]),
                        'keterangan' => $keterangans[$i]
                    );
                }
            }
            
            if($this->Anggaran_model->insert_pengajuan($data_pemohon, $data_rincian)) {
                $this->session->set_flashdata('success', 'Pengajuan dana berhasil dikirim');
                redirect('anggaran');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengirim pengajuan dana');
                redirect('pengajuan');
            }
        }
    }
    
}