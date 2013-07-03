<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fantasy Football Helper
 */
class Fantasy_Football_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * Return string with number suffix removed from position string
     * @param  string $position    Position with number suffix
     * @param  boolean $upperCase  Return string in upper case?
     * @return string              Position without number suffix
     */
    public static function fetchSimplePosition($position, $upperCase = false)
    {
        $position = rtrim($position, '0123456789');

        if ($upperCase) {
            return strtoupper($position);
        }

        return $position;
    }
}