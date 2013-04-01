<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Player Helper
 */
class Player_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchPlayerObject($playerId)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Player_model');

        return $ci->Player_model->fetch($playerId);
    }

    protected static function _convertPlayerObject($player)
    {
        if (!is_object($player)) {
            if (is_array($player)) {
                $player = (object) $player;
            } else {
                $player = self::_fetchPlayerObject($player);
            }
        }

        return $player;
    }

    /**
     * Return a Player's Full Name
     * @param  mixed $player  Player Object/Array
     * @return string         The Player's Full Name
     */
    public static function fullName($player)
    {
        $player = self::_convertPlayerObject($player);

        return "{$player->first_name} {$player->surname}";
    }

    /**
     * Return a Player's Full Name (in reverse)
     * @param  mixed $player  Player Object/Array
     * @return string         The Player's Full Name
     */
    public static function fullNameReverse($player)
    {
        $player = self::_convertPlayerObject($player);

        return "{$player->surname}, {$player->first_name}";
    }

    /**
     * Return a Player's Initial and Surname
     * @param  mixed $player  Player Object/Array
     * @return string         The Player's Full Name
     */
    public static function initialSurname($player)
    {
        $player = self::_convertPlayerObject($player);

        return substr($player->first_name, 0, 1) . '. ' . $player->surname;
    }
}