<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Factfile Helper
 */
class Factfile_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * Display Strings for Factfile block
     * @param  array $paragraphs  Generated data in paragraph format
     * @return string             Details about Matches
     */
    protected static function _display($paragraphs)
    {
        return '<p>' . implode("</p>\n<p>", $paragraphs) . '</p>';
    }

    /**
     * Generate and display strings for Factfile block
     * @param  string $match      The match in question
     * @param  array $data        The raw Factfile data
     * @return array              Details about Matches
     */
    public static function generateAndDisplay($match, $data)
    {
        $ci =& get_instance();
        $ci->load->helper('number');

        $lines = array();
        $paragraphs = array();

        $metaData = array(
            'team_name' => Configuration::get('team_name'),
            'opposition_name' => Opposition_helper::name($match->opposition_id),
            'competition_name' => Competition_helper::shortName($match->competition_id),
            'competition_stage_name' => $match->competition_stage_id ? Competition_Stage_helper::name($match->competition_stage_id) : '',
            'venue' => $ci->lang->line("factfile_{$match->venue}"),
        );

        if ($data['opposition_matches']->played > 0) {
            // All meetings with Opponent
            $paragraphs[] = self::matchesDetails('opposition_matches', $metaData +  self::matchData($data['opposition_matches']));

            // All meetings with Opponent at the same venue
            $paragraphs[] = self::matchesDetails('opposition_venue_matches', $metaData + self::matchData($data['opposition_venue_matches']));

            // All meetings with Opponent in this competition
            $paragraphs[] = self::matchesDetails('opposition_competition_matches', $metaData + self::matchData($data['opposition_competition_matches']));

            // All meetings with Opponent in this competition at the same venue
            $paragraphs[] = self::matchesDetails('opposition_competition_venue_matches', $metaData + self::matchData($data['opposition_competition_venue_matches']));

        } else {
            $paragraphs[] = self::matchesDetails('opposition_matches', $metaData + self::matchData($data['opposition_matches']));
        }

        // Competition Details
        $paragraphs[] = self::matchesDetails('competition_matches', $metaData + self::matchData($data['competition_matches']));

        // Competition Details at venue
        $paragraphs[] = self::matchesDetails('competition_venue_matches', $metaData + self::matchData($data['competition_venue_matches']));

        return self::_display($paragraphs);
    }

    /**
     * Show Details relating to Matches involving opposition
     * @param  string $key        The key for the statistics
     * @param  array  $metaData   Meta Data
     * @return string             Details about Matches
     */
    public static function matchesDetails($key, $metaData)
    {
        $ci =& get_instance();

        $lines = array();

        if ($metaData['played'] > 0) {
            if ($metaData['won'] > 0) {
                if ($metaData['played'] == $metaData['won']) {
                    $lines[] = vsprintf($ci->lang->line("factfile_{$key}_n_matches_all_wins"), $metaData);
                } elseif ($metaData['lost'] > 0) {
                    $lines[] = vsprintf($ci->lang->line("factfile_{$key}_n_matches_n_wins"), $metaData);
                } else {
                    $lines[] = vsprintf($ci->lang->line("factfile_{$key}_n_matches_no_losses"), $metaData);
                }
            } else {
                if ($metaData['played'] == $metaData['drawn']) {
                    $lines[] = vsprintf($ci->lang->line("factfile_{$key}_n_matches_all_draws"), $metaData);
                } else {
                    $lines[] = vsprintf($ci->lang->line("factfile_{$key}_n_matches_no_wins"), $metaData);
                }
            }

            if ($metaData['total_goals'] > 0) {
                if ($metaData['goals_for'] > 0) {
                    if ($metaData['goals_against'] > 0) {
                        $lines[] = vsprintf($ci->lang->line("factfile_{$key}_n_goals_for_n_goals_against"), $metaData);
                    } else {
                        $lines[] = vsprintf($ci->lang->line("factfile_{$key}_n_goals_for_no_goals_against"), $metaData);
                    }
                } else {
                    $lines[] = vsprintf($ci->lang->line("factfile_{$key}_no_goals_for_n_goals_against"), $metaData);
                }

                $lines[] = vsprintf($ci->lang->line("factfile_{$key}_total_goals_with_average"), $metaData);
            } else {
                $lines[] = vsprintf($ci->lang->line("factfile_{$key}_no_goals_for_no_goals_against"), $metaData);
            }
        } else {
            $lines[] = vsprintf($ci->lang->line("factfile_{$key}_no_matches"), $metaData);
        }

        return implode(" ", $lines);
    }

    /**
     * Prepare matches data with units
     * @param  array  $matchData   Matches Data
     * @return string              Details about Matches
     */
    public static function matchData($matchData)
    {
        $matchData = (array) $matchData;

        $matchData += self::matchTextualValues($matchData, 'played');
        $matchData += self::goalTextualValues($matchData, 'total_goals');
        $matchData += self::goalTextualValues($matchData, 'goals_for');
        $matchData += self::goalTextualValues($matchData, 'goals_against');

        return $matchData;
    }

    /**
     * Prepare matches textual values
     * @param  string $key        The key for the data
     * @param  array  $matchData  Matches Data
     * @return array              Details about Matches
     */
    public static function matchTextualValues($matchData, $key)
    {
        $ci =& get_instance();

        switch ($matchData[$key]) {
            case 1:
                $matchData["{$key}_unit_text"]     = $ci->lang->line("factfile_once");
                $matchData["{$key}_times_value"]   = '';
                $matchData["{$key}_matches_value"] = $ci->lang->line("factfile_match");
                break;
            case 2:
                $matchData["{$key}_unit_text"]     = $ci->lang->line("factfile_twice");
                $matchData["{$key}_times_value"]   = '';
                $matchData["{$key}_matches_value"] = $ci->lang->line("factfile_matches");
                break;
            default :
                $matchData["{$key}_unit_text"]     = Number_helper::numberToText($matchData[$key]); // Replace number with word
                $matchData["{$key}_times_value"]   = $ci->lang->line("factfile_times");
                $matchData["{$key}_matches_value"] = $ci->lang->line("factfile_matches");
        }

        return $matchData;
    }

    /**
     * Prepare goal textual values
     * @param  string $key        The key for the data
     * @param  array  $matchData  Matches Data
     * @return array              Details about Matches
     */
    public static function goalTextualValues($matchData, $key)
    {
        $ci =& get_instance();

        $matchData["{$key}_unit_text"]   = $matchData[$key]; // Replace number with word

        switch ($matchData[$key]) {
            case 1:
                $matchData["{$key}_goals_value"] = $ci->lang->line("factfile_goal");
                $matchData["{$key}_have_value"]  = $ci->lang->line("factfile_has");
                break;
            default :
                $matchData["{$key}_goals_value"] = $ci->lang->line("factfile_goals");
                $matchData["{$key}_have_value"]  = $ci->lang->line("factfile_have");
        }

        return $matchData;
    }
}