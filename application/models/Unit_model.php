<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('units')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('units', array('id' => $id))->row();
    }
}