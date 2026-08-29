<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('session');
        
		$session_data = array(
                        'username'  => 'xxx',
                        'hak_akses' => 1,
                        'role' => 'admin',
                        'kode_org_anggaran' => '00',
                        'cn_anggaran' => ''
                    );
		$this->session->set_userdata('logged_anggaran', $session_data);

        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }
    
    public function unit_kerja()
    {
        
        $data['title'] = 'Unit Kerja';
        $data['nama'] = 'xxx';

        $unit_kerja = $this->db->get('units')->result_array();
        $data['unit_kerja'] = $unit_kerja;
        
        //$this->load->view('template/header', $data);
        $this->load->view('anggaran/unit_kerja', $data);
    }

    public function set_role_bridge()
	{
        $role_id = $this->input->post('kode_bidang');
        $newdata = array(
            'kode_bidang'  => $role_id
        );

        $this->session->set_userdata($newdata);
        //header("location:".base_url()."Test_autocomplete");
        redirect('pengajuan/form');
    }
}