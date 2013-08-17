<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Player extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Player_model');
        $this->load->model('Competition_model');
        $this->load->model('Season_model');
        $this->lang->load('player');
        $this->load->helper(array('competition', 'nationality', 'player', 'position', 'url', 'utility'));
        $this->load->library('chart');

        Assets::addCss('assets/modules/player/css/player.css');
        Assets::addJs('assets/modules/player/js/player.js');
    }

    /**
     * Index Action
     * @return NULL
     */
    public function index()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('season', 'type', 'order-by', 'order'));

        $baseURL = site_url('player/index');

        $season = (int) $parameters['season'];
        if ($parameters['season'] === false) {
            $season = Season_model::fetchCurrentSeason();
        } elseif ($parameters['season'] == 'all-time') {
            $season = 'all-time';
            $baseURL .= '/season/all-time';
        } else {
            $baseURL .= '/season/' . $season;
        }

        $type = $parameters['type'];
        if ($parameters['type'] === false) {
            $type = 'overall';
        }

        if ($type && $type != 'overall') {
            $baseURL .= '/type/' . $type;
        }

        $orderBy = $this->Player_model->getOrderBy($parameters['order-by']);
        $order = $this->Player_model->getOrder($parameters['order']);

        $players = $this->Player_model->fetchPlayerList($season == 'all-time' ? 'career' : $season, $type, $orderBy, $order);

        $metaData = array(
            Configuration::get('team_name'),
            Utility_helper::formattedSeason($season),
        );

        $this->templateData['metaTitle']       = vsprintf($this->lang->line('player_index_frontend_meta_title'), $metaData);
        $this->templateData['metaDescription'] = vsprintf($this->lang->line('player_index_frontend_meta_description'), $metaData);
        $this->templateData['players']         = $players;
        $this->templateData['season']          = $season;
        $this->templateData['baseURL']         = $baseURL;
        $this->templateData['orderBy']         = $parameters['order-by'];
        $this->templateData['order']           = $parameters['order'];

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/player/welcome_message", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('id', 'info', 'season'));

        $player = $this->Player_model->fetch($parameters['id']);

        if ($player === false) {
            show_error($this->lang->line('player_not_found'), 404);
        }

        $season = (int) $parameters['season'];
        if ($parameters['season'] === false) {
            $season = Season_model::fetchCurrentSeason();
        } elseif ($parameters['season'] == 'all-time') {
            $season = 'all-time';
        }

        $metaData = array(
            Configuration::get('team_name'),
            Player_helper::fullName($player, false),
        );

        switch ($parameters['info']) {
            case 'appearances':
                $info = 'appearances';
                break;
            case 'goal-statistics':
                $info = 'goal-statistics';

                Assets::addJs('assets/js/chartjs/Chart.min.js');
                Assets::addJs('assets/js/charts.js');
                break;
            case 'records':
                $info = 'records';
                break;
            default:
                $info = 'career-statistics';
        }

        $player = $this->Player_model->fetchPlayerDetails($parameters['id'], array(
            'data'   => $info,
            'season' => $season == 'all-time' ? 'career' : $season,
            'type'   => 'overall',
            )
        );

        $this->templateData['metaTitle']       = vsprintf($this->lang->line('player_view_frontend_meta_title'), $metaData);
        $this->templateData['metaDescription'] = vsprintf($this->lang->line('player_view_frontend_meta_description'), $metaData);
        $this->templateData['player']          = $player;
        $this->templateData['baseURL']         = site_url("player/view/id/{$player->id}");
        $this->templateData['info']            = $info;
        $this->templateData['season']          = $season;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/player/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file player.php */
/* Location: ./application/controllers/player.php */