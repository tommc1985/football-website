<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->database();
		$this->load->model('Player_model');
		$this->load->model('Season_model');
		$this->load->model('Cache_Player_model');
		$this->load->model('Cache_Player_Goals_model');

		$data['players'] = $this->Player_model->fetchPlayers();

		//$this->Cache_Player_Goals_model->generateAllStatistics();
		//$this->Cache_Player_model->generateAllStatistics();
		//
		$this->Cache_Player_Goals_model->processQueuedRows();

		$this->load->view('welcome_message', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */