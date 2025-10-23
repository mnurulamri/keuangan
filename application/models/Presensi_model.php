<?php
class Presensi_model extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->database();
		$this->remun_db = $this->load->database('remun', TRUE);	
	}

	public function getListPegawai($tanggal)
	{
	    $sql = "SELECT * FROM presensi_master_pegawai_pns WHERE flag = 1 AND end_date > '$tanggal'";
	    $query = $this->db->query($sql);
	    return $query->result_array();
	}	

	public function getPresensiDetailPegawai($tahun, $bulan, $nip)
	{
		$sql = "SELECT DISTINCT b.nip as nip, nama_bergelar, date_time, DATE(date_time) as tanggal, TIME_FORMAT(date_time, '%H:%i') as waktu
		FROM  presensi_load_data a
		LEFT JOIN presensi_master_pegawai_pns b ON a.nip = b.nip
		WHERE a.nip = '$nip' AND YEAR(date_time) = '$tahun' AND MONTH(date_time)='$bulan'";
	    $query = $this->db->query($sql);
	    return $query->result_array();
	}
	
	public function getDataItm($nip)
	{
		$sql = "SELECT KodeJenis, TglAwal, TglAkhir	From TblCutiSakitIzin WHERE NIP = '$nip' ";
		$query = $this->remun_db->query($sql);
	    return $query->result_array();
	}

	public function getDataShift()
	{
		$sql = "SELECT TglAwal, TglAkhir, H01, H02, H03, H04, H05, H06, H07 FROM TblShift WHERE KodeShift = '001'";
		$query = $this->remun_db->query($sql);
		return $query->result_array();
	}

	public function getDataLibur($tahun)
	{
	    $sql = "SELECT Uraian, TglMulai, TglAkhir FROM TblHariLibur WHERE YEAR(TglMulai) = '$tahun'";
	    $query = $this->remun_db->query($sql);
	    return $query->result_array();
	}

	public function getDataWaktuKerja()
	{
	    $sql = "SELECT KodeWaktuKerja, JadwalMasuk, JadwalKeluar FROM TblWaktuKerja WHERE KodeWaktuKerja = '01'";
	    $query = $this->remun_db->query($sql);
	    return $query->result_array();
	}
	
	public function getPresensiLoadData()
	{
	    $sql = "SELECT * FROM presensi_load_data WHERE month(date_time) = '01'";
	    $query = $this->db->query($sql);
	    return $query->result_array();
	}
}