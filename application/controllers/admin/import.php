<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for Import of data
 */
class Import extends Backend_Controller
{
    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Import Data
     * @return NULL
     */
    public function index()
    {
        $this->load->model('Import_model');

        $this->Import_model->importData();
    }
}

/* End of file import.php */
/* Location: ./application/controllers/admin/import.php */