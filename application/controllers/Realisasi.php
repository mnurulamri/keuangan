<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realisasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper('tanggal_helper');
        $this->load->helper('status_helper');
		$this->load->helper('url');
        $this->load->helper('menu_helper');
		$this->load->library('session');
		$this->load->library('Ajax_pagination_realisasi');
		$this->perPage = 2;

        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() {
        
        
        $data['title'] = 'Daftar Rekap Realisasi UMKO';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('unit_kerja/realisasi-ajax-index', $data);        
        $this->load->view('template/footer');
    }

    public function add()
    {

        $this->load->database();

        // get id
        $id = $this->input->post('id');

        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        // get nomor_pengajuan
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');
        if (!$nomor_pengajuan) {
            // If no nomor_pengajuan is provided, redirect to the index page
            //redirect('realisasi');
        }

        $data['id'] = $id; $data['nomor_pengajuan'] = $nomor_pengajuan;  
        $sql = "SELECT * FROM pengajuan_rincian WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();

        $data['sql'] = $sql;
        $data['result'] = $result;
        $data['nominal_pengajuan'] = $this->input->post('nominal_pengajuan');
        //$this->load->view('unit_kerja/realisasi_add', $data); exit();

        // Load the form for creating a new realisasi

		$this->load->view('unit_kerja/realisasi_add', $data);
    }

    // Function to save the realisasi data
    // This function will be called when the form is submitted 
    public function simpan()
    {
        // ambil data kegiatan dari form realisasi_add.php
        $id_pengajuan_rincian = $this->input->post('id_pengajuan_rincian');
        $data_kegiatan = $this->input->post('data_kegiatan'); // Assuming data_kegiatan is an array of data
        foreach ($data_kegiatan as $key => $value) {
            // Process each data_kegiatan item
            // tambahkan addslashes untuk menghindari error dan security
            $data_kegiatan[$key] = addslashes($value);            
        }        

        //print_r($data_kegiatan); // Debugging output

        // update data pengajuan_rincian
        $this->db->where('id', $id_pengajuan_rincian);
        $this->db->update('pengajuan_rincian', $data_kegiatan);
        // You can also log or echo the SQL query for debugging
        //echo $this->db->last_query(); // Debugging output
        //echo $this->db->get_compiled_update('pengajuan_rincian'); // Debugging output
        $sql = $this->db->set($data_kegiatan)->where('id', $id_pengajuan_rincian)->get_compiled_update('pengajuan_rincian');
        //echo $sql; // Debugging output


        // ambil data rincian realisasi dari form realisasi_add.php
        $rincian = $this->input->post('rincian'); // Assuming rincian is an array of data
        if (is_array($rincian)) {
            foreach ($rincian as $key => $value) {
                // Process each rincian item
                // For example, you can save it to the database or perform other actions
                // $data['rincian'][$key] = $value; // Example of processing
                //print_r($value); // Debugging output
                //jika ada tanggal, ubah formatnya ke format Y-m-d

                if (isset($value['tanggal']) && !empty($value['tanggal'])) {
                    $value['tanggal'] = $this->tanggalToDb($value['tanggal']);
                }

                // push tanggal sistem ke setiap item di $rincian



                // $value['tanggal_sistem'] = date('Y-m-d H:i:s'); // Format tanggal sistem
                // tambahkan tanggal sistem ke setiap item di $rincian
                // tambahkan tanggal sistem ke setiap item di $rincian
                $value['tgl_sistem'] = date('Y-m-d H:i:s');

                // insert ke tabel realisasi
                // tambahkan addslashes untuk menghindari error dan security
                //$value = array_map('addslashes', $value); // Escape all values in the array
                // You can also log or echo the SQL query for debugging
                //echo $this->db->set($value)->get_compiled_insert('realisasi'); // Debugging output
                // Insert the data into the 'realisasi' table
                print_r($value); // Debugging output
                // Use the set method to prepare the data for insertion
                // and then insert it into the 'realisasi' table
                // Note: Make sure the 'realisasi' table exists and has the appropriate columns
                //echo $this->db->set($value)->get_compiled_insert('realisasi'); // Debugging output
                // Use the set method to prepare the data for insertion
                $this->db->set($value)->insert('realisasi');
                // You can also log or echo the SQL query for debugging
                //echo $this->db->last_query(); // Debugging output
                //echo $sql = $this->db->set($value)->get_compiled_insert('realisasi');
                
                if (isset($value['bruto'])) {
                    if (!isset($total_bruto)) {
                        $total_bruto = 0;
                    }
                    $total_bruto += floatval($value['bruto']);
                }
            }
        }
        //print_r($rincian); // Debugging output
        // Save the main realisasi data
        //$rincian = array();
        // Redirect to the index page after saving
        //redirect('realisasi');

        // update field realisasi_umko pada tabel monitoring dengan nilai $total_bruto
        // dapatkan id_pengajuan_pemohon dari id_pengajuan_rincian
        $id_pengajuan_pemohon = $this->db->select('id_pengajuan_pemohon')
            ->from('pengajuan_rincian')
            ->where('id', $id_pengajuan_rincian)
            ->get()
            ->row()
            ->id_pengajuan_pemohon;
            
        // update field realisasi_umko pada tabel monitoring dengan nilai $total_bruto
        $this->db->set('realisasi_umko', $total_bruto);
        $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
        $this->db->update('monitoring');
    }

    public function edit()
    {
		// get id
        $id = $this->input->post('id');
        
        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        // get nomor_pengajuan
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');
        if (!$nomor_pengajuan) {
            // If no nomor_pengajuan is provided, redirect to the index page
            //redirect('realisasi');
        }

        $data['id'] = $id; $data['nomor_pengajuan'] = $nomor_pengajuan;  
        $sql = "SELECT * FROM pengajuan_rincian WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();

        // retrieve realisasi data
        $sql_realisasi = "SELECT * FROM realisasi WHERE id_pengajuan_rincian = ?";
        $query_realisasi = $this->db->query($sql_realisasi, array($id));
        $result_realisasi = $query_realisasi->result_array();

        $data['sql'] = $sql;
        $data['result'] = $result;        
        $data['sql_realisasi'] = $sql_realisasi;
        $data['result_realisasi'] = $result_realisasi;
        $data['nominal_pengajuan'] = $this->input->post('nominal_pengajuan');
        
        // Load the form for creating a new realisasi
		$this->load->view('unit_kerja/realisasi_edit', $data);
    }

    public function view()
    {		
        // get id
        $id = $this->input->post('id');

        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        // get nomor_pengajuan
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');
        if (!$nomor_pengajuan) {
            // If no nomor_pengajuan is provided, redirect to the index page
            //redirect('realisasi');
        }

        $data['id'] = $id; $data['nomor_pengajuan'] = $nomor_pengajuan;  
        $sql = "SELECT * FROM pengajuan_rincian WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();

        $data['sql'] = $sql;
        $data['result'] = $result;
        
        // ambil rincian realisasi
        $sql_realisasi = "SELECT * FROM realisasi WHERE id_pengajuan_rincian = ?";
        $query_realisasi = $this->db->query($sql_realisasi, array($id));
        $result_realisasi = $query_realisasi->result_array();
        $data['sql_realisasi'] = $sql_realisasi;
        $data['result_realisasi'] = $result_realisasi;

        // Load the form for creating a new realisasi

		$this->load->view('unit_kerja/realisasi_view', $data);
    }

    public function deleteRincianBiaya()
    {
		// set id
        $id = $this->input->post('id');

        // delete baris data pengajuan pada tabel realisasi
        $this->db->where('id', $id);
        $this->db->delete('realisasi');
    }

    function editRincianBiaya()
    {
        //echo '<pre>';print_r($_POST); exit();// Debugging output
        $id_pengajuan_rincian = $this->input->post('id_pengajuan_rincian');
        $data_kegiatan = $this->input->post('data_kegiatan'); // Assuming data_kegiatan is an array of data
        foreach ($data_kegiatan as $key => $value) {
            // Process each data_kegiatan item
            // tambahkan addslashes untuk menghindari error dan security
            $data_kegiatan[$key] = addslashes($value);            
        }
        
        // update data pengajuan_rincian
        $this->db->where('id', $id_pengajuan_rincian);
        $this->db->update('pengajuan_rincian', $data_kegiatan);
        // You can also log or echo the SQL query for debugging
        $sql = $this->db->set($data_kegiatan)->where('id', $id_pengajuan_rincian)->get_compiled_update('pengajuan_rincian');
        //echo $sql; // Debugging output

        // ambil data rincian realisasi dari form realisasi_edit.php
        $rincian = $this->input->post('rincian'); // Assuming rincian is an array of data
        if (is_array($rincian)) {
            foreach ($rincian as $key => $value) {
                // cek $value['id'] apakah ada
                if (isset($value['id']) && !empty($value['id']) && $value['id'] != '99999999999') {
                    // Edit data (update)
                    $id_realisasi = $value['id'];
                    unset($value['id']); // Remove id from update data
                    if (isset($value['tanggal']) && !empty($value['tanggal'])) {
                        $value['tanggal'] = $this->tanggalToDb($value['tanggal']);
                    }
                    $value['tgl_sistem'] = date('Y-m-d H:i:s');
                    $this->db->where('id', $id_realisasi);
                    $this->db->update('realisasi', $value);
                } else {
                    // Insert data (insert)
                    if (isset($value['tanggal']) && !empty($value['tanggal'])) {
                        $value['tanggal'] = $this->tanggalToDb($value['tanggal']);
                    }
                    $value['tgl_sistem'] = date('Y-m-d H:i:s');
                    $value['id'] = null; 
                    
                    // Use the set method to prepare the data for insertion
                    $this->db->set($value)->insert('realisasi');
                    // You can also log or echo the SQL query for debugging
                    //echo $this->db->last_query(); // Debugging output
                    //echo $sql = $this->db->set($value)->get_compiled_insert('realisasi');
                    //echo $sql; // Debugging output
                }

            }
        }

        // update field realisasi_umko pada tabel monitoring dengan nilai $total_bruto
        // dapatkan id_pengajuan_pemohon dari id_pengajuan_rincian
        $id_pengajuan_pemohon = $this->db->select('id_pengajuan_pemohon')
            ->from('pengajuan_rincian')
            ->where('id', $id_pengajuan_rincian)
            ->get()
            ->row()
            ->id_pengajuan_pemohon;
            
        // update field realisasi_umko pada tabel monitoring dengan nilai $total_bruto
        $this->db->set('realisasi_umko', $total_bruto);
        $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
        $this->db->update('monitoring');
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