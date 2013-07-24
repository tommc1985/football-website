<?php
require_once('_base_model.php');

/**
 * Model for Calendar Event data
 */
class Calendar_Event_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'calendar_event';
    }

    /**
     * Process Calendar Event data from a valid submitted form
     * @return array    Prepared Calendar data
     */
    public function processData()
    {
        $data = array(
            'name'        => $this->ci->form_validation->set_value('name', NULL),
            'description' => $this->ci->form_validation->set_value('description', NULL),
            'location'    => $this->ci->form_validation->set_value('location', NULL),
        );

        $allDay = $this->ci->form_validation->set_value('all_day', NULL);
        $data['all_day'] = $allDay;

        $startDate = $this->ci->form_validation->set_value('start_date', NULL);
        $startTime = '00:00';

        $endDateTime = NULL;
        if (!$allDay) {
            $startTime = $this->ci->form_validation->set_value('start_time', NULL);
            $endDate   = $this->ci->form_validation->set_value('end_date', NULL);
            $endTime   = $this->ci->form_validation->set_value('end_time', NULL);
            $endDateTime = "{$endDate} {$endTime}:00";
        }

        $startDateTime = "{$startDate} {$startTime}:00";

        $data['start_datetime'] = $startDateTime;
        $data['end_datetime']   = $endDateTime;

        return $data;
    }

    /**
     * Insert a Calendar Event from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        $data = $this->processData();

        return $this->insertEntry($data);
    }

    /**
     * Update a Calendar Event from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        $data = $this->processData();

        return $this->updateEntry($id, $data);
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        return 'start_datetime';
    }

    /**
     * Return "asc" or "desc" depending on value passed
     * @param  string $order Either "asc" or "desc"
     * @return string        Either "asc" or "desc"
     */
    public function getOrder($order)
    {
        return 'desc';
    }

    /**
     * Apply Form Validation for Adding & Updating Calendar Events
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('name', 'Name', "trim|required|regex_match[/^[A-Za-z0-9 -']+$/]|max_length[" . $this->config->item('name_max_length', 'calendar_event') . "]|xss_clean");
        $this->ci->form_validation->set_rules('description', 'Description', "trim|regex_match[/^[A-Za-z0-9 -']+$/]|xss_clean");
        $this->ci->form_validation->set_rules('start_date', 'Start Date', 'trim|required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|xss_clean');
        $this->ci->form_validation->set_rules('start_time', 'Start Time', 'trim|regex_match[/^[0-9]{2}:[0-9]{2}$/]|callback_is_start_time_set|xss_clean');
        $this->ci->form_validation->set_rules('end_date', 'End Date', 'trim|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|callback_is_end_date_set|xss_clean');
        $this->ci->form_validation->set_rules('end_time', 'End Time', 'trim|regex_match[/^[0-9]{2}:[0-9]{2}$/]|callback_is_end_time_set|xss_clean');
        $this->ci->form_validation->set_rules("all_day", "All Day", "trim|integer|xss_clean");
        $this->ci->form_validation->set_rules('location', 'Location', "trim|max_length[" . $this->config->item('location_max_length', 'calendar_event') . "]|xss_clean");
    }

    /**
     * Fetch all Calendar Events and format for dropdown
     * @return array List of Positions
     */
    public function fetchForDropdown()
    {
        $results = $this->fetchAll();

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = $result->name;
        }

        return $dropdownOptions;
    }

    /**
     * Can the Calendar Event be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean     Can the specified Calendar Event be deleted?
     */
    public function isDeletable($id)
    {
        return true;
    }

}