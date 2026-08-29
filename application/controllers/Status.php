<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Status_model');
        $this->load->library('form_validation');
        $this->load->helper('menu_helper');
		$this->load->helper('url');
		$this->load->library('session');
        
        // Check login (sesuaikan dengan authentication system Anda)
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }
    
    /**
     * Main page - List all status
     */
    public function index() {
        $data['title'] = 'Pengaturan Status';
        $data['statuses'] = $this->Status_model->get_all_status();
        $data['roles'] = $this->Status_model->get_roles();
        $data['nama'] = $this->session->userdata('username');
        
        // Load views
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('status/index', $data);
        $this->load->view('template/footer');
    }
    
    /**
     * Create new status
     */
    public function create() {
        $data['title'] = 'Tambah Status Baru';
        $data['roles'] = $this->Status_model->get_roles();
        
        $this->form_validation->set_rules('kode_status', 'Kode Status', 'required|numeric');
        $this->form_validation->set_rules('nama_status', 'Nama Status', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/tes_menu', array('menu' => menu()));
            $this->load->view('status/create', $data);
            $this->load->view('template/footer');
        } else {
            $post_data = $this->input->post();
            
            // Prepare data for insertion
            $status_data = array(
                'kode_status' => $post_data['kode_status'],
                'nama_status' => $post_data['nama_status'],
                'keterangan' => $post_data['keterangan'],
                'pum' => isset($post_data['pum']) ? 1 : 0,
                'anggaran' => isset($post_data['anggaran']) ? 1 : 0,
                'korpum' => isset($post_data['korpum']) ? 1 : 0,
                'manajer' => isset($post_data['manajer']) ? 1 : 0,
                'kasir' => isset($post_data['kasir']) ? 1 : 0,
                'verifikator' => isset($post_data['verifkator']) ? 1 : 0,
                'yunior_akuntan' => isset($post_data['yunior_akuntan']) ? 1 : 0
            );
            
            $this->Status_model->add_status($status_data);
            $this->session->set_flashdata('success', 'Status berhasil ditambahkan');
            redirect('status');
        }
    }
    
    /**
     * Edit status
     */
    public function edit($id) {
        $data['title'] = 'Edit Status';
        $data['status'] = $this->Status_model->get_status_by_id($id);
        $data['roles'] = $this->Status_model->get_roles();
        $data['nama'] = $this->session->userdata('username');
        
        if (empty($data['status'])) {
            show_404();
        }
        
        $this->form_validation->set_rules('kode_status', 'Kode Status', 'required|numeric');
        $this->form_validation->set_rules('nama_status', 'Nama Status', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/tes_menu', array('menu' => menu()));
            $this->load->view('status/edit', $data);
            $this->load->view('template/footer');
        } else {
            $post_data = $this->input->post();
            
            // Prepare data for update
            $status_data = array(
                'kode_status' => $post_data['kode_status'],
                'nama_status' => $post_data['nama_status'],
                'keterangan' => $post_data['keterangan'],
                'pum' => isset($post_data['pum']) ? 1 : 0,
                'anggaran' => isset($post_data['anggaran']) ? 1 : 0,
                'korpum' => isset($post_data['korpum']) ? 1 : 0,
                'manajer' => isset($post_data['manajer']) ? 1 : 0,
                'kasir' => isset($post_data['kasir']) ? 1 : 0,
                'verifikator' => isset($post_data['verifkator']) ? 1 : 0,
                'yunior_akuntan' => isset($post_data['yunior_akuntan']) ? 1 : 0
            );
            
            $this->Status_model->update_status($id, $status_data);
            $this->session->set_flashdata('success', 'Status berhasil diperbarui');
            redirect('status');
        }
    }
    
    /**
     * Delete status
     */
    public function delete($id) {
        if (!$id) {
            redirect('status');
        }
        
        $this->Status_model->delete_status($id);
        $this->session->set_flashdata('success', 'Status berhasil dihapus');
        redirect('status');
    }
    
    /**
     * Get status list by role (for AJAX)
     */
    public function get_by_role($role) {
        $valid_roles = array_keys($this->Status_model->get_roles());
        
        if (in_array($role, $valid_roles)) {
            $statuses = $this->Status_model->get_status_by_role($role, 1);
            echo json_encode($statuses);
        } else {
            echo json_encode(array());
        }
    }
}
?>