<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for Import of data
 */
class Import extends CI_Controller/*Backend_Controller*/
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
        die('Import');
    }
}

/* End of file import.php */
/* Location: ./application/controllers/admin/import.php */