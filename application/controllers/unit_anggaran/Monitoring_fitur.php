<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_fitur extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('session');
        
		$session_data = array(
                        'username'  => 'xxx',
                        'hak_akses' => 1,
                        'role' => 'admin',
                        'kode_org_anggaran' => '00',
                        'cn_anggaran' => ''
                    );
		$this->session->set_userdata('logged_anggaran', $session_data);

        /*if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }*/
        if (!$this->session->userdata('kode_bidang')) {
            redirect('auth/unit_kerja');
        }
    }

    public function index() {
        $data['title'] = 'Monitoring Anggaran';
        //$data['anggaran'] = $this->Anggaran_model->get_all_anggaran();
        //$data['rka'] = $this->Rka_model->get_all_rka();
        
        // Load view
        $this->load->view('anggaran/monitoring-ajax-index ', $data);
    }

    public function ajax_list() {
        // This method can be used to handle AJAX requests for listing data
        // You can implement pagination, filtering, etc. here
        $data['title'] = 'Monitoring Anggaran';
        $this->load->view('anggaran/monitoring-ajax-index', $data);
    }
    public function ajax_detail($id) {
        // This method can be used to handle AJAX requests for details of a specific item
        // You can implement fetching details based on the ID here
        $data['title'] = 'Detail Anggaran';
        $data['id'] = $id; // Example of passing the ID to the view
        $this->load->view('anggaran/monitoring-ajax-detail', $data);
    }
    public function ajax_edit($id) {
        // This method can be used to handle AJAX requests for editing a specific item
        // You can implement fetching the item based on the ID and returning it as JSON
        $data['title'] = 'Edit Anggaran';
        $data['id'] = $id; // Example of passing the ID to the view
        $this->load->view('anggaran/monitoring-ajax-edit', $data);
    }
    public function ajax_delete($id) {
        // This method can be used to handle AJAX requests for deleting a specific item
        // You can implement the deletion logic based on the ID here
        $this->Anggaran_model->delete_anggaran($id);
        echo json_encode(array("status" => TRUE));
    }
    public function ajax_add() {
        // This method can be used to handle AJAX requests for adding a new item
        // You can implement the logic for adding a new item here
        $data = array(
            'nama' => $this->input->post('nama'),
            'jumlah' => $this->input->post('jumlah')
        );
        $insert = $this->Anggaran_model->save_anggaran($data);
        echo json_encode(array("status" => TRUE));
    }
    public function ajax_update() {
        // This method can be used to handle AJAX requests for updating an existing item
        // You can implement the logic for updating an item here
        $data = array(
            'nama' => $this->input->post('nama'),
            'jumlah' => $this->input->post('jumlah')
        );
        $this->Anggaran_model->update_anggaran($this->input->post('id'), $data);
        echo json_encode(array("status" => TRUE));
    }
    public function ajax_search() {
        // This method can be used to handle AJAX requests for searching items
        // You can implement the search logic here
        $keyword = $this->input->post('keyword');
        $data['results'] = $this->Anggaran_model->search_anggaran($keyword);
        $this->load->view('anggaran/monitoring-ajax-search', $data);
    }
    public function ajax_pagination() {
        // This method can be used to handle AJAX requests for pagination
        // You can implement the pagination logic here
        $config = array();
        $config["base_url"] = base_url() . "anggaran/monitoring/ajax_list";
        $config["total_rows"] = $this->Anggaran_model->get_count_anggaran();
        $config["per_page"] = 10;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        $page = ($this->input->get('page')) ? $this->input->get('page') : 0;
        $data["results"] = $this->Anggaran_model->get_anggaran($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        
        $this->load->view('anggaran/monitoring-ajax-pagination', $data);
    }
    public function ajax_export() {
        // This method can be used to handle AJAX requests for exporting data
        // You can implement the export logic here, e.g., exporting to CSV or Excel
        $data['title'] = 'Export Anggaran';
        $this->load->view('anggaran/monitoring-ajax-export', $data);
    }
    public function ajax_import() {
        // This method can be used to handle AJAX requests for importing data
        // You can implement the import logic here, e.g., importing from a CSV file
        $data['title'] = 'Import Anggaran';
        $this->load->view('anggaran/monitoring-ajax-import', $data);
    }
    public function ajax_report() {
        // This method can be used to handle AJAX requests for generating reports
        // You can implement the report generation logic here
        $data['title'] = 'Report Anggaran';
        $this->load->view('anggaran/monitoring-ajax-report', $data);
    }
    public function ajax_settings() {
        // This method can be used to handle AJAX requests for settings
        // You can implement the settings logic here, e.g., updating configurations
        $data['title'] = 'Settings Anggaran';
        $this->load->view('anggaran/monitoring-ajax-settings', $data);
    }
    public function ajax_help() {
        // This method can be used to handle AJAX requests for help or documentation
        // You can implement the help logic here, e.g., displaying help content
        $data['title'] = 'Help Anggaran';
        $this->load->view('anggaran/monitoring-ajax-help', $data);
    }
    public function ajax_logout() {
        // This method can be used to handle AJAX requests for logging out
        // You can implement the logout logic here, e.g., clearing session data
        $this->session->unset_userdata('logged_anggaran');
        redirect('auth/login');
    }
    public function ajax_dashboard() {
        // This method can be used to handle AJAX requests for the dashboard
        // You can implement the dashboard logic here, e.g., displaying statistics
        $data['title'] = 'Dashboard Anggaran';
        $this->load->view('anggaran/monitoring-ajax-dashboard', $data);
    }
    public function ajax_notifications() {
        // This method can be used to handle AJAX requests for notifications
        // You can implement the notifications logic here, e.g., displaying user notifications
        $data['title'] = 'Notifications Anggaran';
        $this->load->view('anggaran/monitoring-ajax-notifications', $data);
    }
    public function ajax_user_profile() {
        // This method can be used to handle AJAX requests for user profile
        // You can implement the user profile logic here, e.g., displaying and updating user information
        $data['title'] = 'User Profile Anggaran';
        $this->load->view('anggaran/monitoring-ajax-user-profile', $data);
    }
    public function ajax_activity_log() {
        // This method can be used to handle AJAX requests for activity logs
        // You can implement the activity log logic here, e.g., displaying user activity logs
        $data['title'] = 'Activity Log Anggaran';
        $this->load->view('anggaran/monitoring-ajax-activity-log', $data);
    }
    public function ajax_feedback() {
        // This method can be used to handle AJAX requests for user feedback
        // You can implement the feedback logic here, e.g., collecting and displaying user feedback
        $data['title'] = 'Feedback Anggaran';
        $this->load->view('anggaran/monitoring-ajax-feedback', $data);
    }
    public function ajax_search_results() {
        // This method can be used to handle AJAX requests for search results
        // You can implement the search results logic here, e.g., displaying search results based on user input
        $keyword = $this->input->post('keyword');
        $data['results'] = $this->Anggaran_model->search_anggaran($keyword);
        $this->load->view('anggaran/monitoring-ajax-search-results', $data);
    }
    public function ajax_statistics() {
        // This method can be used to handle AJAX requests for statistics
        // You can implement the statistics logic here, e.g., displaying statistical data related to the budget
        $data['title'] = 'Statistics Anggaran';
        $this->load->view('anggaran/monitoring-ajax-statistics', $data);
    }
    public function ajax_logs() {
        // This method can be used to handle AJAX requests for logs
        // You can implement the logs logic here, e.g., displaying system logs or user activity logs
        $data['title'] = 'Logs Anggaran';
        $this->load->view('anggaran/monitoring-ajax-logs', $data);
    }
    public function ajax_settings_update() {
        // This method can be used to handle AJAX requests for updating settings
        // You can implement the settings update logic here, e.g., updating configurations based on user input
        $data = array(
            'setting_name' => $this->input->post('setting_name'),
            'setting_value' => $this->input->post('setting_value')
        );
        $this->Anggaran_model->update_setting($data);
        echo json_encode(array("status" => TRUE));
    }
    public function ajax_help_content() {
        // This method can be used to handle AJAX requests for help content
        // You can implement the help content logic here, e.g., displaying help articles or FAQs
        $data['title'] = 'Help Content Anggaran';
        $this->load->view('anggaran/monitoring-ajax-help-content', $data);
    }
    public function ajax_contact() {
        // This method can be used to handle AJAX requests for contact information
        // You can implement the contact logic here, e.g., displaying contact details or a contact form
        $data['title'] = 'Contact Anggaran';
        $this->load->view('anggaran/monitoring-ajax-contact', $data);
    }
    public function ajax_terms() {
        // This method can be used to handle AJAX requests for terms and conditions
        // You can implement the terms logic here, e.g., displaying terms and conditions for using the application
        $data['title'] = 'Terms Anggaran';
        $this->load->view('anggaran/monitoring-ajax-terms', $data);
    }
    public function ajax_privacy() {
        // This method can be used to handle AJAX requests for privacy policy
        // You can implement the privacy logic here, e.g., displaying the privacy policy for the application
        $data['title'] = 'Privacy Anggaran';
        $this->load->view('anggaran/monitoring-ajax-privacy', $data);
    }
    public function ajax_faq() {
        // This method can be used to handle AJAX requests for frequently asked questions
        // You can implement the FAQ logic here, e.g., displaying a list of FAQs related to the application
        $data['title'] = 'FAQ Anggaran';
        $this->load->view('anggaran/monitoring-ajax-faq', $data);
    }
    public function ajax_support() {
        // This method can be used to handle AJAX requests for support
        // You can implement the support logic here, e.g., displaying support options or a support form
        $data['title'] = 'Support Anggaran';
        $this->load->view('anggaran/monitoring-ajax-support', $data);
    }
    public function ajax_about() {
        // This method can be used to handle AJAX requests for about information
        // You can implement the about logic here, e.g., displaying information about the application or organization
        $data['title'] = 'About Anggaran';
        $this->load->view('anggaran/monitoring-ajax-about', $data);
    }
    public function ajax_logout_confirm() {
        // This method can be used to handle AJAX requests for logout confirmation
        // You can implement the logout confirmation logic here, e.g., displaying a confirmation dialog
        $data['title'] = 'Logout Confirmation Anggaran';
        $this->load->view('anggaran/monitoring-ajax-logout-confirm', $data);
    }
    public function ajax_session_check() {
        // This method can be used to handle AJAX requests for checking session validity
        // You can implement the session check logic here, e.g., verifying if the user is still logged in
        if ($this->session->userdata('logged_anggaran')) {
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }
    public function ajax_error() {
        // This method can be used to handle AJAX requests for error handling
        // You can implement the error handling logic here, e.g., displaying error messages or logging errors
        $data['title'] = 'Error Anggaran';
        $this->load->view('anggaran/monitoring-ajax-error', $data);
    }
    public function ajax_success() {
        // This method can be used to handle AJAX requests for success messages
        // You can implement the success logic here, e.g., displaying success messages after an operation
        $data['title'] = 'Success Anggaran';
        $this->load->view('anggaran/monitoring-ajax-success', $data);
    }
    public function ajax_loading() {
        // This method can be used to handle AJAX requests for loading indicators
        // You can implement the loading logic here, e.g., displaying a loading spinner while data is being fetched
        $data['title'] = 'Loading Anggaran';
        $this->load->view('anggaran/monitoring-ajax-loading', $data);
    }
    public function ajax_error_404() {
        // This method can be used to handle AJAX requests for 404 errors
        // You can implement the 404 error handling logic here, e.g., displaying a 404 error page
        $data['title'] = '404 Error Anggaran';
        $this->load->view('anggaran/monitoring-ajax-error-404', $data);
    }
    public function ajax_error_500() {
        // This method can be used to handle AJAX requests for 500 errors
        // You can implement the 500 error handling logic here, e.g., displaying a 500 error page
        $data['title'] = '500 Error Anggaran';
        $this->load->view('anggaran/monitoring-ajax-error-500', $data);
    }
    public function ajax_custom() {
        // This method can be used to handle custom AJAX requests
        // You can implement any custom logic here based on your application's requirements
        $data['title'] = 'Custom Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom', $data);
    }
    public function ajax_custom_action() {
        // This method can be used to handle custom AJAX actions
        // You can implement any custom action logic here based on your application's requirements
        $data = array(
            'action' => $this->input->post('action'),
            'value' => $this->input->post('value')
        );
        // Perform the custom action based on the input data
        // For example, you can save it to the database or perform some calculations
        echo json_encode(array("status" => TRUE, "message" => "Custom action performed successfully."));
    }
    public function ajax_custom_view() {
        // This method can be used to load a custom view for AJAX requests
        // You can implement any custom view logic here based on your application's requirements
        $data['title'] = 'Custom View Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-view', $data);
    }
    public function ajax_custom_data() {
        // This method can be used to return custom data for AJAX requests
        // You can implement any custom data logic here based on your application's requirements
        $data = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3'
        );
        echo json_encode($data);
    }
    public function ajax_custom_error() {
        // This method can be used to handle custom AJAX errors
        // You can implement any custom error handling logic here based on your application's requirements
        $error_message = 'This is a custom error message.';
        echo json_encode(array("status" => FALSE, "message" => $error_message));
    }
    public function ajax_custom_success() {
        // This method can be used to handle custom AJAX success messages
        // You can implement any custom success handling logic here based on your application's requirements
        $success_message = 'This is a custom success message.';
        echo json_encode(array("status" => TRUE, "message" => $success_message));
    }
    public function ajax_custom_loading() {
        // This method can be used to handle custom AJAX loading indicators
        // You can implement any custom loading logic here based on your application's requirements
        $data['title'] = 'Custom Loading Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-loading', $data);
    }
    public function ajax_custom_404() {
        // This method can be used to handle custom AJAX 404 errors
        // You can implement any custom 404 error handling logic here based on your application's requirements
        $data['title'] = 'Custom 404 Error Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-404', $data);
    }
    public function ajax_custom_500() {
        // This method can be used to handle custom AJAX 500 errors
        // You can implement any custom 500 error handling logic here based on your application's requirements
        $data['title'] = 'Custom 500 Error Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-500', $data);
    }
    public function ajax_custom_dashboard() {
        // This method can be used to handle custom AJAX requests for the dashboard
        // You can implement any custom dashboard logic here based on your application's requirements
        $data['title'] = 'Custom Dashboard Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-dashboard', $data);
    }
    public function ajax_custom_notifications() {
        // This method can be used to handle custom AJAX requests for notifications
        // You can implement any custom notifications logic here based on your application's requirements
        $data['title'] = 'Custom Notifications Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-notifications', $data);
    }
    public function ajax_custom_user_profile() {
        // This method can be used to handle custom AJAX requests for user profile
        // You can implement any custom user profile logic here based on your application's requirements
        $data['title'] = 'Custom User Profile Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-user-profile', $data);
    }
    public function ajax_custom_activity_log() {
        // This method can be used to handle custom AJAX requests for activity logs
        // You can implement any custom activity log logic here based on your application's requirements
        $data['title'] = 'Custom Activity Log Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-activity-log', $data);
    }
    public function ajax_custom_feedback() {
        // This method can be used to handle custom AJAX requests for user feedback
        // You can implement any custom feedback logic here based on your application's requirements
        $data['title'] = 'Custom Feedback Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-feedback', $data);
    }
    public function ajax_custom_search_results() {
        // This method can be used to handle custom AJAX requests for search results
        // You can implement any custom search results logic here based on your application's requirements
        $keyword = $this->input->post('keyword');
        $data['results'] = $this->Anggaran_model->search_anggaran($keyword);
        $this->load->view('anggaran/monitoring-ajax-custom-search-results', $data);
    }
    public function ajax_custom_statistics() {
        // This method can be used to handle custom AJAX requests for statistics
        // You can implement any custom statistics logic here based on your application's requirements
        $data['title'] = 'Custom Statistics Anggaran';
        $this->load->view('anggaran/monitoring-ajax-custom-statistics', $data);
    }
}