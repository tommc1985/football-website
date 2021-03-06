<?php
$configValues['start_month']                      = '6';
$configValues['start_day']                        = '1';

$configValues['earliest_season']                  = '1997';

$configValues['usual_match_ko_time']              = '10:30';

$configValues['starting_appearance_points']       = '5';
$configValues['substitute_appearance_points']     = '2';
$configValues['clean_sheet_by_goalkeeper_points'] = '15';
$configValues['clean_sheet_by_defender_points']   = '10';
$configValues['clean_sheet_by_midfielder_points'] = '5';
$configValues['assist_by_goalkeeper_points']      = '10';
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

$configValues['max_appearance_rating']            = '100';
$configValues['max_goal_rating']                  = '100';

$configValues['max_shirt_number']                 = '100';

$configValues['max_minute']                       = '120';


$configValues['include_appearance_ratings']       = false;
$configValues['include_appearance_shirt_numbers'] = false;
$configValues['include_goal_ratings']             = false;

$configValues['include_genders']                  = false;
$configValues['include_nationalities']            = false;

$configValues['include_match_attendances']        = true;


$configValues['articles_per_page']                = 6;

$configValues['default_threshold']                = 60;

$configValues['form_match_count']                 = 6;

$configValues['motm_placings']                    = 3;

$configValues['team_name']                        = 'Hardy Athletic';

$configValues['cache_key']                        = 'key';

$configValues['google_maps_api_key']              = 'AIzaSyBkd8POuYkRkbMUXmBLdUnoYBR-TXBnWnU';

$configValues['google_analytics_tracking_id']     = 'UA-10509683-1';

class Configuration {
    public function _construct() {}

    public static function get($key) {
        return $GLOBALS['configValues'][$key];
    }
}