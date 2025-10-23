<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load model, helper, or library if needed
        // $this->load->model('Some_model');
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('menu_helper');   
        $this->load->helper('dashboard_helper');
    }

    public function index()
    {
        // Data yang ingin dikirim ke view
        $data['title'] = 'Dashboard';

        // Load view beranda unit Anggaran
        $data['title'] = 'Monitoring Anggaran';
        $data['nama'] = 'test nama';
        $data['info_boxes'] = dashboard_status($this->session->userdata('logged_anggaran')['role']);

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar' );
        $this->load->view('anggaran/dashboard', $data);
        $this->load->view('template/footer');
    }
}