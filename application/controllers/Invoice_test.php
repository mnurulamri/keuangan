<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_test extends CI_Controller {
    /**
     * Constructor to initialize the controller
     * Loads necessary models, libraries, and session data
     */
    //protected $perPage;


    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
        $this->load->helper('status_helper');
        $this->load->helper('menu_helper');
		$this->load->helper('periode_helper');
		$this->load->library('session');
        $this->load->library('Ajax_pagination_pengajuan');
        $this->perPage = 10;

        // Cek apakah user sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function search() {
                // set periode penginputan
        $sql = "SELECT * FROM periode ORDER BY tahun DESC, bulan ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
		foreach ($result as $row){
			$array_tahun[] = $row['tahun'];
			$array_bulan[] = $row['bulan'];
		}
        $data['array_tahun'] = array_unique($array_tahun);
        $data['array_bulan'] = $array_bulan;
            
		//set bulan aktif
		$sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
		foreach ($result as $row){
			$array_tahun_aktif[] = $row['tahun'];
			$array_bulan_aktif[] = $row['bulan'];
		}
        $data['array_tahun_aktif'] = array_unique($array_tahun_aktif);
        $data['array_bulan_aktif'] = $array_bulan_aktif;

        $data['title'] = 'Monitoring Anggaran';
        $data['nama'] = 'test nama';
        
        // ambil kode_status dari input get
        $data['kode_status'] = $this->input->get('kode_status');

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE anggaran = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => $this->menu()) );
        $this->load->view('anggaran/invoice-search-index', $data);        
        $this->load->view('template/footer');

    }

    public function search_data() {

		$tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        
        // Tambahkan filter status pada WHERE
        $where = "WHERE SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan' AND kode_status = 13";
        
        # get records
        $sql = "SELECT * FROM view_pengajuan $where ORDER BY nomor_pengajuan DESC";
        $query = $this->db->query($sql); //$query = $this->db->get();
        
        # get total records
        $sql_count = "SELECT id FROM view_pengajuan $where";
        $query_count = $this->db->query($sql_count);

        $daftar_pengajuan = array();
        $array_rincian = array();
        $array_monitoring = array();
        $array_monitoring_keterangan = array();
        
        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            
            foreach ($query->result_array() as $rows){
                $array_id_pengajuan_pemohon[] = $rows['id'];                
                $daftar_pengajuan[$rows['id']] = $rows;
            }

            // get id pengajuan pemohon agar bisa digunakan untuk mengambil data rincian sesuai halaman
            $array_value_id = implode(",", $array_id_pengajuan_pemohon);

            
            # get rincian berdasarkan id pengajuan pemohon
            // jika ada id pengajuan pemohon
            if(!empty($array_value_id)) {
                $array_value_id = $array_value_id;
            } else {
                $array_value_id = 0;
            }

            // ambil rincian berdasarkan id pengajuan pemohon
            $sql_rincian = "SELECT * FROM view_pengajuan_rincian_realisasi WHERE id_pengajuan_pemohon IN ($array_value_id)";           
            $query_rincian = $this->db->query($sql_rincian);
            $result_rincian = $query_rincian->result_array();
            if($query_rincian->num_rows() > 0) {
                $array_rincian = array();
                foreach ($result_rincian as $row) {
                    $array_rincian[$row['id_pengajuan_pemohon']][] = $row;
                }
            } else {
                $array_rincian = array();
            }

            // ambil data monitoring -> untuk menampilkan kode_status
            $sql_monitoring = "SELECT kode_status, id_pengajuan_pemohon, anggaran_keterangan_disetujui, nomor_pengajuan, no_pp FROM monitoring WHERE id_pengajuan_pemohon IN ($array_value_id)";
            $query_monitoring = $this->db->query($sql_monitoring);
            if($query_monitoring->num_rows() > 0) {
                $array_monitoring = array();
                foreach ($query_monitoring->result_array() as $row) {
                    $array_monitoring[$row['id_pengajuan_pemohon']] = $row['kode_status'];
                    $array_monitoring_keterangan[$row['id_pengajuan_pemohon']] = $row['anggaran_keterangan_disetujui'] ?? '';
                }
            } else {
                $array_monitoring = array();
            }

        } else {
            $totalRec = 0;
            $array_value_id = 0;
            //$array_petugas = array();
        }

        # pagination
        $data['totalRec'] = $totalRec;
        $data['num_rows'] = $query->num_rows();
		$data['posts'] = $daftar_pengajuan;
        $data['array_rincian'] = $array_rincian;
        $data['sql'] = $sql;
        $data['array_value_id'] = $array_value_id;
        $data['array_monitoring'] = $array_monitoring;
        $data['array_monitoring_keterangan'] = $array_monitoring_keterangan;
        //echo '<pre>';print_r($data['posts']);echo '</pre>'; exit();
        # load the view
        $this->load->view('anggaran/invoice-search-data', $data, false);
    }

	public function menu()
	{		
		$sql = "SELECT * FROM menu where anggaran = 1 order by parent, sort";
		$query = $this->db->query($sql);
		$menu = $query->result_array();
		return $menu;
	}
}