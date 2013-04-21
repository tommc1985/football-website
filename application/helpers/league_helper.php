<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * League Helper
 */
class League_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('League_model');

        return $ci->League_model->fetch($id);
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
     * Return a League's Name
     * @param  mixed $league       League Object/Array
     * @return string              The League's Name
     */
    public static function name($league)
    {
        $league = self::_convertObject($league);

        return $league->name;
    }

    /**
     * Return a League's Short Name
     * @param  mixed $league       League Object/Array
     * @return string              The League's Short Name
     */
    public static function shortName($league)
    {
        $league = self::_convertObject($league);

        return $league->short_name;
    }
}