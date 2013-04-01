<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Card Helper
 */
class Card_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Card_model');

        return $ci->Card_model->fetch($id);
    }

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
     * Return a Card's Offence
     * @param  mixed $card  Card Object/Array
     * @return string       The Card's Type
     */
    public static function offence($card)
    {
        $card = self::_convertObject($card);

        $offences = Card_model::fetchOffences();

        if (isset($offences[$card->offence])) {
            return $offences[$card->offence]['offence'];
        }

        return 'Unknown';
    }
}