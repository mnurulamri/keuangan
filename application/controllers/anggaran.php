<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggaran extends CI_Controller {

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

    public function test(){
        $data['title'] = 'test title';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('test', $data);
        $this->load->view('template/footer');
    }

    public function index() {
        $data['title'] = 'Daftar Pengajuan Anggaran';
        
        // Get user role
        $user_role = $this->session->userdata['logged_anggaran']['role'];
        $user_id = $this->session->userdata('username');
        
        if ($user_role == 'admin' || $user_role == 'keuangan') {
            $data['anggaran'] = $this->Anggaran_model->get_all();
        } else {
            $data['anggaran'] = $this->Anggaran_model->get_by_unit($user_id);
        }
        
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('anggaran/list', $data);
        $this->load->view('template/footer');
    }

    public function pengajuan() {
        $data['title'] = 'Form Pengajuan Dana';
        $data['nama'] = 'test nama';
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();
        
        // Generate nomor pengajuan untuk preview
        $data['preview_nomor'] = $this->Anggaran_model->generate_nomor_pengajuan('kom'); // Default kode
        $data['kode_bidang'] = $this->session->userdata('kode_bidang');

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('unit_id', 'Unit', 'required');
        $this->form_validation->set_rules('penanggung_jawab', 'Penanggung Jawab', 'required');
        $this->form_validation->set_rules('nomor_identitas', 'NPM/NIP/NUP', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');
        $this->form_validation->set_rules('untuk_nama', 'Untuk dan Atas Nama', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('anggaran/pengajuan', $data);
            $this->load->view('template/footer');
			$this->load->view('anggaran/pengajuan_script');
        } else {
            // Get kode unit
            $unit_id = $this->input->post('unit_id');
            $unit = $this->Unit_model->get_by_id($unit_id);
            $unit_kode = $unit ? $unit->kode_unit : 'kom';
            
            // Data Pemohon
            $data_pemohon = array(
                'nomor_pengajuan' => $this->Anggaran_model->generate_nomor_pengajuan($unit_kode),
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
                redirect('anggaran/pengajuan');
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
        $sql = "SELECT deskripsi_dpsj, kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang'";
        $query = $this->db->query($sql);

		if($query->num_rows()>0){
			$kotaksuggest ='
			
			<table class="autocomplete-dpsj" id="test" >
				<tr>
					<th>DESKRIPSI DPSJ</th>
					<th>KODE DPSJ</th>
				</tr>';
				foreach ($query->result_array() as $row){
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
						<td><div class="isi_pc" data-value="'.$row['nama_kegiatan'].'">'.$row['kode_kegiatan'].'</div></td>
						<td><div class="isi_pc" data-value="'.$row['nama_kegiatan'].'">'.$row['nama_kegiatan'].'</div></td>
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
        
		$sql = "SELECT kode_akun, deskripsi_akun, anggaran, kode_dana 
                FROM rka WHERE kode_kegiatan = '$kode_kegiatan' AND (deskripsi_akun LIKE '%$kata%' OR kode_akun LIKE '%$kata%') 
                LIMIT 10";
		$array = $this->db->query($sql);
		//echo $sql; print_r($array_ruang->result_array()); 
		//$array_ruang = $ppf_web_db->select('*')->from('ruang_rapat')->where('nm_ruang')->limit('10')->get();

		if($array->num_rows()>0){
			$kotaksuggest ='
			
			<table class="autocomplete-pc" id="test" >
				<tr>
					<th>KODE AKUN</th>
					<th>DESKRIPSI AKUN</th>
                    <th>KODE DANA</th>
				</tr>';
				foreach ($array->result_array() as $row){
					$kotaksuggest.= '
					<tr>
						<td><div class="isi_akun" data-value="'.$row['deskripsi_akun'].'">'.$row['kode_akun'].'</div></td>
						<td><div class="isi_akun" data-value="'.$row['deskripsi_akun'].'">'.$row['deskripsi_akun'].'</div></td>
						<td><div class="isi_akun" data-value="'.$row['deskripsi_akun'].'">'.$row['kode_dana'].'</div></td>
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
        print_r($this->input->post()) ;

        $sql = "SELECT nama_unit FROM unit_kerja WHERE kode_dpsj = ?";
        $query = $this->db->query($sql, array($this->input->post('kode_dpsj')));
        $result = $query->result_array();

        foreach($result as $row){
            $nama_unit = $row['nama_unit'];
        }
        
        // insert ke tabel monitoring
        $this->db->insert('monitoring', array(
            'nomor_pengajuan' => $this->input->post('nomor_pengajuan'), 
            'unit' => $nama_unit,
            'form' => 'D01'
        ));

        // set rincian
        foreach($this->input->post('data') as $key => $value) {            
                $this->db->insert('pengajuan_rincian', $value);
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
        redirect('anggaran/pengajuan');
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
}
