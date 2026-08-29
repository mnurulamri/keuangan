<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load model, helper, or library if needed
        // $this->load->model('Some_model')
        $this->load->database();
		$this->load->helper('url');
		$this->load->helper('menu_helper');
		$this->load->library('session');

        // Cek apakah user sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Data yang ingin dikirim ke view
        $data['title'] = 'Dashboard';

        // Load view beranda unit kerja$data['title'] = 'Monitoring Anggaran';
        $data['nama'] = $this->session->userdata('logged_anggaran')['username'];

        // ambil data status dari tabel status untuk field anggaran yang bernilai 1
        $sql = "SELECT * FROM status WHERE manajer = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        // ambil data kode_status dari tabel monitoring sesuai dengan unit kerja user yang login untuk menghitung jumlah per kode_status untuk ditampilkan di dashboard
        $sql_monitoring = "SELECT kode_status, COUNT(*) as jumlah FROM monitoring GROUP BY kode_status";
        $data['monitoring_list'] = $this->db->query($sql_monitoring)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('manajer/dashboard', $data);
		$this->load->view('template/footer');
    }
}