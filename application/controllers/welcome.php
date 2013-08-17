<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Welcome extends Frontend_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->model('frontend/Welcome_model');
        $this->lang->load('index');
        $this->lang->load('fantasy_football');
        $this->lang->load('goal');
        $this->lang->load('player');
        $this->load->helper(array('competition', 'index', 'player', 'position', 'url', 'utility'));

        $limit = 5;
        $season = Season_model::fetchCurrentSeason();

        $section = array();
        $section['latestNewsArticle']  = $this->Welcome_model->fetchLatestNewsArticle();
        $section['topScorers']         = $this->Welcome_model->fetchTopScorers($season, $limit);
        $section['topAssisters']       = $this->Welcome_model->fetchTopAssisters($season, $limit);
        $section['mostMotMs']          = $this->Welcome_model->fetchMostMotMs($season, $limit);
        $section['worstDiscipline']    = $this->Welcome_model->fetchWorstDiscipline($season, $limit);
        $section['fantasyFootballers'] = $this->Welcome_model->fetchFantasyFootballers($season, $limit);
        $section['onThisDay']          = $this->Welcome_model->fetchOnThisDay();
        $section['recentResults']      = $this->Welcome_model->fetchRecentResults($limit);
        $section['upcomingFixtures']   = $this->Welcome_model->fetchUpcomingFixtures($limit);
        $section['upcomingEvents']     = $this->Welcome_model->fetchUpcomingEvents($limit);

        $this->templateData['section'] = $section;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/index/welcome_message", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */