<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Calendar extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Calendar_model');
        $this->lang->load('calendar');
        $this->load->helper(array('calendar', 'player', 'url', 'utility'));
    }

    /**
     * Subscribe Action
     * @return NULL
     */
    public function subscribe()
    {
        $calendarData = $this->Calendar_model->fetchData();

        $this->Calendar_model->generateCalendar($calendarData);
    }
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */