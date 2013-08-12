<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_Controller extends CI_Controller
{
    public $theme;
    public $templateData;

    public function __construct()
    {
        parent::__construct();

        $this->templateData = array();

        $this->load->database();
        $this->load->library('tank_auth');

        $this->templateData['isLoggedIn'] = $this->tank_auth->is_logged_in();

        $this->theme = 'default';
        $this->templateData['theme'] = $this->theme;

        $this->lang->load('global');

        $this->load->helper(array('url', 'utility'));

        $this->load->model('frontend/League_model');
        $this->load->model('Season_model');

        $this->templateData['metaTitle']       = '';
        $this->templateData['metaDescription'] = '';
    }
}

/* End of file frontend_controller.php */
/* Location: ./application/controllers/frontend_controller.php */