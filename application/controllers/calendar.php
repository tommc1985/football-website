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
     * Subscribe to Combined Calendars Action
     * @return NULL
     */
    public function combined()
    {
        $calendarData = $this->Calendar_model->fetchData(array(
            'players',
            'matches',
            'events',
        ));

        $calendarName = sprintf($this->lang->line('calendar_combined_calendar'), Configuration::get('team_name'));

        $this->Calendar_model->generateCalendar($calendarData, $calendarName);
    }

    /**
     * Subscribe to Players' Birthdays Calendar Action
     * @return NULL
     */
    public function player_birthdays()
    {
        $calendarData = $this->Calendar_model->fetchData(array(
            'players',
        ));

        $calendarName = sprintf($this->lang->line('calendar_players_birthday_calendar'), Configuration::get('team_name'));

        $this->Calendar_model->generateCalendar($calendarData, $calendarName);
    }

    /**
     * Subscribe to Fixtures & Results Calendar Action
     * @return NULL
     */
    public function fixtures_and_results()
    {
        $calendarData = $this->Calendar_model->fetchData(array(
            'matches',
        ));

        $calendarName = sprintf($this->lang->line('calendar_fixtures_and_results_calendar'), Configuration::get('team_name'));

        $this->Calendar_model->generateCalendar($calendarData, $calendarName);
    }

    /**
     * Subscribe to Events Calendar Action
     * @return NULL
     */
    public function events()
    {
        $calendarData = $this->Calendar_model->fetchData(array(
            'events',
        ));

        $calendarName = sprintf($this->lang->line('calendar_event_calendar'), Configuration::get('team_name'));

        $this->Calendar_model->generateCalendar($calendarData, $calendarName);
    }
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */