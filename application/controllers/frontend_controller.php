<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_Controller extends CI_Controller
{
    public $theme;

    public function __construct()
    {
        parent::__construct();

        $this->theme = 'default';

        $this->lang->load('global');

        $this->load->model('Season_model');
    }
}

/* End of file frontend_controller.php */
/* Location: ./application/controllers/frontend_controller.php */