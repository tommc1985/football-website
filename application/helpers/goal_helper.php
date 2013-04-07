<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Goal Helper
 */
class Goal_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Goal_model');

        return $ci->Goal_model->fetch($id);
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
     * Return a Goal's Scorer
     * @param  mixed $goal         Goal Object/Array
     * @return string              The Goal's Scorer
     */
    public static function scorer($goal)
    {
        $ci =& get_instance();
        $ci->load->helper(array('player', 'url', 'utility'));
        $ci->lang->load('goal');

        if ($goal->scorer_id == 0) {
            return $ci->lang->line('goal_own_goal');
        }

        return Player_helper::fullName($goal->scorer_id);
    }

    /**
     * Return a Goal's Assister
     * @param  mixed $goal         Goal Object/Array
     * @return string              The Goal's Assister
     */
    public static function assister($goal)
    {
        $ci =& get_instance();
        $ci->load->helper(array('player', 'url', 'utility'));

        if ($goal->assist_id == 0) {
            return $ci->lang->line('goal_no_assist');
        }

        return Player_helper::fullName($goal->assist_id);
    }

    /**
     * Return a Goal's Type
     * @param  mixed $goal         Goal Object/Array
     * @return string              The Goal's Type
     */
    public static function type($goal)
    {
        $ci =& get_instance();
        $ci->load->model('Goal_model');

        $types = Goal_model::fetchTypes();

        if (isset($types[$goal->type])) {
            return $types[$goal->type];
        }

        return $ci->lang->line('global_unknown');
    }

    /**
     * Return a Goal's Body Part
     * @param  mixed $goal         Goal Object/Array
     * @return string              The Goal's Body Part
     */
    public static function bodyPart($goal)
    {
        $ci =& get_instance();
        $ci->load->model('Goal_model');

        $bodyParts = Goal_model::fetchBodyParts();

        if (isset($bodyParts[$goal->body_part])) {
            return $bodyParts[$goal->body_part];
        }

        return $ci->lang->line('global_unknown');
    }

    /**
     * Return a Goal's Distance
     * @param  mixed $goal         Goal Object/Array
     * @return string              The Goal's Distance
     */
    public static function distance($goal)
    {
        $ci =& get_instance();
        $ci->load->model('Goal_model');

        $distances = Goal_model::fetchDistances();

        if (isset($distances[$goal->distance])) {
            return $distances[$goal->distance];
        }

        return $ci->lang->line('global_unknown');
    }

    /**
     * Return a Goal's Rating
     * @param  mixed $goal         Goal Object/Array
     * @return string              The Goal's Rating
     */
    public static function rating($goal)
    {
        return "{$goal->rating}/" . Configuration::get('max_goal_rating');
    }
}