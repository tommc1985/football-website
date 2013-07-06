<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Article extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Content_model');
        $this->lang->load('article');
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
                'type' => 'article'
            ), $perPage, $offset
        );

        $config['base_url'] = '/article/index/offset/';
        $config['total_rows'] = count($this->Content_model->fetchAll(
            array(
                'type' => 'article'
            )
        ));
        $config['num_links'] = 1;
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_closed'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_closed'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_closed'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_closed'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_closed'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_closed'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_closed'] = '</li>';

        $this->pagination->initialize($config);

        $data = array(
            'articles' => $articles,
            'pagination' => $this->pagination->create_links(),
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/article/welcome_message", $data);
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
            show_error($this->lang->line('article_not_found'), 404);
        }

        $data = array(
            'article' => $article,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/article/view", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file article.php */
/* Location: ./application/controllers/article.php */