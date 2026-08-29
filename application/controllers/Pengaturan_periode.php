<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_periode extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('periode_lock_model');
        // Tambahkan validasi login/session sesuai kebutuhan
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('session');      
        $this->load->helper('menu_helper');   
     
		// Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }
    
    // Tampilkan semua periode
    public function index() {
		$data['title'] = 'Pengaturan Periode';
		$data['nama'] = $this->session->userdata['logged_anggaran']['username'];

        $data['periode'] = $this->periode_lock_model->get_all_periode();
        $data['active_periode'] = $this->periode_lock_model->get_active_periode();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('periode/index', $data);
        $this->load->view('template/footer');

        // Cek apakah user sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }
    
    // Ubah status lock
    public function toggle_lock($id) {
        // Cek apakah sudah login sebagai admin
        // if (!$this->session->userdata('is_admin')) {
        //     redirect('login');
        // }
        
        $this->db->where('id', $id);
        $periode = $this->db->get('periode')->row();
        
        if ($periode) {
            $new_status = $periode->lock_data == 1 ? 0 : 1;
            
            if ($this->periode_lock_model->update_lock($id, $new_status)) {
                $this->session->set_flashdata('success', 'Status periode berhasil diubah');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengubah status periode');
            }
        }
        
        redirect('pengaturan_periode');
    }
    
    // Inisialisasi periode baru
    public function initialize() {
        if ($this->input->post()) {
            $tahun = $this->input->post('tahun');
            $bulan = $this->input->post('bulan');
            
            if ($this->periode_lock_model->initialize_periode($tahun, $bulan)) {
                $this->session->set_flashdata('success', 'Periode berhasil diinisialisasi');
            } else {
                $this->session->set_flashdata('error', 'Periode sudah ada atau gagal dibuat');
            }
        }
        
        redirect('pengaturan_periode');
    }
}