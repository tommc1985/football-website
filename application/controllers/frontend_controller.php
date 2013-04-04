<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_Controller extends CI_Controller
{
    public $theme;

    function __construct()
    {
        parent::__construct();

        $this->theme = 'default';
    }
}

/* End of file frontend_controller.php */
/* Location: ./application/controllers/frontend_controller.php */