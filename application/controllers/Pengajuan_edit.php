<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_edit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
        $this->load->helper('sisa_anggaran_helper');
		$this->load->library('session');

        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() 
    {		
        // get all data from pengajuan_rincian berdasarkan kode_bidang
        $kode_bidang = $this->session->userdata('logged_anggaran')['kode_bidang'];
        $data['kode_bidang'] = $kode_bidang;
        
        
        $this->load->model('Unit_model');
        $data['units'] = $this->Unit_model->get_all();

        // ambil id pengajuan dari input POST
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        
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
            //'jabatan' => $result['jabatan'],
            'telp' => $result['telp']
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

        //$kode_dpsj = implode("','", $array_kode_dpsj_value);
        //$kode_dpsj = "'".$kode_dpsj."'";
        $kode_dpsj = $this->input->post('kode_dpsj');
        $deskripsi_dpsj = $this->input->post('deskripsi_dpsj');
        $data['kode_dpsj'] = $kode_dpsj;
        $data['deskripsi_dpsj'] = $deskripsi_dpsj;

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

        $data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;

        $this->load->view('unit_kerja/pengajuan_form_edit',$data);
    }

    
    public function simpan()
    {
        
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
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
         
        $kode_dpsj = $this->input->post('kode_dpsj');
        $deskripsi_dpsj = $this->input->post('deskripsi_dpsj');

        
        foreach ($rincian as $key => $value) {
            //print_r($value); // Debugging

            // cek apakah data sudah ada di tabel pengajuan_rincian
            $id = $value['id']; 

            $kode_kegiatan = $value['kode_kegiatan'];
            $nama_kegiatan = $value['nama_kegiatan'];
            $kode_akun = $value['kode_akun'];
            $deskripsi_akun = $value['deskripsi_akun'];
            $kode_dana = $value['kode_dana'];
            $komitmen = $value['komitmen'];
            $keterangan = $value['keterangan'];
            $username = $this->session->userdata('logged_anggaran')['username'];

            $this->db->where('id', $id);         
            $query = $this->db->get('pengajuan_rincian');
            $result = $query->row_array();
            print_r($result); // Debugging 
            
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
                    'komitmen' => $komitmen,
                    'keterangan' => $keterangan,
                    'tgl_update' => $tanggal,
                    'username' => $username
                );

                // update data pada tabel pengajuan_rincian
                $this->db->where('id', $id);
                $this->db->update('pengajuan_rincian',$data_rincian);

                echo 'update data:';print_r($data_rincian); // Debugging
                //print_r($result); // Debugging
               

            } else {
                $id = null; // Set ID ke null untuk insert baru
                //echo "Insert data baru dengan ID: $id<br>"; // Debugging
                // data belum ada, insert data baru ke tabel pengajuan_rincian
                $data_rincian = array(
                    'kode_dpsj' => $kode_dpsj,
                    'deskripsi_dpsj' => $deskripsi_dpsj,
                    'kode_kegiatan' => $kode_kegiatan,
                    'nama_kegiatan' => $nama_kegiatan,
                    'kode_akun' => $kode_akun,
                    'deskripsi_akun' => $deskripsi_akun,
                    'kode_dana' => $kode_dana,
                    //'nomor_pengajuan' => $nomor_pengajuan,
                    'komitmen' => $komitmen,
                    'keterangan' => $keterangan,
                    'tgl_update' => $tanggal,
                    'username' => $username,
                    'id_pengajuan_pemohon' => $id_pengajuan_pemohon
                );
               
                // insert ke database
                $this->db->insert('pengajuan_rincian', $data_rincian);
            }
            
        }
        
    }

    public function deletePengajuan(){
        // set id pengajuan pemohon
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');

        // delete data pengajuan pada tabel pemohon
        $this->db->where('id', $id_pengajuan_pemohon);
        $this->db->delete('pengajuan_pemohon');

        $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
        $this->db->delete('pengajuan_rincian');
    }

    public function deletePengajuanRincian(){
        // set id
        $id = $this->input->post('id');

        // delete baris data pengajuan pada tabel pengajuan_rincian
        $this->db->where('id', $id);
        $this->db->delete('pengajuan_rincian');
        
    }
}