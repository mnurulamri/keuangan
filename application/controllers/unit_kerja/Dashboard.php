<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load model, helper, or library if needed
        // $this->load->model('Some_model')
		$this->load->helper('url');
		$this->load->helper('menu_helper');
		$this->load->library('session');
    }

    public function index()
    {
        // Data yang ingin dikirim ke view
        $data['title'] = 'Dashboard';

        // Load view beranda unit kerja$data['title'] = 'Monitoring Anggaran';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('unit_kerja/dashboard', $data);
		$this->load->view('template/footer');
    }
}