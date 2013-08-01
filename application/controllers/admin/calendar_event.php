<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Calendar Events
 */
class Calendar_Event extends Backend_Controller
{
    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->model('Calendar_Event_model');
        $this->load->config('calendar_event', true);

        $this->lang->load('calendar_event');
        $this->load->helper('calendar_event');
    }

    /**
     * Index Action - Show Paginated List of Calendar Events
     * @return NULL
     */
    public function index()
    {
        $this->load->library('pagination');
        $this->load->helper('pagination');

        $parameters = $this->uri->uri_to_assoc(4, array('offset', 'order-by', 'order'));

        $perPage = Configuration::get('per_page');
        $offset = false;
        if ($parameters['offset'] !== false && $parameters['offset'] > 0) {
            $offset = $parameters['offset'];
        }

        $data['calendarEvents'] = $this->Calendar_Event_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = site_url('admin/calendar-event/index'). '/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->Calendar_Event_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;
        $config + Pagination_helper::settings();

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/calendar-event/index', $data);
    }

    /**
     * Add Action - Add a Calendar Event
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = $this->lang->line('calendar_event_add');

        $this->Calendar_Event_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Calendar_Event_model->processInsert();

            $calendarEvent = $this->Calendar_Event_model->fetch($insertId);

            $this->session->set_flashdata('message', sprintf($this->lang->line('calendar_event_added'), $calendarEvent->name));
            redirect('/admin/calendar-event');
        }

        $this->load->view('admin/calendar-event/add', $data);
    }

    /**
     * Edit Action - Edit a Calendar Event
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('calendar_event_save');

        $calendarEvent = false;
        if ($parameters['id'] !== false) {
            $calendarEvent = $this->Calendar_Event_model->fetch($parameters['id']);
        }

        if (empty($calendarEvent)) {
            $this->load->view('admin/calendar-event/not_found', $data);
            return;
        }

        $this->Calendar_Event_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Calendar_Event_model->processUpdate($parameters['id']);

            $calendarEvent = $this->Calendar_Event_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', sprintf($this->lang->line('calendar_event_updated'), $calendarEvent->name));
            redirect('/admin/calendar-event');
        }

        $data['calendarEvent'] = $calendarEvent;

        $this->load->view('admin/calendar-event/edit', $data);
    }

    /**
     * Delete Action - Delete a Calendar Event
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $calendarEvent = false;
        if ($parameters['id'] !== false) {
            $calendarEvent = $this->Calendar_Event_model->fetch($parameters['id']);
        }

        if (empty($calendarEvent)) {
            $this->load->view('admin/calendar-event/not_found', $data);
            return;
        }

        $data['calendarEvent'] = $calendarEvent;

        if (!$this->Calendar_Event_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/calendar-event/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->Calendar_Event_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', sprintf($this->lang->line('calendar_event_deleted'), $calendarEvent->name));
            redirect('/admin/calendar-event');
        }

        $this->load->view('admin/calendar-event/delete', $data);
    }

    /**
     * Has the "All Day" check been set and if so, Start Time is "required"
     * @param  int  $value    Start Time Value
     * @return boolean        Has a Start Time value been entered (if it should be)
     */
    public function is_start_time_set($value)
    {
        $allDayValue = $this->input->post("all_day");

        if (!$allDayValue) {
            if (!$value) {
                $this->form_validation->set_message('is_start_time_set', $this->lang->line('calendar_event_start_time_missing'));
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Has the "All Day" check been set and if so, End Date is "required"
     * @param  int  $value    End Date Value
     * @return boolean        Has a End Date value been entered (if it should be)
     */
    public function is_end_date_set($value)
    {
        $allDayValue = $this->input->post("all_day");

        if (!$allDayValue) {
            if (!$value) {
                $this->form_validation->set_message('is_end_date_set', $this->lang->line('calendar_event_end_date_missing'));
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Has the "All Day" check been set and if so, End Time is "required"
     * @param  int  $value    End Time Value
     * @return boolean        Has a End Time value been entered (if it should be)
     */
    public function is_end_time_set($value)
    {
        $allDayValue = $this->input->post("all_day");

        if (!$allDayValue) {
            if (!$value) {
                $this->form_validation->set_message('is_end_time_set', $this->lang->line('calendar_event_end_time_missing'));
                return FALSE;
            }
        }

        return TRUE;
    }
}

/* End of file calendar_event.php */
/* Location: ./application/controllers/admin/calendar_event.php */