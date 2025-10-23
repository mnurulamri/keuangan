<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
        $this->load->helper('menu_helper');
        $this->load->helper('tanggal_helper');
		$this->load->library('session');

        // Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Monitoring Anggaran';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('kasir/monitoring-ajax-index', $data);        
        $this->load->view('template/footer');
    }

    public function konfirmasiApproval() {	
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        //$kode_bidang = $this->session->userdata('kode_bidang');
        
        // set kode_bidang melalui kode_dpsj dari input POST
        $kode_dpsj = $this->input->post('kode_dpsj');
        $sql_kode_bidang = "SELECT kode_bidang FROM unit_kerja WHERE kode_dpsj = ?";        
        $query = $this->db->query($sql_kode_bidang, array($kode_dpsj));
        $result = $query->row_array();
        $kode_bidang = $result['kode_bidang'];        
        $data['kode_bidang'] = $kode_bidang;        
        
        // Load model untuk mendapatkan data unit
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();

        // ambil id pengajuan dari input POST
        $id_monitoring = $this->input->post('id_monitoring');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
        $data['id_monitoring'] = $id_monitoring;
        
        if (!$id_pengajuan_pemohon) {
            show_error('Nomor pengajuan tidak ditemukan.');
            return;
        }

        // ambil data tanggal dari database
        $sql = "SELECT * FROM pengajuan_pemohon WHERE id = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $result = $query->row_array();
        if (!$result) {
            show_error('Data pengajuan tidak ditemukan.');
            return;
        }
        $data['tanggal'] = $result['tanggal'];
        $data['nomor_pengajuan'] = $result['nomor_pengajuan'];
        $data['preview_nomor'] = $result['nomor_pengajuan'];
        $data['untuk'] = $result['untuk'];

        // siapkan data pejabat
        $data_pejabat[] = array(
            'nip' => $result['nip'],
            'nama' => $result['penanggung_jawab'],
            'telp' => $result['telp'],
            'tgl_diajukan' => $result['tgl_diajukan']
        );
        
        // ambil data jabatan pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT jabatan FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$kode_bidang' ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data_pejabat[] = array(
                'jabatan' => $row['jabatan']
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

        // ambil data dari tabel pengajuan_rincian berdasarkan id_pengajuan_pemohon
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

        // ambil data anggaran_tgl_disetujui, anggaran_keterangan dari tabel monitoring berdasarkan id_pengajuan pemohon
        $sql = "SELECT * FROM monitoring WHERE id_pengajuan_pemohon = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $monitoring = $query->row_array();
        $data['kasir_tgl_disetujui'] = $monitoring['kasir_tgl_disetujui'] ?? null;
        $data['kasir_penerima'] = $monitoring['kasir_penerima'] ?? null;
        $data['kasir_keterangan'] = $monitoring['kasir_keterangan'] ?? null;
        $data['nominal_umko_cair'] = $monitoring['nominal_umko_cair'] ?? '0';
        $data['nominal_disetujui_umko'] = $monitoring['nominal_disetujui_umko'] ?? '0';

        $this->load->view('kasir/monitoring_konfirmasi',$data);
    }

    public function approval() {
        $id_monitoring = $this->input->post('id_monitoring');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $nominal_umko_cair = $this->input->post('nominal_umko_cair');    
        $nominal_umko_cair = preg_replace('/\D/', '', $nominal_umko_cair);
        $kasir_penerima = $this->input->post('kasir_penerima');
        $kasir_keterangan = $this->input->post('kasir_keterangan');
        $kasir_tgl_disetujui = date('Y-m-d H:i:s');
        $username = $this->session->userdata('logged_anggaran')['username'] ?? '';
        
        // Update data monitoring
        $data = array(
            'nominal_umko_cair' => $nominal_umko_cair,
            'kasir_penerima' => addslashes($kasir_penerima),
            'kasir_keterangan_disetujui' => addslashes($kasir_keterangan),
            'kasir_tgl_disetujui' => $kasir_tgl_disetujui,
            'kasir_username' => $username,  // disetuijui oleh
            'kode_status' => '41', // Set status ke '31' untuk disetujui kasir
        );        
        print_r($data);
        // update tabel monitoring
        $sql = $this->db->set($data)->where('id', $id_monitoring)->update('monitoring');

        // update kode_status pada tabel pengajuan_pemohon
        $data_pengajuan = array(
            'kode_status' => '41'
        );
        $this->db->where('id', $id_pengajuan_pemohon);
        $this->db->update('pengajuan_pemohon', $data_pengajuan);

        /*// update tabel pengajuan_rincian
        if (is_array($array_jumlah_disetujui)) {
            foreach ($array_jumlah_disetujui as $key => $row) {
                $sql = "UPDATE pengajuan_rincian SET komitmen_disetujui = ? WHERE id = ?";
                $this->db->query($sql, array($row['jumlah_disetujui'], $row['id']));
            }
        }
        // cek apakah update berhasil
        if ($this->db->affected_rows() > 0) {
            echo json_encode(array('status' => 'success', 'message' => 'Data berhasil diperbarui.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Gagal memperbarui data.'));
        }*/
    }
    

    public function detailApproval() {
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        //$kode_bidang = $this->session->userdata('kode_bidang');
        
        // set kode_bidang melalui kode_dpsj dari input POST
        $kode_dpsj = $this->input->post('kode_dpsj');
        $sql_kode_bidang = "SELECT kode_bidang FROM unit_kerja WHERE kode_dpsj = ?";        
        $query = $this->db->query($sql_kode_bidang, array($kode_dpsj));
        $result = $query->row_array();
        $kode_bidang = $result['kode_bidang'];        
        $data['kode_bidang'] = $kode_bidang;        
        
        // Load model untuk mendapatkan data unit
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();

        // ambil id pengajuan dari input POST
        $id_monitoring = $this->input->post('id_monitoring');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
        $data['id_monitoring'] = $id_monitoring;
        
        if (!$id_pengajuan_pemohon) {
            show_error('Nomor pengajuan tidak ditemukan.');
            return;
        }

        // ambil data tanggal dari database
        $sql = "SELECT * FROM pengajuan_pemohon WHERE id = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $result = $query->row_array();
        if (!$result) {
            show_error('Data pengajuan tidak ditemukan.');
            return;
        }
        $data['tanggal'] = $result['tanggal'];
        $data['nomor_pengajuan'] = $result['nomor_pengajuan'];
        $data['preview_nomor'] = $result['nomor_pengajuan'];
        $data['untuk'] = $result['untuk'];

        // siapkan data pejabat
        $data_pejabat[] = array(
            'nip' => $result['nip'],
            'nama' => $result['penanggung_jawab'],
            'telp' => $result['telp'],
            'tgl_diajukan' => $result['tgl_diajukan']
        );
        
        // ambil data jabatan pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT jabatan FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$kode_bidang' ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data_pejabat[] = array(
                'jabatan' => $row['jabatan']
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

        // ambil data dari tabel pengajuan_rincian berdasarkan id_pengajuan_pemohon
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

        // ambil data anggaran_tgl_disetujui, anggaran_keterangan dari tabel monitoring berdasarkan id_pengajuan pemohon
        $sql = "SELECT * FROM monitoring WHERE id_pengajuan_pemohon = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $monitoring = $query->row_array();
        $data['kasir_penerima'] = $monitoring['kasir_penerima'] ?? null;
        $data['kasir_tgl_disetujui'] = $monitoring['kasir_tgl_disetujui'] ?? null;
        $data['kasir_keterangan'] = $monitoring['kasir_keterangan_disetujui'] ?? null;
        $data['nominal_umko_cair'] = $monitoring['nominal_umko_cair'] ?? '0';

        $this->load->view('kasir/monitoring_detail',$data);
    }

    public function simpanTglPenyerahanSpj()
    {
        $id = $this->input->post('id');
        $tgl_penyerahan_spj_umko = $this->input->post('tgl_penyerahan_spj_umko');
        
        // Validasi input tanggal
        if (!$id || !$tgl_penyerahan_spj_umko) {
            echo 'ID atau tanggal tidak valid.';
            return;
        }

        // Format tanggal ke format database
        $tgl_penyerahan_spj_umko = $this->tanggalToDb($tgl_penyerahan_spj_umko);

        // Update tanggal penyerahan SPJ
        $data = array(
            'tgl_penyerahan_spj_umko' => $tgl_penyerahan_spj_umko
        );

        $this->db->where('id', $id);
        $this->db->update('monitoring', $data);
    }
    

    function tanggalToDb($tgl_kegiatan)
	{
		$bulan = array('Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember');
		$tgl_array = explode(" ", $tgl_kegiatan);
		$d = $tgl_array[0];
		$month = array_search($tgl_array[1], $bulan)+1;
		$m = (strlen($month)==2) ? $month : '0'.$month; 
		$y = $tgl_array[2];
		$tgl = $y."-".$m."-".$d;
		$tgl_kegiatan = $tgl;
		return $tgl;
	}

    function dbToTanggal($tanggal)
	{
		if ($tanggal=='0000-00-00') {
			$tanggal = '';
		} else {
			$array = explode('-', $tanggal);
			//set tanggal
	        $d = $array[2];
	        $m = $array[1];
	        $y = $array[0];
			//set hari
			$nama_hari = array( 0 => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu' );
			$kd_hari = date("w", mktime(0, 0, 0, $m, $d, $y));
			$hari = $nama_hari[$kd_hari];
			//set bulan
			$nama_bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
			$bulan = $nama_bulan[$m];
	        $tanggal_hari = $hari.', '.$d.' '.$bulan.' '.$y;
	        $tanggal = $d.' '.$bulan.' '.$y;
		}

        return $tanggal;
	}	
}