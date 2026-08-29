<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load model dan library yang diperlukan
        $this->load->database();
        $this->load->model('Menu_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('menu_helper');
		$this->load->helper('url');
        
        // Cek login (sesuaikan dengan sistem autentikasi Anda)
        if(!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Pengaturan Menu';
        $data['menus'] = $this->Menu_model->get_all_menus();
        $data['nama'] = $this->session->userdata('username');
        $data['roles'] = [
            'pum' => 'PUM',
            'anggaran' => 'Anggaran',
            'korpum' => 'Korpum',
            'manajer' => 'Manajer',
            'kasir' => 'Kasir',
            'verifikator' => 'Verifikator',
            'yunior_akuntan' => 'Yunior Akuntan'
        ];
        
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('menu/index', $data);
        $this->load->view('template/footer');
    }

    public function update_role_access() {
        $this->load->model('Menu_model');
        
        $menu_id = $this->input->post('menu_id');
        $role = $this->input->post('role');
        $value = $this->input->post('value');
        
        // Validasi value
        $value = ($value == 'true' || $value == 1) ? 1 : 0;
        
        $result = $this->Menu_model->update_role_access($menu_id, $role, $value);
        
        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'Akses berhasil diperbarui']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui akses']);
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Menu';
        $data['menu'] = $this->Menu_model->get_menu_by_id($id);
        $data['nama'] = $this->session->userdata('username');
        $data['parent_menus'] = $this->Menu_model->get_parent_menus();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('menu/edit', $data);
        $this->load->view('template/footer');
    }

    public function update($id) {
        $data = [
            'label' => $this->input->post('label'),
            'link' => $this->input->post('link'),
            'icon' => $this->input->post('icon'),
            'parent' => $this->input->post('parent'),
            'sort' => $this->input->post('sort')
        ];
        
        $this->Menu_model->update_menu($id, $data);
        $this->session->set_flashdata('success', 'Menu berhasil diperbarui');
        redirect('menu');
    }
}
?>