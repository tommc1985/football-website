<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Opposition Helper
 */
class Opposition_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Opposition_model');

        return $ci->Opposition_model->fetch($id);
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
     * Return a Opposition's Name
     * @param  mixed $opposition   Opposition Object/Array
     * @return string              The Opposition's Name
     */
    public static function name($opposition)
    {
        $opposition = self::_convertObject($opposition);

        return $opposition->name;
    }
}