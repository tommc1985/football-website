<?php

$configValues['start_month']                      = '6';
$configValues['start_day']                        = '1';

$configValues['earliest_season']                  = '1997';

$configValues['usual_match_ko_time']              = '10:30';

$configValues['starting_appearance_points']       = '2';
$configValues['substitute_appearance_points']     = '1';
$configValues['clean_sheet_by_goalkeeper_points'] = '5';
$configValues['clean_sheet_by_defender_points']   = '3';
$configValues['clean_sheet_by_midfielder_points'] = '1';
$configValues['assist_by_goalkeeper_points']      = '9';
$configValues['assist_by_defender_points']        = '7';
$configValues['assist_by_midfielder_points']      = '3';
$configValues['assist_by_striker_points']         = '5';
$configValues['goal_by_goalkeeper_points']        = '15';
$configValues['goal_by_defender_points']          = '10';
$configValues['goal_by_midfielder_points']        = '7';
$configValues['goal_by_striker_points']           = '5';
$configValues['man_of_the_match_points']          = '10';
$configValues['yellow_card_points']               = '-5';
$configValues['red_card_points']                  = '-15';


$configValues['per_page']                         = '25';

class Configuration {
    public function _construct() {}

    public static function get($key) {
        return $GLOBALS['configValues'][$key];
    }
}