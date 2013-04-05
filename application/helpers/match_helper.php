<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Match Helper
 */
class Match_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Match_model');

        return $ci->Match_model->fetch($id);
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
     * Return a Match's Score
     * @param  mixed $match        Match Object/Array
     * @return string              The Match's Score
     */
    public static function score($match)
    {
        $match = self::_convertObject($match);

        return "{$match->h} - {$match->a}";
    }
}