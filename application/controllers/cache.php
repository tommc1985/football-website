<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cache extends CI_Controller {

    public function execute()
    {
        //if ($this->input->is_cli_request()) {
            $this->load->database();
            $this->load->model('Season_model');
            $this->load->model('Cache_Player_model');
            $this->load->model('Cache_Player_Goals_model');

            $rowsProcessed = $this->Cache_Player_Goals_model->processQueuedRows();

            if (!$rowsProcessed) {
                $rowsProcessed = $this->Cache_Player_model->processQueuedRows();
            }
        /*} else {
            $this->load->helper('url');
            redirect('/', 'location', 301);
        }*/
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */