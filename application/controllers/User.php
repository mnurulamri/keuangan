<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('user_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('menu_helper');
        $this->load->library('session');

        // Check login - sesuaikan dengan sistem auth Anda
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
        
        // Check if user has admin role
        //if ($this->session->userdata('role') != 'admin') {
            //show_error('Anda tidak memiliki akses ke halaman ini', 403, 'Akses Ditolak');
        //}
    }

    public function index() {
        $data['title'] = 'Pengaturan User';
        $data['nama'] = $this->session->userdata('username');
        $data['users'] = $this->user_model->get_all_users();
        $data['roles'] = $this->user_model->get_roles();
        $data['units'] = $this->user_model->get_all_units();
        
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()) );
        $this->load->view('user/index', $data);
        $this->load->view('template/footer');
    }

    public function ajax_list() {
        $users = $this->user_model->get_all_users();
        $data = array();
        $no = $_POST['start'];
        
        foreach ($users as $user) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $user->nama;
            $row[] = $user->username;
            $row[] = $user->email;
            $row[] = '<span class="label label-primary">' . ucfirst($user->role) . '</span>';
            $row[] = $user->unit ?: ($user->nama_unit ?: '-');
            $row[] = ($user->is_active == 1) ? 
                '<span class="label label-success">Aktif</span>' : 
                '<span class="label label-danger">Nonaktif</span>';
            
            // Action buttons
            $row[] = '
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" 
                            data-id="'.$user->id.'" 
                            data-nama="'.$user->nama.'"
                            data-username="'.$user->username.'"
                            data-email="'.$user->email.'"
                            data-role="'.$user->role.'"
                            data-kode_bidang="'.$user->kode_bidang.'"
                            data-kode_dpsj="'.$user->kode_dpsj.'"
                            data-is_active="'.$user->is_active.'"
                            title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" 
                            data-id="'.$user->id.'" 
                            data-nama="'.$user->nama.'"
                            title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            ';
            
            $data[] = $row;
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($users),
            "recordsFiltered" => count($users),
            "data" => $data,
        );
        
        echo json_encode($output);
    }

    public function ajax_save() {
        $this->_validate();
        
        $data = array(
            'nama' => $this->input->post('nama'),
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
            'kode_bidang' => $this->input->post('kode_bidang') ?: NULL,
            'kode_dpsj' => $this->input->post('kode_dpsj') ?: NULL,
            'unit' => $this->input->post('unit') ?: NULL,
            'is_active' => $this->input->post('is_active') ? 1 : 0,
            'password' => password_hash('password123', PASSWORD_DEFAULT) // Default password
        );
        
        $insert = $this->user_model->create_user($data);
        
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update() {
        $this->_validate();
        $id = $this->input->post('id');
        
        $data = array(
            'nama' => $this->input->post('nama'),
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
            'kode_bidang' => $this->input->post('kode_bidang') ?: NULL,
            'kode_dpsj' => $this->input->post('kode_dpsj') ?: NULL,
            'unit' => $this->input->post('unit') ?: NULL,
            'is_active' => $this->input->post('is_active') ? 1 : 0
        );
        
        // Jika password diisi, update password
        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }
        
        $this->user_model->update_user($id, $data);
        
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->user_model->delete_user($id);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_get_units($kode_bidang) {
        $units = $this->user_model->get_units_by_bidang($kode_bidang);
        echo json_encode($units);
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        
        if ($this->input->post('nama') == '') {
            $data['inputerror'][] = 'nama';
            $data['error_string'][] = 'Nama wajib diisi';
            $data['status'] = FALSE;
        }
        
        if ($this->input->post('username') == '') {
            $data['inputerror'][] = 'username';
            $data['error_string'][] = 'Username wajib diisi';
            $data['status'] = FALSE;
        } else {
            $id = $this->input->post('id');
            // sementara dinonaktifkan pengecekan username unik
            /*if ($this->user_model->check_username_exists($this->input->post('username'), $id)) {
                $data['inputerror'][] = 'username';
                $data['error_string'][] = 'Username sudah digunakan';
                $data['status'] = FALSE;
            }*/
        }
        
        if ($this->input->post('role') == '') {
            $data['inputerror'][] = 'role';
            $data['error_string'][] = 'Role wajib diisi';
            $data['status'] = FALSE;
        }
        
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
?>