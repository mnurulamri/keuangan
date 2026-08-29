<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_test extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Mutasi_model');
        $this->load->library('form_validation');        
		$this->load->helper('url');        
        $this->load->helper('menu_helper');

        // Check authentication (sesuaikan dengan sistem auth Anda)
        /*if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }*/
    }
    
    // Index - List all mutations
    public function index() {
        $data['title'] = 'Mutasi Anggaran';
        $data['nama'] = 'test nama';
        // Pagination
        $config['base_url'] = site_url('mutasi/index');
        $config['total_rows'] = $this->Mutasi_model->count_mutasi();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['mutasi'] = $this->Mutasi_model->get_mutasi($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('mutasi/index', $data);
        $this->load->view('template/footer');
    }
    
    // Create new mutation
    public function create() {
        $data['title'] = 'Tambah Mutasi';
        $data['nama'] = 'Test Nama';
        $data['akun_options'] = $this->Mutasi_model->get_akun_options();

        $data['periode'] = $this->periode();
        
        $this->form_validation->set_rules('kode_kegiatan', 'Kode Kegiatan', 'required');
        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'required');
        $this->form_validation->set_rules('kode_dana', 'Kode Dana', 'required');
        $this->form_validation->set_rules('mutasi', 'Nilai Mutasi', 'required|numeric');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('no_bukti', 'No. Bukti', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('mutasi/create', $data);
            $this->load->view('template/footer');
        } else {
            // Get akun detail for additional data
            $akun_detail = $this->Mutasi_model->get_akun_detail(
                $this->input->post('kode_kegiatan'),
                $this->input->post('kode_akun'),
                $this->input->post('kode_dana')
            );
            
            if (!$akun_detail) {
                $this->session->set_flashdata('error', 'Akun tidak ditemukan!');
                redirect('mutasi/create');
            }
            
            // Prepare data for insertion
            $data_mutasi = array(
                'kode_dpsj' => $akun_detail->kode_dpsj,
                'deskripsi_dpsj' => $akun_detail->deskripsi_dpsj,
                'kode_kegiatan' => $this->input->post('kode_kegiatan'),
                'nama_kegiatan' => $akun_detail->nama_kegiatan,
                'kode_akun' => $this->input->post('kode_akun'),
                'deskripsi_akun' => $akun_detail->deskripsi_akun,
                'kode_dana' => $this->input->post('kode_dana'),
                'komitmen' => $akun_detail->komitmen,
                'aktual' => $akun_detail->aktual,
                'mutasi' => $this->input->post('mutasi'),
                'tanggal' => $this->input->post('tanggal'),
                'no_bukti' => $this->input->post('no_bukti'),
                'keterangan' => $this->input->post('keterangan'),
                'created_by' => $this->session->userdata('user_id')
            );
            
            if ($this->Mutasi_model->insert_mutasi($data_mutasi)) {
                $this->session->set_flashdata('success', 'Mutasi berhasil ditambahkan!');
                redirect('mutasi');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan mutasi!');
                redirect('mutasi/create');
            }
        }
    }
    
    // Edit mutation
    public function edit($id) {
        $data['title'] = 'Edit Mutasi';
        $data['mutasi'] = $this->Mutasi_model->get_mutasi_by_id($id);
        $data['akun_options'] = $this->Mutasi_model->get_akun_options();
        
        if (!$data['mutasi']) {
            show_404();
        }
        
        $this->form_validation->set_rules('kode_kegiatan', 'Kode Kegiatan', 'required');
        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'required');
        $this->form_validation->set_rules('kode_dana', 'Kode Dana', 'required');
        $this->form_validation->set_rules('mutasi', 'Nilai Mutasi', 'required|numeric');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('no_bukti', 'No. Bukti', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('mutasi/edit', $data);
            $this->load->view('template/footer');
        } else {
            // Get akun detail for additional data
            $akun_detail = $this->Mutasi_model->get_akun_detail(
                $this->input->post('kode_kegiatan'),
                $this->input->post('kode_akun'),
                $this->input->post('kode_dana')
            );
            
            if (!$akun_detail) {
                $this->session->set_flashdata('error', 'Akun tidak ditemukan!');
                redirect('mutasi/edit/' . $id);
            }
            
            // Prepare data for update
            $data_mutasi = array(
                'kode_dpsj' => $akun_detail->kode_dpsj,
                'deskripsi_dpsj' => $akun_detail->deskripsi_dpsj,
                'kode_kegiatan' => $this->input->post('kode_kegiatan'),
                'nama_kegiatan' => $akun_detail->nama_kegiatan,
                'kode_akun' => $this->input->post('kode_akun'),
                'deskripsi_akun' => $akun_detail->deskripsi_akun,
                'kode_dana' => $this->input->post('kode_dana'),
                'komitmen' => $akun_detail->komitmen,
                'aktual' => $akun_detail->aktual,
                'mutasi' => $this->input->post('mutasi'),
                'tanggal' => $this->input->post('tanggal'),
                'no_bukti' => $this->input->post('no_bukti'),
                'keterangan' => $this->input->post('keterangan')
            );
            
            if ($this->Mutasi_model->update_mutasi($id, $data_mutasi)) {
                $this->session->set_flashdata('success', 'Mutasi berhasil diperbarui!');
                redirect('mutasi');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui mutasi!');
                redirect('mutasi/edit/' . $id);
            }
        }
    }
    
    // Delete mutation
    public function delete($id) {
        if (!$id) {
            redirect('mutasi');
        }
        
        if ($this->Mutasi_model->delete_mutasi($id)) {
            $this->session->set_flashdata('success', 'Mutasi berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus mutasi!');
        }
        
        redirect('mutasi');
    }
    
    // Get akun detail via AJAX
    public function get_akun_info() {
        $kode_kegiatan = $this->input->post('kode_kegiatan');
        $kode_akun = $this->input->post('kode_akun');
        $kode_dana = $this->input->post('kode_dana');
        
        $akun_detail = $this->Mutasi_model->get_akun_detail($kode_kegiatan, $kode_akun, $kode_dana);
        
        if ($akun_detail) {
            echo json_encode([
                'success' => true,
                'data' => $akun_detail
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Akun tidak ditemukan'
            ]);
        }
    }

    public function periode() {
        $sql = "SELECT * FROM periodew WHERE lock_data = 0";
        $query = $this->db->query($sql);
        return $query->result();
    }
}
?>