<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Award Helper
 */
class Award_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Award_model');

        return $ci->Award_model->fetch($id);
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
     * Return a Award's Long Name
     * @param  mixed $award     Award Object/Array
     * @return string           The Award's Long Name
     */
    public static function longName($award)
    {
        $award = self::_convertObject($award);

        return $award->long_name;
    }

    /**
     * Return a Award's Short Name
     * @param  mixed $award     Award Object/Array
     * @return string           The Award's Short Name
     */
    public static function shortName($award)
    {
        $award = self::_convertObject($award);

        return $award->short_name;
    }
}