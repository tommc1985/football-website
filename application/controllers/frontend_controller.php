<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_Controller extends CI_Controller
{
    public $theme;
    public $templateData;
    public $Assets;

    public function __construct()
    {
        parent::__construct();

        $this->templateData = array();

        $this->load->database();
        $this->load->library('tank_auth');
        $this->load->library('assets');
        $this->Assets = Assets::get_instance();

        $this->templateData['isLoggedIn'] = $this->tank_auth->is_logged_in();

        $this->theme = 'default';
        $this->templateData['theme'] = $this->theme;

        $this->lang->load('global');

        $this->load->helper(array('url', 'utility'));

        $this->load->model('frontend/League_model');
        $this->load->model('Season_model');

        $this->templateData['metaTitle']       = '';
        $this->templateData['metaDescription'] = '';

        Assets::addCss('bootstrap/docs/assets/css/bootstrap.css');
        Assets::addCss('bootstrap/docs/assets/css/bootstrap-responsive.css');
        Assets::addCss('assets/font-awesome/css/font-awesome.css');
        Assets::addCss('assets/font-awesome/css/font-awesome-ie7.css', 'IE 7');
        Assets::addCss('assets/css/tables.css');

        Assets::addJs('http://platform.twitter.com/widgets.js');
        Assets::addJs('bootstrap/docs/assets/js/jquery.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-transition.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-alert.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-modal.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-dropdown.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-scrollspy.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-tab.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-tooltip.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-popover.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-button.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-collapse.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-carousel.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-typeahead.js');
        Assets::addJs('bootstrap/docs/assets/js/bootstrap-affix.js');
    }
}

/* End of file frontend_controller.php */
/* Location: ./application/controllers/frontend_controller.php */