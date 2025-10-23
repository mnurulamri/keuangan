<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_daftar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('session');
   
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

	public function index() 
	{		
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];

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

    /**
     * Method untuk menampilkan form konfirmasi ajukan
     * @return void
     */
    public function formKonfirmasiAjukan() {
        	
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];
        $data['kode_bidang'] = $kode_bidang;
        
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();

        // ambil nomor pengajuan dari input GET
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon'); 
        //$id_pengajuan_pemohon = 21;
        //$id_pengajuan_pemohon = intval($id_pengajuan_pemohon);
        
        if (!$id_pengajuan_pemohon) {
            show_error('Nomor pengajuan tidak ditemukan.');
            return;
        }
        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
        $data['preview_nomor'] = '';
        //print_r($data); exit();
        // ambil data tanggal dari database
        $sql = "SELECT * FROM pengajuan_pemohon WHERE id = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $result = $query->row_array();
        if (!$result) {
            show_error('Data pengajuan tidak ditemukan.');
            return;
        }
        $data['tanggal'] = $result['tanggal'];
        $data['untuk'] = $result['untuk'];
        
        
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

        //$kode_dpsj = implode("','", $array_kode_dpsj_value);
        //$kode_dpsj = "'".$kode_dpsj."'";
        $kode_dpsj = $this->input->post('kode_dpsj');
        $deskripsi_dpsj = $this->input->post('deskripsi_dpsj');
        $data['kode_dpsj'] = $kode_dpsj;
        $data['deskripsi_dpsj'] = $deskripsi_dpsj;

        // ambil data dari tabel pengajuan_rincian berdasarkan kode_dpsj
        $sql = "SELECT * FROM pengajuan_rincian WHERE id_pengajuan_pemohon = '$id_pengajuan_pemohon'";
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

        $this->load->view('unit_kerja/pengajuan_form_konfirmasi',$data);
    }

    /**
     * Method untuk mengajukan pengajuan
     * @return void
     */
    public function ajukan() {

        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        //$id_pengajuan_pemohon = intval($id_pengajuan_pemohon);
        // Validasi ID pengajuan pemohon
        if (!is_numeric($id_pengajuan_pemohon) || $id_pengajuan_pemohon <= 0) {
            echo json_encode(array('status' => 'error', 'message' => 'ID pengajuan pemohon tidak valid.'));
            return;
        }
        // Update status pengajuan
        $data = array(
            'kode_status' => 1, // Set status ke "ajukan"
            'tgl_diajukan' => date('Y-m-d H:i:s')
        );

        $tgl_diajukan = date('Y-m-d H:i:s');
        // Update data pengajuan pemohon
        //$sql = "UPDATE pengajuan_pemohon SET kode_status = 1, tgl_diajukan = '$tgl_diajukan' WHERE id = $id_pengajuan_pemohon";
        //$this->db->query($sql);
        //$sql = $this->db->set($data)->where('id', $id_pengajuan_pemohon)->update('pengajuan_pemohon');
        //$sql = $this->db->set($data)->where('id', $id_pengajuan_pemohon)->get_compiled_update('pengajuan_pemohon');
        $this->db->where('id', $id_pengajuan_pemohon);
        $this->db->update('pengajuan_pemohon', $data); 
        /*if ($this->db->update('pengajuan_pemohon', $data)) {
            echo json_encode(array('status' => 'success', 'message' => 'Pengajuan berhasil diajukan.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Gagal mengajukan pengajuan.'));
        }*/

            
//echo json_encode(array('response' => $sql)); exit();
        
        // jika berhasil, maka di cek dulu apakah id_pengajuan_pemohon sudah ada di tabel monitoring, jika tidak ada maka insert ke tabel monitoring dengan data tahun_anggaran, nomor_pengajuan (dibuat secara otomatis),  kode_dpsj, deskripsi_dpsj, uraian (diambil dari input untuk), nominal_pengajuan, kode_status dan tanggal sistem

        // Cek apakah id_pengajuan_pemohon sudah ada di tabel monitoring
        $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
        $query = $this->db->get('monitoring');
        if ($query->num_rows() > 0) {
            // Jika sudah ada, tidak perlu insert lagi
            echo json_encode(array('status' => 'info', 'message' => 'Pengajuan sudah ada di monitoring.'));
            return;
        }
        
        // Jika belum ada, maka insert ke tabel monitoring
        // Ambil data dari input
        $tahun_anggaran = date('Y');
        $kode_dpsj = $this->input->post('kode_dpsj');
        $uraian = $this->input->post('untuk');
        $nominal_pengajuan = $this->input->post('nominal_pengajuan');        
        $kode_status = 1; // Status "ajukan"
        $tgl_pengajuan = date('Y-m-d H:i:s');

        // Generate nomor pengajuan
        $sql = "SELECT kode_unit, nama_unit FROM unit_kerja WHERE kode_dpsj = '$kode_dpsj'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach($result as $row){
            $nama_unit = $row['nama_unit'];
            $kode_unit = $row['kode_unit'];
        }
        
        $nomor_pengajuan = $this->Anggaran_model->generate_nomor_pengajuan($kode_unit);

        // set nomor_urut terakhir dimabil dari $nmor_pengajuan
        $nomor_urut = substr($nomor_pengajuan, 0, 3);
        $nomor_urut = (int) $nomor_urut;



        // persiapan insert ke tabel monitoring
        $data_monitoring = array(
            'tahun_anggaran' => $tahun_anggaran,
            'nomor_urut' => $nomor_urut,
            'nomor_pengajuan' => $nomor_pengajuan,
            'kode_unit' => $kode_unit,
            'form' => 'D01',            
            'kode_dpsj' => $kode_dpsj,
            'uraian' => $uraian,
            'nominal_pengajuan' => $nominal_pengajuan,            
            'kode_status' => $kode_status,
            'tgl_pengajuan' => $tgl_pengajuan,
            'created_at' => date('Y-m-d H:i:s'),
            'id_pengajuan_pemohon' => $id_pengajuan_pemohon
        );

        //echo json_decode(array('data_monitoring' =>$data_monitoring)); exit();
        //print_r($data_monitoring); exit();

        // Insert data ke tabel monitoring
        $this->db->insert('monitoring', $data_monitoring);
        if ($this->db->affected_rows() > 0) {
            echo json_encode(array('status' => 'success', 'message' => 'Pengajuan berhasil diajukan dan dimasukkan ke monitoring.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Gagal memasukkan pengajuan ke monitoring.'));
        }

        // insert nomor_pengajuan ke tabel pengajuan_pemohon
        $data_pengajuan_pemohon = array(
            'nomor_pengajuan' => $nomor_pengajuan
        );
        $this->db->where('id', $id_pengajuan_pemohon);
        $this->db->update('pengajuan_pemohon', $data_pengajuan_pemohon);
        
    }


    /**
     * Method untuk mengubah status pengajuan
     * @return void
     */
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