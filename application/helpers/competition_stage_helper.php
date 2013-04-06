<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Competition Stage Helper
 */
class Competition_Stage_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Competition_Stage_model');

        return $ci->Competition_Stage_model->fetch($id);
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
     * Return a Competition Stage's Name
     * @param  mixed $competitionStage  Competition Stage Object/Array
     * @return string                   The Competition Stage's Name
     */
    public static function name($competitionStage)
    {
        $competitionStage = self::_convertObject($competitionStage);

        return $competitionStage->name;
    }

    /**
     * Return a Competition Stage's Abbreviation
     * @param  mixed $competitionStage  Competition Stage Object/Array
     * @return string                   The Competition Stage's Abbreviation
     */
    public static function abbreviation($competitionStage)
    {
        $competitionStage = self::_convertObject($competitionStage);

        return $competitionStage->abbreviation;
    }
}