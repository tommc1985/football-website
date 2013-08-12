<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Page extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Content_model');
        $this->lang->load('page');
        $this->load->helper(array('url', 'utility'));
    }

    /**
     * Index Action
     * @return NULL
     */
    public function index()
    {
        show_error($this->lang->line('page_not_found'), 404);
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
            show_error($this->lang->line('page_not_found'), 404);
        }

        $this->templateData['article']         = $article;
        $this->templateData['metaTitle']       = $article->title;
        $this->templateData['metaDescription'] = Utility_helper::truncate($article->content, 200);

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/page/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */