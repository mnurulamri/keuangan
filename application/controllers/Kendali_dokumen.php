<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kendali_dokumen extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->database();
		$this->load->helper('url');
		$this->load->helper('status_helper');        
		$this->load->helper('tanggal_helper');
        $this->load->library('session');
    }

    public function index()
    {
        $id_monitoring = $this->input->post('id_monitoring');
        $kd_pengajuan = $this->input->post('kd_pengajuan');
        $username = $this->session->userdata['logged_anggaran']['username'];
        $role = $this->session->userdata['logged_anggaran']['role'];

        $tanggal = $this->hariTanggalToDb($this->input->post('tanggal')) . ' ' . $this->input->post('waktu');
        $keterangan = $this->input->post('keterangan');
        $kode_status = $this->input->post('kode_status');        
        $status = nama_status($kode_status);

        // jika kode_status = 21, maka cek ke database, jika kode_status sebelumnya adalah 66, maka ubah kode_status menjadi 67
        if ($kode_status == 21) {
            $this->db->where('id_monitoring', $id_monitoring);
            $this->db->where('kode_status', 66);
            $query = $this->db->get('kendali_dokumen');
            if ($query->num_rows() > 0) {
                $kode_status = 67;
                $status = nama_status($kode_status);
            } else {
                $kode_status = 21;
                $status = nama_status($kode_status);
            }
        }

        $data = array(
            'id_monitoring' => $id_monitoring,
            'nomor_pengajuan' => $kd_pengajuan,
            'username' => $username,
            'role' => $role,
            'tanggal' => $tanggal,
            'catatan' => addslashes($keterangan),
            'kode_status' => $kode_status,            
            'status' => $status
        );
        //print_r($data);
        // Simpan logika penyimpanan data ke database di sini
        $this->db->insert('kendali_dokumen', $data);
        echo ' - Data telah disimpan ke database.';
    }

    public function pum()
    {
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $nama_form = $this->input->post('nama_form');
        $tgl_diajukan = $this->input->post('tgl_diajukan');
        $username = $this->session->userdata['logged_anggaran']['username'];
        $role = $this->session->userdata['logged_anggaran']['role'];
        // cari id_monitoring, nomor_pengajuan, kode status dan nama form berdasarkan id_pengajuan_pemohon
        $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
        $query = $this->db->get('monitoring');
        $row = $query->row();
        $id_monitoring = $row->id;
        $nomor_pengajuan = $row->nomor_pengajuan;
        $kode_status = $row->kode_status;
        $nama_form = $row->form;

        // tentukan tanggal dan catatan berdasarkan kode status
        if($kode_status == 10 || $kode_status == 12 || $kode_status == 51 || $kode_status == 52 || $kode_status == 62 || $kode_status == 63 ||$kode_status == 65 || $kode_status == 66){ // jika statusnya ditolak anggaran maka update ke 1 (ajukan ulang)
            
            $tanggal = date('Y-m-d H:i:s');
            $catatan = 'Ajukan perbaikan';
        } else if($kode_status == 44) {
            $tanggal = tanggalToDb($tgl_diajukan);
            $catatan = 'Ajukan SPJ';        
        } else if($kode_status == 67) {
            $tanggal = tanggalToDb($tgl_diajukan);
            $catatan = 'Ajukan SPJ (perbaikan)';
        } else {            
            $tanggal = $this->tanggalToDb($tgl_diajukan);
            $catatan = 'Ajukan permohonan';
        }

        $status = nama_status($kode_status);
        
        $data = array(
            'id_monitoring' => $id_monitoring,
            'nomor_pengajuan' => $nomor_pengajuan,
            'username' => $username,
            'role' => $role,
            'tanggal' => $tanggal,
            'catatan' => $catatan,
            'kode_status' => $kode_status,            
            'status' => $status
        );
        //print_r($data); exit();
        // Simpan logika penyimpanan data ke database di sini
        $this->db->insert('kendali_dokumen', $data);
        echo ' - Data telah disimpan ke database.';
    }

    public function fetch_logs()
    {
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');
        $tgl_terima = $this->input->post('tgl_terima');
        $unit_pemohon = $this->input->post('unit_pemohon');
        $uraian = $this->input->post('uraian');
        $nominal_pengajuan = $this->input->post('nominal_pengajuan');
        $no_pp = $this->input->post('no_pp');
        
        // hitung total komitmen dari dari tabel pengajuan_rincian berdasarkan nomor_pengajuan untuk disimpan dalam variabel $nominal_pengajuan
        $sql = "SELECT SUM(komitmen) as komitmen FROM pengajuan_rincian WHERE id_pengajuan_pemohon IN (
                SELECT id_pengajuan_pemohon FROM monitoring WHERE nomor_pengajuan = '$nomor_pengajuan')";
        $query = $this->db->query($sql);
        $nominal_pengajuan = number_format($query->row()->komitmen);

        $this->db->where('nomor_pengajuan', $nomor_pengajuan);
        $query = $this->db->get('kendali_dokumen');
        $logs = $query->result_array();
        
        $this->load->view('kendali_dokumen', [
            'logs' => $logs,
            'nomor_pengajuan' => $nomor_pengajuan,
            'tgl_terima' => $tgl_terima,
            'unit_pemohon' => $unit_pemohon,
            'uraian' => $uraian,
            'nominal_pengajuan' => $nominal_pengajuan,
            'nomor_pp' => $no_pp,
            'logs' => $logs
        ]);

        // testing aja
        /*$this->load->view('kendali_dokumen_timeline', [
            'nomor_pengajuan' => $nomor_pengajuan,
            'tgl_terima' => $tgl_terima,
            'unit_pemohon' => $unit_pemohon,
            'uraian' => $uraian,
            'nominal_pengajuan' => $nominal_pengajuan,
            'nomor_pp' => $no_pp,
            'logs' => $logs
        ]);*/
    }

    public function fetch_logs_user_request()
    {
        // jika nomor_pengajuan tidak ditemukan di kendali_dokumen, tetap tampilkan halaman dengan data kosong
        if (!$this->input->post('nomor_pengajuan') or $this->input->post('nomor_pengajuan') == '-') {
            $this->load->view('kendali_dokumen', [
                'nomor_pengajuan' => '',
                'tgl_terima' => '',
                'unit_pemohon' => '',
                'uraian' => '',
                'nominal_pengajuan' => 0,
                'nomor_pp' => '',
                'logs' => []
            ]);
            return;
        } else {

            $nomor_pengajuan = $this->input->post('nomor_pengajuan');
            $unit_pemohon = $this->input->post('unit_pemohon');

            // bagian kepala dokumen diambil dari monitoring
            $this->db->select('tgl_terima, anggaran_tgl_disetujui,uraian, nominal_pengajuan, no_pp');
            $this->db->where('nomor_pengajuan', $nomor_pengajuan);
            $query_monitoring = $this->db->get('monitoring');        
            $monitoring_data = $query_monitoring->row_array();
            
            $tgl_terima = $monitoring_data['anggaran_tgl_disetujui'];
            $uraian = $monitoring_data['uraian'];
            $nominal_pengajuan = $monitoring_data['nominal_pengajuan'];
            $no_pp = $monitoring_data['no_pp'];

            // hitung total komitmen dari dari tabel pengajuan_rincian berdasarkan nomor_pengajuan untuk disimpan dalam variabel $nominal_pengajuan
            $sql = "SELECT SUM(komitmen) as komitmen FROM pengajuan_rincian WHERE id_pengajuan_pemohon IN (
                    SELECT id_pengajuan_pemohon FROM monitoring WHERE nomor_pengajuan = '$nomor_pengajuan')";
            $query = $this->db->query($sql);
            $nominal_pengajuan = number_format($query->row()->komitmen);
            
            // rincian catatan diambil dari kendali_dokumen
            $this->db->where('nomor_pengajuan', $nomor_pengajuan);
            $query = $this->db->get('kendali_dokumen');
            $logs = $query->result_array();
            
            // jika nomor pengajuan tidak ditemukan di kendali_dokumen, tetap tampilkan halaman dengan data kosong
            if(empty($logs)) {
                $logs = [];
            } else {
                $this->load->view('kendali_dokumen', [
                    'nomor_pengajuan' => $nomor_pengajuan,
                    'tgl_terima' => $tgl_terima,
                    'unit_pemohon' => $unit_pemohon,
                    'uraian' => $uraian,
                    'nominal_pengajuan' => $nominal_pengajuan,
                    'nomor_pp' => $no_pp,
                    'logs' => $logs
                ]);
            }
        }

    }
    
    public function invoice(){
        //echo '<pre>'; print_r($this->input->post());echo '</pre>';exit();   
        //$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $no_invoice_pp = $this->input->post('no_invoice_pp');
        $uraian = $this->input->post('uraian');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $tgl = $this->input->post('tgl');
        $username = $this->session->userdata['logged_anggaran']['username'];
        $role = $this->session->userdata['logged_anggaran']['role'];
        
        $kode_status = 71;
        $status = nama_status($kode_status);
        
        $catatan = $this->input->post('keterangan');
        
        // 1. Ubah string "1025,1024" menjadi array PHP
        $id_array = explode(',', $id_pengajuan_pemohon);

        // 2. Gunakan Query Builder / Compile Bindings agar lebih aman
        $this->db->select('id_monitoring, id_pengajuan_pemohon, nomor_pengajuan');
        $this->db->from('view_search_pengajuan');
        $this->db->where_in('id_pengajuan_pemohon', $id_array);
        $query = $this->db->get();
        $result = $query->result_array();
        
        // 3. Inisialisasi array kosong terlebih dahulu
        $array = array(); 
        
        if (!empty($result)) {
            foreach ($result as $row) {
                $array[] = array(
                    //'id_pengajuan_pemohon' => $row['id_pengajuan_pemohon'],
                    'nomor_pengajuan'      => $row['nomor_pengajuan'],
                    'username'             => $username,
                    'role'                 => $role,
                    'tanggal'              => date('Y-m-d H:i:s'),
                    'catatan'              => $catatan,
                    'id_monitoring'        => $row['id_monitoring'],
                    'kode_status'          => $kode_status,        
                    'status'               => $status
                    // Catatan: Jika 'id_monitoring' memicu duplicate key, pastikan strukturnya di database bukan PRIMARY KEY
                );
            }
            
            // 4. Jalankan insert hanya jika array ada isinya
            $this->db->insert_batch('kendali_dokumen', $array);
            
        } else {
            // Log atau tangani jika data pemohon tidak ditemukan
            log_message('error', 'Insert batch gagal: Data view_search_pengajuan kosong.');
        }
         //echo '<pre>'; print_r($array);echo '</pre>';exit();
        
    }

    

    public function invoice_pp(){

        //echo '<pre>'; print_r($this->input->post());echo '</pre>';exit();   

        //$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');

        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');

        $no_invoice_pp = $this->input->post('no_invoice_pp');

        $tgl_invoice_pp = $this->input->post('tgl_invoice_pp');

        $username = $this->session->userdata['logged_anggaran']['username'];

        $role = $this->session->userdata['logged_anggaran']['role'];

        

        $aksi_pp = $this->input->post('aksi_pp');

        

        if($aksi_pp == 'mdk'){

            $kode_status = 72;

            $status = nama_status($kode_status);

            $catatan = 'Input MDK [{'.$no_invoice_pp.'}, {'.$tgl_invoice_pp.'}]';

        } else {

            $kode_status = 73;

            $status = nama_status($kode_status);

            $catatan = 'Buat PP [{'.$no_invoice_pp.'}, {'.$tgl_invoice_pp.'}]';

        }

        // 1. Ubah string "1025,1024" menjadi array PHP

        $id_array = explode(',', $id_pengajuan_pemohon);



        // 2. Gunakan Query Builder / Compile Bindings agar lebih aman

        $this->db->select('id_monitoring, id_pengajuan_pemohon, nomor_pengajuan');

        $this->db->from('view_search_pengajuan');

        $this->db->where_in('id_pengajuan_pemohon', $id_array);

        $query = $this->db->get();

        $result = $query->result_array();

        

        // 3. Inisialisasi array kosong terlebih dahulu

        $array = array(); 

        

        if (!empty($result)) {

            foreach ($result as $row) {

                $array[] = array(

                    //'id_pengajuan_pemohon' => $row['id_pengajuan_pemohon'],

                    'nomor_pengajuan'      => $row['nomor_pengajuan'],

                    'username'             => $username,

                    'role'                 => $role,

                    'tanggal'              => date('Y-m-d H:i:s'),

                    'catatan'              => $catatan,

                    'id_monitoring'        => $row['id_monitoring'],

                    'kode_status'          => $kode_status,        

                    'status'               => $status

                    // Catatan: Jika 'id_monitoring' memicu duplicate key, pastikan strukturnya di database bukan PRIMARY KEY

                );

            }

            

            // 4. Jalankan insert hanya jika array ada isinya

            $this->db->insert_batch('kendali_dokumen', $array);

            

        } else {

            // Log atau tangani jika data pemohon tidak ditemukan

            log_message('error', 'Insert batch gagal: Data view_search_pengajuan kosong.');

        }

         echo '<pre>'; print_r($array);echo '</pre>';exit();

        

    }
    
    public function procost(){
        //echo '<pre>'; print_r($this->input->post());echo '</pre>';exit();   
        //$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $no_invoice_pp = $this->input->post('no_invoice_pp');
        $uraian = $this->input->post('uraian');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $tgl = $this->input->post('tgl');
        $username = $this->session->userdata['logged_anggaran']['username'];
        $role = $this->session->userdata['logged_anggaran']['role'];
        
        $kode_status = 70;
        $status = nama_status($kode_status);
        
        $catatan = 'Buat Invoice PP No: '.$no_invoice_pp.' Tanggal: '.$tgl.'-'.$bulan.'-'.$tahun;
        
        // 1. Ubah string "1025,1024" menjadi array PHP
        $id_array = explode(',', $id_pengajuan_pemohon);

        // 2. Gunakan Query Builder / Compile Bindings agar lebih aman
        $this->db->select('id_monitoring, id_pengajuan_pemohon, nomor_pengajuan');
        $this->db->from('view_search_pengajuan');
        $this->db->where_in('id_pengajuan_pemohon', $id_array);
        $query = $this->db->get();
        $result = $query->result_array();
        
        // 3. Inisialisasi array kosong terlebih dahulu
        $array = array(); 
        
        if (!empty($result)) {
            foreach ($result as $row) {
                $array[] = array(
                    //'id_pengajuan_pemohon' => $row['id_pengajuan_pemohon'],
                    'nomor_pengajuan'      => $row['nomor_pengajuan'],
                    'username'             => $username,
                    'role'                 => $role,
                    'tanggal'              => date('Y-m-d H:i:s'),
                    'catatan'              => $catatan,
                    'id_monitoring'        => $row['id_monitoring'],
                    'kode_status'          => $kode_status,        
                    'status'               => $status
                    // Catatan: Jika 'id_monitoring' memicu duplicate key, pastikan strukturnya di database bukan PRIMARY KEY
                );
            }
             //echo '<pre>'; print_r($array);echo '</pre>';exit();
            // 4. Jalankan insert hanya jika array ada isinya
            $this->db->insert_batch('kendali_dokumen', $array);
            
        } else {
            // Log atau tangani jika data pemohon tidak ditemukan
            log_message('error', 'Insert batch gagal: Data view_search_pengajuan kosong.');
        }
         echo '<pre>'; print_r($array);echo '</pre>';exit();
        
    }

    public function korpum_invoice()
    {
        //echo '<pre>'; print_r($this->input->post());echo '</pre>';exit();
        $id_monitoring = $this->input->post('id_monitoring');
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');
        $username = $this->session->userdata['logged_anggaran']['username'];
        $role = $this->session->userdata['logged_anggaran']['role'];

        $tanggal = $this->hariTanggalToDb($this->input->post('invoice_tanggal')) . ' ' . $this->input->post('invoice_waktu');
        $keterangan = $this->input->post('keterangan');
        $kode_status = $this->input->post('kode_status');        
        $status = nama_status($kode_status);

        $data = array(
            'id_monitoring' => $id_monitoring,
            'nomor_pengajuan' => $nomor_pengajuan,
            'username' => $username,
            'role' => $role,
            'tanggal' => $tanggal,
            'catatan' => addslashes($keterangan),
            'kode_status' => $kode_status,            
            'status' => $status
        );
        //echo '<pre>'; print_r($data);echo '</pre>';exit();
        // Simpan logika penyimpanan data ke database di sini
        $this->db->insert('kendali_dokumen', $data);
        echo ' - Data telah disimpan ke database.';
    }

	public function hariTanggalToDb($tgl_kegiatan)
	{
		$bulan = array('Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember');
		$tgl_array = explode(" ", $tgl_kegiatan);
		$d = $tgl_array[1];
		$month = array_search($tgl_array[2], $bulan)+1;
		$m = (strlen($month)==2) ? $month : '0'.$month; 
		$y = $tgl_array[3];
		$tgl = $y."-".$m."-".$d;
		$tgl_kegiatan = $tgl;
		return $tgl;
	}

	public function dateTimeToTanggalWaktu($parameter){
		$_tanggal = explode(' ', $parameter);
		$tanggal = $_tanggal[0];
		$array = explode('-', $tanggal);
		//set tanggal
	    $d = $array[2];
	    $m = $array[1];
	    $y = $array[0];
		//set hari
		$nama_hari = array( '0' => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu' );
		$kd_hari = date("w", mktime(0, 0, 0, $m, $d, $y));
		$hari = $nama_hari[$kd_hari];
		//set bulan
		$nama_bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
		$bulan = $nama_bulan[$m];
	    $tanggal = $hari.', '.$d.' '.$bulan.' '.$y.'<br>'.$_tanggal[1];
	    return $tanggal;
	}
    
	public function tanggalToDb($tgl_kegiatan)
	{
		$bulan = array('Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember');
		$tgl_array = explode(" ", $tgl_kegiatan);
		$d = $tgl_array[0];
		$month = array_search($tgl_array[1], $bulan)+1;
		$m = (strlen($month)==2) ? $month : '0'.$month; 
		$y = $tgl_array[2];
		$tgl = $y."-".$m."-".$d." 00:00:00";
		$tgl_kegiatan = $tgl;
		return $tgl;
	}
}