<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Competition Helper
 */
class Competition_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Competition_model');

        return $ci->Competition_model->fetch($id);
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
     * Return a Competition's Name
     * @param  mixed $competition  Competition Object/Array
     * @return string              The Competition's Name
     */
    public static function name($competition)
    {
        $competition = self::_convertObject($competition);

        return $competition->name;
    }

    /**
     * Return a Competition's Short Name
     * @param  mixed $competition  Competition Object/Array
     * @return string              The Competition's Short Name
     */
    public static function shortName($competition)
    {
        $competition = self::_convertObject($competition);

        return $competition->short_name;
    }

    /**
     * Return a Competition's Abbreviation
     * @param  mixed $competition  Competition Object/Array
     * @return string              The Competition's Abbreviation
     */
    public static function abbreviation($competition)
    {
        $competition = self::_convertObject($competition);

        return $competition->abbreviation;
    }

    /**
     * Return a Competition's Type
     * @param  mixed $competition  Competition Object/Array
     * @return string              The Competition's Type
     */
    public static function type($competition)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Competition_model');

        $types = Competition_model::fetchTypes();

        if (is_object($competition)) {
            $competition = self::_convertObject($competition);

            if (isset($types[$competition->type])) {
                return $types[$competition->type];
            }
        }

        if (is_string($competition)) {
            return $types[$competition];
        }

        return $ci->lang->line('global_unknown');
    }
}