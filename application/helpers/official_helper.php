<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Official Helper
 */
class Official_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Official_model');

        return $ci->Official_model->fetch($id);
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
     * Return a Official's Full Name
     * @param  mixed $official  Official Object/Array
     * @return string           The Official's Full Name
     */
    public static function fullName($official)
    {
        $official = self::_convertObject($official);

        return "{$official->first_name} {$official->surname}";
    }

    /**
     * Return a Official's Full Name (in reverse)
     * @param  mixed $official  Official Object/Array
     * @return string           The Official's Full Name
     */
    public static function fullNameReverse($official)
    {
        $official = self::_convertObject($official);

        return "{$official->surname}, {$official->first_name}";
    }

    /**
     * Return a Official's Initial and Surname
     * @param  mixed $official  Official Object/Array
     * @return string           The Official's Full Name
     */
    public static function initialSurname($official)
    {
        $official = self::_convertObject($official);

        return substr($official->first_name, 0, 1) . '. ' . $official->surname;
    }
}