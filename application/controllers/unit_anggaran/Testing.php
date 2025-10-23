<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load any required models, libraries, etc. here
    }

    public function index()
    {
        echo "Hello from Testing controller!";
    }

}