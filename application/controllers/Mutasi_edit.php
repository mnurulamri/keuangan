<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_edit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('menu_helper');
        $this->load->helper('status_helper');        
        $this->load->helper('periode_helper');
        $this->load->library('Ajax_pagination_mutasi');
        $this->perPage = 2;

        // Load session library
		$this->load->library('session');
        
		// Cek apakah pengguna sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() 
    {
        $kode_grup = $this->input->post('kode_grup');
        $kode_dpsj = $this->input->post('kode_dpsj');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');

        // set kode bidang
        $sql = "SELECT kode_bidang FROM unit_kerja WHERE kode_dpsj = ?";
        $query = $this->db->query($sql, array($kode_dpsj));
        $result = $query->result_array();
        foreach($result as $row){
            $kode_bidang = $row['kode_bidang'];
        }        
        
        $data['title'] = 'Form Edit Mutasi';
        $data['nama'] = 'xxx';
        
        // set periode
        $data['periode'] = $this->periode();

        foreach($data['periode'] as $row){
            $tahun =$row['tahun'];
            $bulan =$row['bulan'];
        }

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();
         
        $data['kode_bidang'] = $kode_bidang; // $this->session->userdata('logged_anggaran')['kode_bidang'];
       
        // ambil identitas pejabat
        $this->sdm_db = $this->load->database('sdm', TRUE);
        $sql = "SELECT * FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = ? ORDER BY kd_struktur";
        $query = $this->sdm_db->query($sql, array($data['kode_bidang']));
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
        
        $data['preview_nomor'] = '';

        // ambil detail rincian mutasi        
        $sql = "SELECT * FROM mutasi WHERE kode_grup = ?";
        $query = $this->db->query($sql, array($kode_grup));
        $result_rincian = $query->result_array();

        // ambil data sisa anggaran dari tabel view_anggaran_mutasi
        foreach($result_rincian as $row){
            $sql = "SELECT * FROM view_anggaran_mutasi WHERE tahun_anggaran = ? AND kode_kegiatan = ? AND kode_akun = ? AND kode_dana = ?";
            $query = $this->db->query($sql, array($tahun, $row['kode_kegiatan'], $row['kode_akun'], $row['kode_dana']));
            $result_sisa = $query->result_array();
            //$data = array();
            $kategori_kegiatan = '';
            foreach($result_sisa as $row_sisa){
                $sisa_anggaran = $row_sisa['sisa_anggaran'];
                $kategori_kegiatan = $row_sisa['kategori_kegiatan'];
            }
            $array_rincian[] = array(                
                'id' => $row['id'],
                'kode_kegiatan' => $row['kode_kegiatan'],
                'nama_kegiatan' => $row['nama_kegiatan'],
                'kategori_kegiatan' => $kategori_kegiatan,
                'kode_akun' => $row['kode_akun'],
                'deskripsi_akun' => $row['deskripsi_akun'],
                'kode_dana' => $row['kode_dana'],
                'mutasi' => $row['mutasi'],
                'keterangan' => $row['keterangan'],
                'sisa_anggaran' => $sisa_anggaran
            );
        }

        // ambil data dpsj untuk menampilkan nama dpsj pada form edit
        $sql = "SELECT kode_dpsj, deskripsi_dpsj FROM mutasi WHERE kode_grup = ? LIMIT 1";
        $query = $this->db->query($sql, array($kode_grup));
        $result_dpsj = $query->result_array();
        foreach($result_dpsj as $row_dpsj){
            $deskripsi_dpsj = $row_dpsj['deskripsi_dpsj'];
            $kode_dpsj = $row_dpsj['kode_dpsj'];
        }
        $data['deskripsi_dpsj'] = $deskripsi_dpsj;
        $data['kode_dpsj'] = $kode_dpsj;

        $data['result'] = $array_rincian;
        $data['sql'] = $sql;
        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
        $data['kode_grup'] = $kode_grup;
        $this->load->view('mutasi/mutasi_form_edit',$data);
    }

    public function periode() {
        $sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function lock_data() 
    {
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $sql = "SELECT * FROM periode WHERE tahun = ? AND bulan = ? ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql, array($tahun, $bulan));
        $result = $query->result_array();
        if($query->num_rows() == 0){
            $lock_data = 1; // default terkunci
        } else {
            foreach($result as $row){
                $lock_data = $row['lock_data'];            
            }
        }
        echo $lock_data;
    }

    public function simpan()
    {
        
        //$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        /* ada tiga tabel yang akan diupdate
         * 1. pengajuan_pemohon
         * 2. pengajuan_rincian
         * 3. monitoring
         * untuk tabel pengajuan_pemohon, data yang akan diupdate adalah data pejabat, nama unit kerja, tanggal, nomor pengajuan
         * untuk tabel pengajuan_rincian, data yang akan diupdate adalah rincian pengajuan
         * untuk tabel monitoring, data yang akan diupdate adalah nomor urut, nomor pengajuan, form, kode unit, kode dpsj
         */
       
        /* update data pemohon
         * 1. ambil data dari input post
         * 2. data yang di update adalah data pada field penanggung jawab, nip dan telp
         * 3. update data pada tabel pengajuan_pemohon berdasarkan nomor pengajuan
         */

        // ambil data nomor pengajuan dari input post
        //$nomor_pengajuan = $this->input->post('nomor_pengajuan');
        //if (empty($nomor_pengajuan)) {
        //    $this->session->set_flashdata('error', 'Nomor pengajuan belum diinputkan.');
        //    redirect('pengajuan_edit');
            //return;
        //}
        /*
        // update data pada tabel pengajuan_pemohon
        $penanggung_jawab = $this->input->post('penanggung_jawab');
        $nip = $this->input->post('nip');
        $telp = $this->input->post('telp');
        $tanggal = date('Y-m-d H:i:s');
        $untuk = $this->input->post('untuk');
        $data_pemohon = array(
            'penanggung_jawab' => $penanggung_jawab,
            'nip' => $nip,
            'telp' => $telp,
            'tanggal' => $tanggal,
            'untuk' => $untuk
        );

        // update data pada tabel pengajuan_pemohon
        $this->db->where('id', $id_pengajuan_pemohon);
        $this->db->update('pengajuan_pemohon', $data_pemohon);
        //echo $sql = $this->db->set($data_pemohon)->get_compiled_update('pengajuan_pemohon');
        
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Data pemohon berhasil diupdate.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate data pemohon.');
            //redirect('pengajuan_edit');
            //return;
        }
        */
        /* update data rincian
         * 1. ambil data dari input post
         * 2. data yang di update adalah data pada field rincian pengajuan
         * 3. update data pada tabel pengajuan_rincian berdasarkan nomor pengajuan, kode_kegiatan, kode_akun, kode_dana
         * 4. jika ada data yang tidak ada di input post, maka data tersebut akan dihapus
         * 5. jika ada data yang baru, maka data tersebut akan ditambahkan
         * 6. jika ada data yang sudah ada, maka data tersebut akan diupdate
         */
        // ambil data rincian dari input post
        $rincian = $this->input->post('rincian');
        if (empty($rincian)) {
            $this->session->set_flashdata('error', 'Rincian pengajuan belum diinputkan.');
            redirect('pengajuan_edit');
            return;
        }
        
        // update data pada tabel pengajuan_rincian
        
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $kode_grup = $this->input->post('kode_grup');
        $kode_dpsj = $this->input->post('kode_dpsj');
        $deskripsi_dpsj = $this->input->post('deskripsi_dpsj');

        print_r($rincian);// Debugging
        foreach ($rincian as $key => $value) {
            //print_r($value); // Debugging

            // cek apakah data sudah ada di tabel pengajuan_rincian
            $id = $value['id']; 
            $kode_kegiatan = $value['kode_kegiatan'];
            $nama_kegiatan = $value['nama_kegiatan'];
            $kode_akun = $value['kode_akun'];
            $deskripsi_akun = $value['deskripsi_akun'];
            $kode_dana = $value['kode_dana'];
            $mutasi = $value['mutasi'];
            $keterangan = $value['keterangan'];
            $username = $this->session->userdata('logged_anggaran')['username'];

			// ganti kode dpsj'
			$sql = "SELECT kode_dpsj, deskripsi_dpsj FROM rka WHERE kode_kegiatan = '$kode_kegiatan' AND kode_akun = '$kode_akun' AND kode_dana = '$kode_dana' ";
			$query = $this->db->query($sql);
        	$result = $query->result_array();
			foreach($result as $row){
				$data_rincian[$key]['kode_dpsj'] = $row['kode_dpsj'];
				$data_rincian[$key]['deskripsi_dpsj'] = $row['deskripsi_dpsj'];
			}

            //$this->db->where('id', $id);         
            //$query = $this->db->get('mutasi');
            //$result = $query->row_array();
            
            $sql = "SELECT * FROM mutasi where id=$id";
            $query = $this->db->query($sql);
            $result = $query->result_array();

            if ($query->num_rows() > 0) {
                echo "Update data dengan ID: $id<br>"; // Debugging

                // inisiasi data array
                $data_rincian = array(
                    'kode_dpsj' => $kode_dpsj,
                    'deskripsi_dpsj' => $deskripsi_dpsj,
                    'kode_kegiatan' => $kode_kegiatan,
                    'nama_kegiatan' => $nama_kegiatan,
                    'kode_akun' => $kode_akun,
                    'deskripsi_akun' => $deskripsi_akun,
                    'kode_dana' => $kode_dana,
                    //'nomor_pengajuan' => $nomor_pengajuan,
                    'mutasi' => $mutasi,
                    'keterangan' => $keterangan,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $username
                );

                // update data pada tabel pengajuan_rincian
                $this->db->where('id', $id);
                $this->db->update('mutasi',$data_rincian);

                echo 'update data:';print_r($data_rincian); // Debugging
                //print_r($result); // Debugging
               

            } else {
                $id = null; // Set ID ke null untuk insert baru
                echo "Insert data baru dengan ID: $id<br>"; // Debugging
                // data belum ada, insert data baru ke tabel pengajuan_rincian
                $data_rincian = array(
                    'tahun_anggaran' => $tahun,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'kode_grup' => $kode_grup,
                    'kode_dpsj' => $kode_dpsj,
                    'deskripsi_dpsj' => $deskripsi_dpsj,
                    'kode_kegiatan' => $kode_kegiatan,
                    'nama_kegiatan' => $nama_kegiatan,
                    'kode_akun' => $kode_akun,
                    'deskripsi_akun' => $deskripsi_akun,
                    'kode_dana' => $kode_dana,
                    //'nomor_pengajuan' => $nomor_pengajuan,
                    'mutasi' => $mutasi,
                    'keterangan' => $keterangan,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $username,
                    'id_pengajuan_pemohon' => $this->input->post('id_pengajuan_pemohon'),
                );
               
                // insert ke database
                $this->db->insert('mutasi', $data_rincian);
                print_r($data_rincian); // Debugging
            }
            
        }
        
    }

    public function deletePengajuan(){
        // set id pengajuan pemohon
        $kode_grup = $this->input->post('kode_grup');

        // delete data pengajuan pada tabel pemohon
        //$this->db->where('id', $id_pengajuan_pemohon);
        //$this->db->delete('pengajuan_pemohon');

        $this->db->where('kode_grup', $kode_grup);
        $this->db->delete('mutasi');
    }

    public function deletePengajuanRincian(){
        // set id
        $id = $this->input->post('id');

        // delete baris data pengajuan pada tabel pengajuan_rincian
        $this->db->where('id', $id);
        $this->db->delete('mutasi');
        
    }
}