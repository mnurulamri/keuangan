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

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE pum = 1";
        $status_list = $this->db->query($sql)->result_array();
		$status_list[] = array('id'=>50, 'kode_status'=>5, 'nama_status'=>'Retur');

		$data_status_list = array_filter($status_list, function($item) {
		    return $item['kode_status'] != 12 and $item['kode_status'] != 10; // Simpan semua KECUALI yang id-nya 12
		});

		usort($data_status_list, function($a, $b) {
		    // Mengurutkan dari kecil ke besar (Ascending)
		    return $a['kode_status'] <=> $b['kode_status'];
		});

		$data['status_list'] = $data_status_list;

        // ambil data kode_status dari tabel monitoring sesuai dengan unit kerja user yang login untuk menghitung jumlah per kode_status untuk ditampilkan di dashboard
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];// ambil kode dpsj berdasarkan kode bidang
        //$sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
        /*$sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_dpsj = '".$this->session->userdata('logged_anggaran')['kode_dpsj']."'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_dpsj[] = $row['kode_dpsj'];
        }*/
        $kode_dpsj = $this->session->userdata['logged_anggaran']['array_dpsj'];
        $kode_dpsj = implode("','", $kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";
        $data[$kode_dpsj] = $kode_dpsj;

        $sql_monitoring = "SELECT kode_status, COUNT(*) as jumlah FROM monitoring WHERE kode_dpsj IN ($kode_dpsj) GROUP BY kode_status";
        $data['monitoring_list'] = $this->db->query($sql_monitoring)->result_array();

        // ambil kode_status = 0 dari tabel pengajuan_pemohon untuk menampilkan info box "Belum Diajukan"
        $sql_belum_diajukan = "SELECT COUNT(*) as jumlah FROM pengajuan_pemohon WHERE kode_dpsj IN ($kode_dpsj) AND kode_status = 0";
        $query_belum_diajukan = $this->db->query($sql_belum_diajukan);
        $result_belum_diajukan = $query_belum_diajukan->row_array();
        $data['belum_diajukan'] = $result_belum_diajukan['jumlah'];

		$sql_retur = "SELECT COUNT(id) as jumlah FROM view_pengajuan WHERE kode_dpsj IN ($kode_dpsj) AND kode_status IN (12,14,22,32,33,42,52,53,62,63,64,65)";
        $query_retur = $this->db->query($sql_retur);
        $result_retur = $query_retur->row_array();
        $data['retur'] = $result_retur['jumlah'];
        
		$sql_menunggu_verifikasi_anggaran = "SELECT COUNT(id) as jumlah FROM view_pengajuan WHERE kode_dpsj IN ($kode_dpsj) AND kode_status IN (1,10)";
        $query_menunggu_verifikasi_anggaran = $this->db->query($sql_menunggu_verifikasi_anggaran);
        $result_menunggu_verifikasi_anggaran = $query_menunggu_verifikasi_anggaran->row_array();
        $data['menunggu_verifikasi_anggaran'] = $result_menunggu_verifikasi_anggaran['jumlah'];

        // gabungkan $result_belum_diajukan ke $data['monitoring_list']
        $data['monitoring_list'][] = array(
            'kode_status' => 0,
            'jumlah' => $data['belum_diajukan']
        );
		$data['monitoring_list'][] = array(
            'kode_status' => 5,
            'jumlah' => $data['retur']
        );
		$data['monitoring_list'][] = array(
            'kode_status' => 1,
            'jumlah' => $data['menunggu_verifikasi_anggaran']
        );

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('unit_kerja/dashboard', $data);
		$this->load->view('template/footer');
    }
    public function test()
    {
        // Data yang ingin dikirim ke view
        $data['title'] = 'Dashboard';

        // Load view beranda unit kerja$data['title'] = 'Monitoring Anggaran';
        $data['nama'] = $this->session->userdata('logged_anggaran')['username'];

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE pum = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        // ambil data kode_status dari tabel monitoring sesuai dengan unit kerja user yang login untuk menghitung jumlah per kode_status untuk ditampilkan di dashboard
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];// ambil kode dpsj berdasarkan kode bidang
        //$sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
        $sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_dpsj = '".$this->session->userdata('logged_anggaran')['kode_dpsj']."'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_dpsj[] = $row['kode_dpsj'];
        }
        $kode_dpsj = implode("','", $kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";
        $data[$kode_dpsj] = $kode_dpsj;

        $sql_monitoring = "SELECT kode_status, COUNT(*) as jumlah FROM monitoring WHERE kode_dpsj IN ($kode_dpsj) GROUP BY kode_status";
        $data['monitoring_list'] = $this->db->query($sql_monitoring)->result_array();

        // ambil kode_status = 0 dari tabel pengajuan_pemohon untuk menampilkan info box "Belum Diajukan"
        $sql_belum_diajukan = "SELECT COUNT(*) as jumlah FROM pengajuan_pemohon WHERE kode_dpsj IN ($kode_dpsj) AND kode_status = 0";
        $query_belum_diajukan = $this->db->query($sql_belum_diajukan);
        $result_belum_diajukan = $query_belum_diajukan->row_array();
        $data['belum_diajukan'] = $result_belum_diajukan['jumlah'];

        // gabungkan $result_belum_diajukan ke $data['monitoring_list']
        $data['monitoring_list'][] = array(
            'kode_status' => 0,
            'jumlah' => $data['belum_diajukan']
        );

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('unit_kerja/dashboard_test', $data);
		$this->load->view('template/footer');
    }
}