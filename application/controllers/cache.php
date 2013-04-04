<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cache extends CI_Controller {

    public function execute()
    {
        set_time_limit(300);
        if ($this->input->is_cli_request()) {
            $this->load->database();
            $this->load->model('Cache_model');

            $this->Cache_model->processQueuedRows();
        } else {
            $this->load->helper('url');
            redirect('/', 'location', 301);
        }
    }
}

/* End of file cache.php */
/* Location: ./application/controllers/cache.php */