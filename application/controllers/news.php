<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class News extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Content_model');
        $this->lang->load('news');
        $this->load->helper(array('url', 'utility'));
    }

    /**
     * Index Action
     * @return NULL
     */
    public function index()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('offset'));

        $perPage = Configuration::get('articles_per_page');
        $offset = false;
        if ($parameters['offset'] !== false && $parameters['offset'] > 0) {
            $offset = $parameters['offset'];
        }

        $articles = $this->Content_model->fetchAll(
            array(
                'type' => 'news'
            ), $perPage, $offset
        );

        $data = array(
            'articles' => $articles,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/news/welcome_message", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('id'));

        $article = $this->Content_model->fetch($parameters['id']);

        if ($article === false) {
            show_error($this->lang->line('news_not_found'), 404);
        }

        $data = array(
            'article' => $article,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/news/view", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file news.php */
/* Location: ./application/controllers/news.php */