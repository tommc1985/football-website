<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Install extends Frontend_Controller {

    public function index()
    {
        //$this->load->database();

        $this->load->model('install/Install_model');

        $this->Install_model->createDatabaseTables();
        $this->Install_model->createDatabaseViews();
        $this->Install_model->insertData();

        $data = array();
        $this->load->view('install', $data);
    }
}

/* End of file install.php */
/* Location: ./application/controllers/install.php */