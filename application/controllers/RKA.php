<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RKA extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Rka_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
		$this->load->helper('menu_helper');
        $this->load->library('session');
        
        // Cek session/login (sesuaikan dengan auth Anda)
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth');
        }
    }

    public function index() {
        $data['title'] = 'Data Anggaran';
        
        // Ambil parameter filter dari URL
        $tahun_filter = $this->input->get('tahun');
        
        if ($tahun_filter) {
            $data['anggaran'] = $this->Rka_model->get_by_tahun($tahun_filter);
            $data['tahun_filter'] = $tahun_filter;
        } else {
            $data['anggaran'] = $this->Rka_model->get_all();
        }
        
        // Ambil daftar tahun untuk dropdown filter
        $data['tahun_list'] = $this->Rka_model->get_distinct_tahun();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('rka/index', $data);
        $this->load->view('template/footer');
        $this->load->view('rka/rka_script');
    }

    public function create() {
        $data['title'] = 'Tambah Anggaran DPSJ';
        
        // Ambil data untuk dropdown (jika diperlukan)
        $data['kode_dpsj_list'] = $this->Rka_model->get_distinct_kode_dpsj();
        $data['kode_dana_list'] = $this->Rka_model->get_distinct_kode_dana();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('rka/create', $data);
        $this->load->view('template/footer');
        $this->load->view('rka/rka_script');
    }

    public function store() {
        $this->form_validation->set_rules('tahun_anggaran', 'Tahun Anggaran', 'required|numeric|exact_length[4]');
        $this->form_validation->set_rules('kode_dpsj', 'Kode DPSJ', 'required');
        $this->form_validation->set_rules('kode_kegiatan', 'Kode Kegiatan', 'required');
        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'required');
        $this->form_validation->set_rules('anggaran', 'Anggaran', 'numeric');
        $this->form_validation->set_rules('flag_payroll', 'Flag Payroll', 'required'); 

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'tahun_anggaran' => $this->input->post('tahun_anggaran'),
                'kode_dpsj' => $this->input->post('kode_dpsj'),
                'deskripsi_dpsj' => $this->input->post('deskripsi_dpsj'),
                'kode_kegiatan' => $this->input->post('kode_kegiatan'),
                'nama_kegiatan_pendek' => $this->input->post('nama_kegiatan_pendek'),
                'nama_kegiatan' => $this->input->post('nama_kegiatan'),
                'kode_dana' => $this->input->post('kode_dana'),
                'kode_akun' => $this->input->post('kode_akun'),
                'deskripsi_akun' => $this->input->post('deskripsi_akun'),
                'kategori_kegiatan' => $this->input->post('kategori_kegiatan'),
                'anggaran' => $this->input->post('anggaran') ?: 0,
                'komitmen' => $this->input->post('komitmen') ?: 0,
                'flag_payroll' => $this->input->post('flag_payroll')
            );
            
            $insert = $this->Rka_model->insert($data);
            
            if ($insert) {
                $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Data gagal ditambahkan');
            }
            
            redirect('RKA');
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Anggaran';
        $data['anggaran'] = $this->Rka_model->get_by_id($id);
        
        if (empty($data['anggaran'])) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('RKA');
        }
        
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('rka/edit', $data);
        $this->load->view('template/footer');
        $this->load->view('rka/rka_script');
    }

    public function update($id) {
        $this->form_validation->set_rules('tahun_anggaran', 'Tahun Anggaran', 'required|numeric|exact_length[4]');
        $this->form_validation->set_rules('kode_dpsj', 'Kode DPSJ', 'required');
        $this->form_validation->set_rules('kode_kegiatan', 'Kode Kegiatan', 'required');
        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'required');
        $this->form_validation->set_rules('anggaran', 'Anggaran', 'numeric');
        $this->form_validation->set_rules('flag_payroll', 'Flag Payroll', 'required'); 

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = array(
                'tahun_anggaran' => $this->input->post('tahun_anggaran'),
                'kode_dpsj' => $this->input->post('kode_dpsj'),
                'deskripsi_dpsj' => $this->input->post('deskripsi_dpsj'),
                'kode_kegiatan' => $this->input->post('kode_kegiatan'),
                'nama_kegiatan_pendek' => $this->input->post('nama_kegiatan_pendek'),
                'nama_kegiatan' => $this->input->post('nama_kegiatan'),
                'kode_dana' => $this->input->post('kode_dana'),
                'kode_akun' => $this->input->post('kode_akun'),
                'deskripsi_akun' => $this->input->post('deskripsi_akun'),
                'kategori_kegiatan' => $this->input->post('kategori_kegiatan'),
                'anggaran' => $this->input->post('anggaran') ?: 0,
                'komitmen' => $this->input->post('komitmen') ?: 0,
                'flag_payroll' => $this->input->post('flag_payroll')
            );
            
            $update = $this->Rka_model->update($id, $data);
            
            if ($update) {
                $this->session->set_flashdata('success', 'Data berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Data gagal diupdate');
            }
            
            redirect('RKA');
        }
    }

    public function delete($id) {
        // cek terlebih apakah akun yang akan dihapus memiliki nomor pengajuan yang sudah terinput di tabel monitoring
        $rka = $this->Rka_model->get_by_id($id);
        $has_transaksi = $this->Rka_model->check_transaksi_by_akun($rka->kode_kegiatan, $rka->kode_akun, $rka->kode_dana);
        if ($has_transaksi) {
            $this->session->set_flashdata('error', 'Data gagal dihapus karena sudah ada transaksi');
            redirect('RKA');
        }
        
        
        $delete = $this->Rka_model->delete($id);
        
        if ($delete) {
            $this->session->set_flashdata('success', 'Data berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Data gagal dihapus');
        }
        
        redirect('RKA');
    }
    
    // API untuk jQuery (jika diperlukan)
    public function get_data_json() {
        $data = $this->Rka_model->get_all();
        echo json_encode($data);
    }

    public function search_dpsj_rka() {
        $keyword = $this->input->post('keyword');
        
        if (strlen($keyword) < 2) {
            echo json_encode(['status' => 'error', 'message' => 'Keyword terlalu pendek']);
            return;
        }

        $this->db->distinct(); // Menambahkan DISTINCT
        $this->db->select('kode_dpsj, deskripsi_dpsj');
        $this->db->like('kode_dpsj', $keyword);
        $this->db->or_like('deskripsi_dpsj', $keyword);
        $this->db->where('kode_dpsj IS NOT NULL');
        $this->db->limit(20); // Batasi hasil untuk performa
        $query = $this->db->get('rka');
        
        $results = $query->result();
        
        if (count($results) > 0) {
            echo json_encode([
                'status' => 'success', 
                'data' => $results
            ]);
        } else {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

}
?>