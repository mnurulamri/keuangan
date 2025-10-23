<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('load_vendor')) {
    function load_vendor() {
        require_once FCPATH . 'vendor/autoload.php';
    }
}