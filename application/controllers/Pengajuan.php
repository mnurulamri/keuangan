<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('menu_helper');
        $this->load->helper('status_helper');
        $this->load->library('Ajax_pagination_pengajuan');
        $this->perPage = 2;

        // Load session library
		$this->load->library('session');
        
		// Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function test(){
        $data['title'] = 'test title';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('test', $data);
        $this->load->view('template/footer');
    }

    public function index() {
        
        
        /*$data['title'] = 'Unit Kerja';
        $data['nama'] = 'xxx';

        $unit_kerja = $this->db->get('units')->result_array();
        $data['unit_kerja'] = $unit_kerja;
        
        //$this->load->view('template/header', $data);
        $this->load->view('anggaran/unit_kerja', $data);*/

        $data['title'] = 'Daftar Pengajuan';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('unit_kerja/pengajuan-ajax-index', $data);        
        $this->load->view('template/footer');
    }

    public function form() {
        $data['title'] = 'Form Pengajuan Dana';
        $data['nama'] = 'test nama';
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();
        
        $data['kode_bidang'] = $this->session->userdata('logged_anggaran')['kode_bidang'];

        // ambil identitas pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT * FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '$data[kode_bidang]' ORDER BY kd_struktur";
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
        }
        $data['array_dpsj'] = $array_dpsj;        
        $data['kode_unit'] = $kode_unit;

        // Generate nomor pengajuan untuk preview
        //$data['preview_nomor'] = $this->Anggaran_model->generate_nomor_pengajuan($kode_unit); // Default kode
        $data['preview_nomor'] = '';

		// tentukan jumlah anggaran yg sudah ditetapkan dari masing2 akun untuk setiap kode_dpsj
		$array_kode_dpsj = "'".implode("','", $array_kode_dpsj)."'";
		
        $sql = "SELECT kode_akun, anggaran FROM rka WHERE kode_dpsj in ($array_kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_anggaran[$row['kode_akun']] = $row['anggaran'];
        }
		$data['array_anggaran'] = $array_anggaran;

		// tentukan total komitmen dari setiap akun
		$array_komitmen = array();
        $sql = "SELECT DISTINCT kode_akun, SUM(komitmen) as komitmen FROM pengajuan_rincian WHERE kode_dpsj in ($array_kode_dpsj) GROUP BY kode_akun";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_komitmen[$row['kode_akun']] = $row['komitmen'];
        }
		$data['array_komitmen'] = $array_komitmen;
		
		// tentukan total realisasi dari setiap akun
		$array_realisasi = array();
        $sql = "SELECT DISTINCT kode_akun, SUM(jumlah) as realisasi FROM realisasi WHERE kode_dpsj in ($array_kode_dpsj) GROUP BY kode_akun";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $array_realisasi[$row['kode_akun']] = $row['realisasi'];
        }
		$data['array_realisasi'] = $array_realisasi;
		
		// tentukan sisa anggaran
		foreach($array_anggaran as $key => $value){
			if(isset($array_komitmen[$key])){
				$komitmen = $array_komitmen[$key];
			} else {
				$komitmen = 0;
			}

			if(isset($array_realisasi[$key])){
				$realisasi = $array_realisasi[$key];
			} else {
				$realisasi = 0;
			}

			if($realisasi > 0){
				$sisa_anggaran = $value - $komitmen + ($komitmen - $realisasi);
			} else {
				$sisa_anggaran = $value - $komitmen;
			}
			//
			
			$array_sisa_anggaran[$key] = array('anggaran'=>$value, 'komitmen'=>$komitmen, 'realisasi'=>$realisasi, 'sisa_anggaran'=>$sisa_anggaran);
		}
		$data['array_sisa_anggaran'] = json_encode($array_sisa_anggaran);
		$data['sql'] = $sql;

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('unit_id', 'Unit', 'required');
        $this->form_validation->set_rules('penanggung_jawab', 'Penanggung Jawab', 'required');
        $this->form_validation->set_rules('nomor_identitas', 'NPM/NIP/NUP', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');
        $this->form_validation->set_rules('untuk_nama', 'Untuk dan Atas Nama', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/tes_menu', array('menu' => menu()) );
            $this->load->view('anggaran/pengajuan', $data);
            $this->load->view('template/footer');
			$this->load->view('anggaran/pengajuan_script');
        } else {
            // Get kode unit
            $unit_id = $this->input->post('unit_id');
            //$unit = $this->Unit_model->get_by_id($unit_id);
            //$unit_kode = $unit ? $unit->kode_unit : 'kom';
            
            // Data Pemohon
            $data_pemohon = array(
                //'nomor_pengajuan' => $this->Anggaran_model->generate_nomor_pengajuan($unit_kode),
                'tanggal' => $this->input->post('tanggal'),
                'unit_id' => $unit_id,
                'penanggung_jawab' => $this->input->post('penanggung_jawab'),
                'nomor_identitas' => $this->input->post('nomor_identitas'),
                'telepon' => $this->input->post('telepon'),
                'untuk_nama' => $this->input->post('untuk_nama'),
                'user_id' => $this->session->userdata('user_id'),
                'status' => 'diajukan',
                'created_at' => date('Y-m-d H:i:s')
            );
            
            // Data Rincian
            $data_rincian = array();
            $project_costings = $this->input->post('project_costing');
            $akuns = $this->input->post('akun');
            $jumlahs = $this->input->post('jumlah');
            $keterangans = $this->input->post('keterangan');
            
            for($i = 0; $i < count($project_costings); $i++) {
                if(!empty($project_costings[$i]) && !empty($akuns[$i]) && !empty($jumlahs[$i])) {
                    $data_rincian[] = array(
                        'project_costing' => $project_costings[$i],
                        'akun' => $akuns[$i],
                        'jumlah' => str_replace(',', '', $jumlahs[$i]),
                        'keterangan' => $keterangans[$i]
                    );
                }
            }
            
            if($this->Anggaran_model->insert_pengajuan($data_pemohon, $data_rincian)) {
                $this->session->set_flashdata('success', 'Pengajuan dana berhasil dikirim');
                redirect('anggaran');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengirim pengajuan dana');
                redirect('pengajuan');
            }
        }
    }

    public function detail($id) {
        $data['title'] = 'Detail Pengajuan Anggaran';
        $data['anggaran'] = $this->Anggaran_model->get_by_id($id);
        
        if (empty($data['anggaran'])) {
            show_404();
        }
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('anggaran/detail', $data);
        $this->load->view('template/footer');
    }

    public function approve($id) {
        if ($this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'keuangan') {
            show_404();
        }
        
        $data = array(
            'status' => 'disetujui',
            'approved_by' => $this->session->userdata('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        );
        
        $this->Anggaran_model->update($id, $data);
        $this->session->set_flashdata('success', 'Pengajuan anggaran telah disetujui');
        redirect('anggaran');
    }

    public function reject($id) {
        if ($this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'keuangan') {
            show_404();
        }
        
        $data = array(
            'status' => 'ditolak',
            'approved_by' => $this->session->userdata('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        );
        
        $this->Anggaran_model->update($id, $data);
        $this->session->set_flashdata('success', 'Pengajuan anggaran telah ditolak');
        redirect('anggaran');
    }

    // Di Anggaran controller
    public function get_kode_unit($unit_id) {
        $this->load->model('Unit_model');
        $unit = $this->Unit_model->get_by_id($unit_id);
        
        $response = array(
            'kode' => $unit ? $unit->kode_unit : 'kom'
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function generate_nomor_pengajuan($kode_unit) {
        $nomor = $this->Anggaran_model->generate_nomor_pengajuan($kode_unit);
        
        $response = array(
            'nomor' => $nomor
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // Tambahkan method baru untuk autocomplete dpsj
	public function search_dpsj() 
	{
		$kata = $this->input->post('kata');
        $kode_bidang = $this->input->post('kode_bidang');

        // ambil kode dpsj berdasarkan kode bidang
        $sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_dpsj = $row['kode_dpsj'];
        }
        

		$sql = "SELECT kode_dpsj, deskripsi_dpsj FROM rka WHERE deskripsi_dpsj LIKE '%$kata%' AND kode_dpsj = '$kode_dpsj' GROUP BY deskripsi_dpsj LIMIT 10";
		$array = $this->db->query($sql);
		//echo $sql; print_r($array_ruang->result_array()); 
		//$array_ruang = $ppf_web_db->select('*')->from('ruang_rapat')->where('nm_ruang')->limit('10')->get();

		if($array->num_rows()>0){
			$kotaksuggest ='
			
			<table class="autocomplete-dpsj" id="test" >
				<tr>
					<th>DESKRIPSI DPSJ</th>
					<th>KODE DPSJ</th>
				</tr>';
				foreach ($array->result_array() as $row){
					$kotaksuggest.= '
					<tr>
						<td class="isi_dpsj">'.$row['deskripsi_dpsj'].'</td>
						<td class="isi_dpsj">'.$row['kode_dpsj'].'</td>
					</tr>';
				}
			
			$kotaksuggest.='</table>';
		} else {
			$kotaksuggest = '';
		}
		echo $kotaksuggest;
	}

    // Tambahkan method baru untuk autocomplete
    public function search_project()
    {
		$kata = $this->input->post('kata');
        $dpsj = $this->input->post('dpsj');
        
		$sql = "SELECT kode_kegiatan, nama_kegiatan FROM rka WHERE kode_dpsj = '$dpsj' AND nama_kegiatan LIKE '%$kata%' GROUP BY nama_kegiatan LIMIT 10";
		$array = $this->db->query($sql);
		//echo $sql; print_r($array_ruang->result_array()); 
		//$array_ruang = $ppf_web_db->select('*')->from('ruang_rapat')->where('nm_ruang')->limit('10')->get();

		if($array->num_rows()>0){
			$kotaksuggest ='
			
			<table class="autocomplete-pc" id="test" >
				<tr>
					<th>KODE KEGIATAN</th>
					<th>NAMA KEGIATAN</th>
				</tr>';
				foreach ($array->result_array() as $row){
					$kotaksuggest.= '
					<tr>
						<td class="isi_pc">'.$row['kode_kegiatan'].'</td>
						<td class="isi_pc">'.$row['nama_kegiatan'].'</td>
					</tr>';
				}
			
			$kotaksuggest.='</table>';
		} else {
			$kotaksuggest = '';
		}
		echo $kotaksuggest;
    }

    public function search_akun() {
        $kata = $this->input->post('kata');
        $kode_kegiatan = $this->input->post('kode_kegiatan');
        
		$sql = "SELECT kode_akun, deskripsi_akun, anggaran FROM rka WHERE kode_kegiatan = '$kode_kegiatan' AND deskripsi_akun LIKE '%$kata%' LIMIT 10";
		$array = $this->db->query($sql);
		//echo $sql; print_r($array_ruang->result_array()); 
		//$array_ruang = $ppf_web_db->select('*')->from('ruang_rapat')->where('nm_ruang')->limit('10')->get();

		if($array->num_rows()>0){
			$kotaksuggest ='
			
			<table class="autocomplete-pc" id="test" >
				<tr>
					<th>KODE AKUN</th>
					<th>DESKRIPSI AKUN</th>
                    <th>ANGGARAN</th>
				</tr>';
				foreach ($array->result_array() as $row){
					$kotaksuggest.= '
					<tr>
						<td class="isi_akun">'.$row['kode_akun'].'</td>
						<td class="isi_akun">'.$row['deskripsi_akun'].'</td>
						<td class="isi_akun">'.number_format($row['anggaran']).'</td>
					</tr>';
				}
			
			$kotaksuggest.='</table>';
		} else {
			$kotaksuggest = '';
		}
		echo $kotaksuggest;
    }

    public function check_anggaran() {
        $kode_kegiatan = $this->input->post('kode_kegiatan');
        $akun = $this->input->post('akun');
        $jumlah = str_replace(['Rp ', '.', ','], '', $this->input->post('jumlah'));
        
        $sisa_anggaran = $this->Rka_model->get_sisa_anggaran($kode_kegiatan, $akun);
        
        $response = array(
            'valid' => ($jumlah <= $sisa_anggaran),
            'sisa_anggaran' => $sisa_anggaran,
            'message' => ($jumlah <= $sisa_anggaran) ? '' : 'Jumlah melebihi sisa anggaran yang tersedia'
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function list_unit_kerja()
    {        
        $data['title'] = 'Form Pengajuan Dana';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('anggaran/list_unit_kerja');        
        $this->load->view('template/footer');
    }

    public function list_unit_anggaran()
    {
        $data['title'] = 'Form Pengajuan Dana';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('anggaran/list_unit_anggaran');        
        $this->load->view('template/footer');
        
    }
    
    public function list_unit_keuangan(){
        $data['title'] = 'Form Pengajuan Dana';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('anggaran/list_unit_keuangan');        
        $this->load->view('template/footer');
    }

    public function list_manajer(){
        $data['title'] = 'Form Pengajuan Dana';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('anggaran/list_unit_manajer');        
        $this->load->view('template/footer');
    }

    public function simpan_rincian()
    {
        // set nama unit kerja
        //print_r($this->input->post()) ;
        $kode_dpsj = $this->input->post('kode_dpsj');
        $nomor_pengajuan = $this->input->post('nomor_pengajuan'); // masih bernilai kosong
        /*$sql = "SELECT kode_unit, nama_unit FROM unit_kerja WHERE kode_dpsj = ?";
        $query = $this->db->query($sql, array($this->input->post('kode_dpsj')));
        $result = $query->result_array();*/
        $sql = "SELECT kode_unit, nama_unit FROM unit_kerja WHERE kode_dpsj = '$kode_dpsj'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach($result as $row){
            $nama_unit = $row['nama_unit'];
            $kode_unit = $row['kode_unit'];
        }
        //print_r($kode_unit);
        
        $data_pejabat = $this->input->post('data_pejabat');
        $data_pejabat[0]['kode_bidang'] = $this->session->userdata('kode_bidang');
        $data_pejabat[0]['nama_unit'] = $nama_unit;
        $data_pejabat[0]['created_at'] = date('Y-m-d H:i:s');

        //print_r($data_pejabat);

        /* dilakukan setelah pengajuan diajukan
        // insert ke tabel monitoring
        $data_monitoring = $this->input->post('data_monitoring');
        
        // set nomor urut
        $nomor_urut = substr($nomor_pengajuan, 0, 3);
        $nomor_urut = (int) $nomor_urut;    
        
        $data_monitoring[0]['nomor_urut'] = $nomor_urut;
        $data_monitoring[0]['nomor_pengajuan'] = $nomor_pengajuan;
        $data_monitoring[0]['form'] = 'D01';
        $data_monitoring[0]['kode_unit'] =  $kode_unit;
        $data_monitoring[0]['kode_dpsj'] =  $kode_dpsj;

        
        foreach($data_monitoring as $key => $value){
            $array_monitoring = $value;
        }

        // testing
        print_r($array_monitoring);
        print_r($this->input->post('data')); 
        //$data = $this->input->post('data');        

        // insert ke tabel monitoring (dilakukan setelah pengajuan disetujui atau ditolak)
        //$this->db->insert('monitoring', $array_monitoring);
        //echo $sql = $this->db->set($array_monitoring)->get_compiled_insert('monitoring');
        */

        // insert ke tabel pengajuan_pemohon        
        foreach($data_pejabat as $key => $value){
            $array_pejabat = $value;
        }
        $this->db->insert('pengajuan_pemohon', $array_pejabat);
        //$sql = $this->db->set($array_pejabat)->get_compiled_insert('pengajuan_pemohon');

        $this->db->insert_id(); // ambil id dari tabel pengajuan_pemohon yang baru saja diinsert
        $id_pengajuan_pemohon = $this->db->insert_id();
        
        // menyisipkan id_monitoring ke dalam data_rincian
        foreach($this->input->post('data') as $key => $value) {            
            $data_rincian[] = $value;
            // sisipkan id_pengajuan_rincian
            $data_rincian[$key]['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
        }
        print_r($data_rincian);
        // insert ke tabel pengjuan rincian
        foreach($data_rincian as $key => $value) {            
            $this->db->insert('pengajuan_rincian', $value);
            //echo $sql = $this->db->set($value)->get_compiled_insert('pengajuan_rincian');
        }
    }

    public function unit_kerja()
    {
        
        $data['title'] = 'Unit Kerja';
        $data['nama'] = 'xxx';

        $unit_kerja = $this->db->get('units')->result_array();
        $data['unit_kerja'] = $unit_kerja;
        
        //$this->load->view('template/header', $data);
        $this->load->view('anggaran/unit_kerja', $data);
    }

    public function set_role_bridge()
	{
        $role_id = $this->input->post('kode_bidang');
        $newdata = array(
            'kode_bidang'  => $role_id
        );

        $this->session->set_userdata($newdata);
        //header("location:".base_url()."Test_autocomplete");
        redirect('pengajuan/form');
    }

    public function daftar()
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
        $result = $query->result_array();

        // ambil data anggaraan dari tabel rka berdasarkan kode_dpsj
        $sql = "SELECT * FROM rka WHERE kode_dpsj IN ($kode_dpsj)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach($result as $row){
            $kode_akun[$row['kode_akun']] = $row['anggaran'];
        }

        $data['rincian'] = $result;
        $data['kode_akun'] = $kode_akun;
        $data['sql'] = $sql;

        $data['title'] = 'Form Pengajuan Dana';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('anggaran/list_unit_kerja', $data);        
        $this->load->view('template/footer');
    }

    public function get_nip_pejabat(){
        $nama = $this->input->post('nama');
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT * FROM pejabat WHERE nama = '$nama'";
        $query = $this->sdm_db->query($sql);
        $result = $query->result_array();
        //$data = array();
        foreach($result as $row){
            $data = array(
                'nip' => $row['nip'],
                'telp' => $row['telp']
            );
        }
        echo json_encode($data);
    }
}