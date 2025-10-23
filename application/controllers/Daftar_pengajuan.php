<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_pengajuan extends CI_Controller {

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

        //if (!$this->session->userdata('logged_anggaran')) {
            //redirect('auth/login');
        //}
    }

	public function index() 
	{		
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('kode_bidang');

        // ambil kode dpsj berdasarkan kode bidang
        $sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_dpsj[] = $row['kode_dpsj'];
        }
        $kode_dpsj = implode("','", $kode_dpsj);
        $kode_dpsj = "'".$kode_dpsj."'";

        // ambil data dari tabel pengajuan_rincian berdasarkan kode_dpsj
        $sql = "SELECT * FROM pengajuan_rincian WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result_pengajuan_rincian = $query->result_array();

        // ambil data anggaraan dari tabel rka berdasarkan kode_dpsj
        $sql = "SELECT * FROM rka WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_akun[$row['kode_akun']] = $row['anggaran'];
        }

		// ambil data monitoring -> tambahkan kode dpsj supaya hanya bisa di lihat oleh unit ybs
        $sql = "SELECT * FROM monitoring WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $array_uraian[$row['nomor_pengajuan']] = $row['uraian'];
            $array_kode_status[$row['nomor_pengajuan']] = $row['kode_status'];
        }

        // ambil data untuk -> tambahkan kode dpsj supaya hanya bisa di lihat oleh unit ybs
        $sql = "SELECT id, untuk FROM pengajuan_pemohon WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $array_untuk[$row['id']] = $row['untuk'];
        }

        $data['array_untuk'] = $array_untuk;

        $data['array_uraian'] = $array_uraian;
        $data['array_kode_status'] = $array_kode_status;

        $data['rincian'] = $result_pengajuan_rincian;
        $data['kode_akun'] = $kode_akun;
        $data['sql'] = $sql;

        $data['title'] = 'Daftar Pengajuan Dana';
        $data['nama'] = 'test nama';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('unit_kerja/daftar_pengajuan', $data);        
        $this->load->view('template/footer');
    
	}

    public function preview() {
        	
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('kode_bidang');
        $data['kode_bidang'] = $kode_bidang;
        
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();

        // ambil nomor pengajuan dari input GET
        //$nomor_pengajuan = $this->input->get('nomor_pengajuan');
        $nomor_pengajuan = $this->input->post('nomor_pengajuan'); 
       // $nomor_pengajuan = '001/ANG.06/2025-PAF'; // contoh nomor pengajuan, ganti dengan input yang sesuai
        
        if (!$nomor_pengajuan) {
            show_error('Nomor pengajuan tidak ditemukan.');
            return;
        }
        $data['nomor_pengajuan'] = $nomor_pengajuan;
        $data['preview_nomor'] = $nomor_pengajuan;
        //print_r($data); exit();
        // ambil data tanggal dari database
        $sql = "SELECT tanggal FROM pengajuan_pemohon WHERE nomor_pengajuan = ?";
        $query = $this->db->query($sql, array($nomor_pengajuan));
        $result = $query->row_array();
        if (!$result) {
            show_error('Data pengajuan tidak ditemukan.');
            return;
        }
        $data['tanggal'] = $result['tanggal'];
        
        
        // ambil identitas pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT * FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$kode_bidang' ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data_pejabat[] = array(
                'nip' => $row['nip'],
                'nama' => $row['nama'],
                'jabatan' => $row['jabatan'],
                'telp' => $row['telp']
            );
        }
        $data['pejabat'] = $data_pejabat;
        
        // ambil nama unit
        $sql = "SELECT * FROM units WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $nama_unit = $row['nama_unit'];            
        }
        $data['nama_unit'] = $nama_unit;

        // ambil kode_ddpsj
        $sql = "SELECT * FROM unit_kerja WHERE kode_bidang = '$data[kode_bidang]'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_dpsj[] = $row;  
            $kode_unit = $row['kode_unit']; 
            $array_kode_dpsj[$row['kode_dpsj']] = $row['kode_dpsj'];
            $array_kode_dpsj_value[] = $row['kode_dpsj']; 
        }

        $data['array_dpsj'] = $array_dpsj;        
        $data['kode_unit'] = $kode_unit;

        $kode_dpsj = implode("','", $array_kode_dpsj_value);
        $kode_dpsj = "'".$kode_dpsj."'";

        // ambil data dari tabel pengajuan_rincian berdasarkan kode_dpsj
        $sql = "SELECT * FROM pengajuan_rincian WHERE nomor_pengajuan = '$nomor_pengajuan'";
        $query = $this->db->query($sql);
        $rincian = $query->result_array();
        $data['rincian'] = $rincian;

        // ambil data anggaraan dari tabel rka berdasarkan kode_dpsj
        $sql = "SELECT * FROM rka WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_akun[$row['kode_akun']] = $row['anggaran'];
        }
        //echo '<pre>';print_r($data);echo '</pre>';

        // hitung sisa anggaran
        $array_sisa_anggaran = array();
        foreach ($rincian as $row) {
            $kode_dpsj = $row['kode_dpsj'];
            $kode_kegiatan = $row['kode_kegiatan'];
            $kode_akun = $row['kode_akun'];
            $kode_dana = $row['kode_dana'];

            // ambil data anggaran awal
            $sql_anggaran = "SELECT sisa_anggaran FROM rka WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_anggaran = $this->db->query($sql_anggaran, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $anggaran_awal = $query_anggaran->row_array()['sisa_anggaran'] ?? 0;

            // ambil data total komitmen
            $sql_komitmen = "SELECT SUM(komitmen) AS total_komitmen FROM pengajuan_rincian WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_komitmen = $this->db->query($sql_komitmen, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $total_komitmen = $query_komitmen->row_array()['total_komitmen'] ?? 0;

            // ambil data realisasi
            $sql_realisasi = "SELECT SUM(jumlah) AS total_realisasi FROM realisasi WHERE kode_dpsj = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query_realisasi = $this->db->query($sql_realisasi, array($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana));
            $total_realisasi = $query_realisasi->row_array()['total_realisasi'] ?? 0;

            // hitung sisa anggaran
            if ($total_realisasi == 0) {
                $sisa_anggaran = number_format($anggaran_awal - $total_komitmen);
            } else {
                $sisa_anggaran = number_format($anggaran_awal - $total_realisasi);
            }

            $array_sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana] = $sisa_anggaran;
        }

        $data['sisa_anggaran'] = $array_sisa_anggaran;

        $this->load->view('unit_kerja/form_pengajuan',$data);
    }

    public function rubahStatusPengajuan() {
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');
        $array_status = array('ajukan'=>1, 'disetujui'=>2, 'ditolak'=>3, 'dibatalkan'=>4, 'diterima'=>5, 'selesai'=>6);
        if (!isset($array_status[$this->input->post('status')])) {
            echo json_encode(array('status' => 'error', 'message' => 'Status pengajuan tidak valid.'));
            return;
        }
        $status = $array_status[$this->input->post('status')];

        // Update status pengajuan
        $data = array(
            'kode_status' => $status,
            'tgl_pengajuan' => date('Y-m-d H:i:s')
        );

        $this->db->where('nomor_pengajuan', $nomor_pengajuan);
        if ($this->db->update('monitoring', $data)) {
            echo json_encode(array('status' => 'success', 'message' => 'Status pengajuan berhasil diubah.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Gagal mengubah status pengajuan.'));
        }
    }
}