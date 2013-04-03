<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backend_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('tank_auth');

        if (!$this->tank_auth->is_logged_in()) {
            redirect('/admin/auth/login?url=' . uri_string());
        } else {
            $data['user_id']    = $this->tank_auth->get_user_id();
            $data['username']   = $this->tank_auth->get_username();
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/admin/backend_controller.php */