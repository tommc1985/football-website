<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Position Helper
 */
class Position_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Position_model');

        return $ci->Position_model->fetch($id);
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
     * Return a Position's Long Name
     * @param  mixed $position     Position Object/Array
     * @return string              The Position's Long Name
     */
    public static function name($position)
    {
        $position = self::_convertObject($position);

        return $position->long_name;
    }

    /**
     * Return a Position's Abbreviation
     * @param  mixed $position     Position Object/Array
     * @return string              The Position's Abbreviation
     */
    public static function abbreviation($position)
    {
        $position = self::_convertObject($position);

        return $position->abbreviation;
    }
}