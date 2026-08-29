<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_cetak extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
		$this->load->helper('url');
    }

    public function index() {
        // ambil id pengajuan dari input POST
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');

        // ambil data nama form
        $sql = "SELECT form FROM monitoring WHERE id = ?";
        $query = $this->db->query($sql, array($id_pengajuan_pemohon));
        $result = $query->row_array();
        $form = $result['form'];
        
        if($form == 'D01'){
            include(base_url().'template/form_D01.php');
        } else {
            include(base_url().'template/form_D02.php');

        }
    }
}