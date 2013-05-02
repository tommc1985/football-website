<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cache extends CI_Controller {

    /**
     * Execute any queued cache calls
     * @return NULL
     */
    public function execute()
    {
        set_time_limit(1200);
        //if ($this->input->is_cli_request()) {
            $this->load->database();
            $this->load->model('Cache_model');

            $this->Cache_model->processQueuedRows();
        /*} else {
            $this->load->helper('url');
            redirect('/', 'location', 301);
        }*/
    }

    /**
     * Insert all possible rows into queues
     * @return NULL
     */
    public function queue_all()
    {
        $this->load->database();
        $this->load->model('Cache_model');
        $this->Cache_model->insertEntries();
    }
}

/* End of file cache.php */
/* Location: ./application/controllers/cache.php */