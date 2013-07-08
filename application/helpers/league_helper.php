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

    /**
     * Return a Team's current form
     * @param  string $form          Team's form
     * @param  int $numberOfMatches  Number of matches to include in form
     * @return int                   Number of points gained to selected matches
     */
    public static function calculateFormPoints($form, $numberOfMatches)
    {
        $trimmed_form = substr($form, 0, $numberOfMatches);

        return array_sum(str_split($trimmed_form));
    }

    /**
     * Return a Team's current form (formatted)
     * @param  string $form          Raw string of team's form
     * @param  int $numberOfMatches  Number of matches to include in form
     * @return int                   Number of points gained to selected matches
     */
    public static function formattedForm($form, $numberOfMatches)
    {
        $ci =& get_instance();
        $trimmed_form = substr($form, 0, $numberOfMatches);

        $trimmed_form = str_replace('3', $ci->lang->line("league_w"), $trimmed_form);
        $trimmed_form = str_replace('1', $ci->lang->line("league_d"), $trimmed_form);
        $trimmed_form = str_replace('0', $ci->lang->line("league_l"), $trimmed_form);

        return $trimmed_form;
    }
}