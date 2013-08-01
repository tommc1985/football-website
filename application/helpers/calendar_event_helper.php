<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Calendar Event Helper
 */
class Calendar_Event_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Calendar_Event_model');

        return $ci->Calendar_Event_model->fetch($id);
    }

    /**
     * Convert Object
     * @param  object $object Passed Object
     * @return object         Returned Object
     */
    protected static function _convertObject($object)
    {
        if (!is_object($object)) {
            if (is_array($object)) {
                $object = (object) $object;
            } else {
                $object = self::_fetchObject($object);
            }
        }

        return $object;
    }

    /**
     * Return a Calendar Event's Name
     * @param  mixed $calendarEvent  Calendar Event Object/Array
     * @return string                The Calendar Event's Name
     */
    public static function name($calendarEvent)
    {
        $calendarEvent = self::_convertObject($calendarEvent);

        return "{$calendarEvent->name}";
    }

    /**
     * Return a Calendar Event's Start
     * @param  mixed $calendarEvent  Calendar Event Object/Array
     * @return string                The Calendar Event's Start
     */
    public static function start($calendarEvent)
    {
        $ci =& get_instance();
        $ci->load->helper('utility');

        $calendarEvent = self::_convertObject($calendarEvent);

        if ($calendarEvent->all_day) {
            return Utility_helper::formattedDate($calendarEvent->start_datetime, "D jS F Y");
        }

        return Utility_helper::formattedDate($calendarEvent->start_datetime, "D jS F Y, g.ia");
    }

    /**
     * Return a Calendar Event's End
     * @param  mixed $calendarEvent  Calendar Event Object/Array
     * @return string                The Calendar Event's End
     */
    public static function end($calendarEvent)
    {
        $ci =& get_instance();
        $ci->load->helper('utility');

        $calendarEvent = self::_convertObject($calendarEvent);

        if ($calendarEvent->all_day) {
            return '&nbsp;';
        }

        return Utility_helper::formattedDate($calendarEvent->end_datetime, "D jS F Y, g.ia");
    }
}