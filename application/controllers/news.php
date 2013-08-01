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
        $this->load->library('pagination');
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

        $config['base_url'] = '/news/index/offset/';
        $config['total_rows'] = count($this->Content_model->fetchAll(
            array(
                'type' => 'news'
            )
        ));
        $config['num_links'] = 1;
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $this->templateData['articles']   = $articles;
        $this->templateData['pagination'] = $$this->pagination->create_links();

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/news/welcome_message", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
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

        $this->templateData['article']   = $article;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/news/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file news.php */
/* Location: ./application/controllers/news.php */