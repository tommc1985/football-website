<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Nationality Helper
 */
class Nationality_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Nationality_model');

        return $ci->Nationality_model->fetch($id);
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
     * Return a Nationality
     * @param  mixed $nationality     Nationality Object/Array
     * @return string                 The Nationality
     */
    public static function nationality($nationality)
    {
        $nationality = self::_convertObject($nationality);

        if ($nationality !== false) {
            return $nationality->nationality;
        }

        $ci =& get_instance();
        $ci->lang->load('global');

        return $ci->lang->line('global_unknown');
    }
}