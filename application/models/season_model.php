<?php
/**
 * Model for Season data
 */
class Season_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public static $startMonth;
    public static $startDay;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
        $this->ci->load->model('Match_model');

        self::$startMonth = Configuration::get('start_month');
        self::$startDay = Configuration::get('start_day');
    }

    public static function __callStatic($name, $arguments)
    {
        self::$startMonth = Configuration::get('start_month');
        self::$startDay = Configuration::get('start_day');
    }

    /**
     * Fetch current season
     * @return int                              Four digit integer
     */
    public static function fetchCurrentSeason()
    {
        return self::fetchSeason(self::$startMonth, self::$startDay);
    }

    /**
     * Fetch current season from MySQL DateTime
     * @param  string $matchDate                Date of the match
     * @return int                              Four digit integer
     */
    public static function fetchSeasonFromDateTime($matchDate)
    {
        $timestamp = strtotime($matchDate);

        return self::fetchSeason(self::$startMonth, self::$startDay, $timestamp);
    }

    /**
     * Fetch which season a particular date is in, depending upon the Timestamp supplied
     * @param  int $startMonth                  Month season starts
     * @param  int $startDay                    Day season starts
     * @param  int|NULL $comparisonTimestamp    Timestamp for comparison, leave empty for current timestamp
     * @return int                              Four digit integer
     */
    public static function fetchSeason($startMonth, $startDay, $comparisonTimestamp = NULL)
    {
        $comparisonTimestamp = is_null($comparisonTimestamp) ? time() : $comparisonTimestamp;
        $endTimestamp        = mktime(0, 0, 0, $startMonth, $startDay, date("Y", $comparisonTimestamp));

        return (int) ($comparisonTimestamp < $endTimestamp ? date("Y", $comparisonTimestamp) - 1 : date("Y", $comparisonTimestamp));
    }

    /**
     * Fetch earliest year in the system
     * @return int              The Earliest year in the system (either the earliest match date of config setting)
     */
    public function fetchEarliestYear()
    {
        $currentSeason = self::fetchCurrentSeason();

        $match = $this->ci->Match_model->fetchEarliest();

        if ($match === false) {
            return $currentSeason;
        }

        $matchTimestamp = strtotime($match->date);

        $databaseEarliestYear = self::fetchSeason(self::$startMonth, self::$startDay, $matchTimestamp);

        return $databaseEarliestYear < $currentSeason ? $databaseEarliestYear : $currentSeason;
    }

    /**
     * Fetch all competition types
     * @return results Query Object
     */
    public function fetchCompetitionTypes()
    {
        $this->db->select('DISTINCT(competition.type) as competition_type')
            ->from('competition')
            ->where('deleted = 0');

        $rows = $this->db->get()->result();

        $types = array();
        foreach ($rows as $row) {
            $types[] = $row->competition_type;
        }

        return $types;
    }

    /**
     * Generate Start/End dates for SQL statements
     * @param  int $season      Four digit integer for the season
     * @param  int $startMonth  Month season starts
     * @param  int $startDay    May season starts
     * @return int              Four digit integer
     */
    public static function generateStartEndDates($season, $startMonth = NULL, $startDay = NULL)
    {
        $startMonth = (int) (is_null($startMonth) ? self::$startMonth : $startMonth);
        $startDay   = (int) (is_null($startDay) ? self::$startDay : $startDay);

        $startMonth = str_pad($startMonth, 2, 0, STR_PAD_LEFT);
        $startDay   = str_pad($startDay, 2, 0, STR_PAD_LEFT);

        return array('startDate' => ">= '{$season}-{$startMonth}-{$startDay} 00:00:00'",
            'endDate' => "< '" . ($season + 1) . "-{$startMonth}-{$startDay} 00:00:00'");
    }

    /**
     * Fetch list of matches matches stored in the system
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  string|NULL $venue   Whether to include all, home, away or neutral venues
     * @return array|object   Earliest match/matches
     */
    public function fetchMatches($type = false, $season = NULL, $venue = NULL)
    {
        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $whereConditions[] = "(m.competitive = 1)";

        $sql = "SELECT m.*
FROM view_competitive_matches m" . (count($whereConditions) > 0 ? "
WHERE " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.date ASC";

        $query = $this->db->query($sql);

        return $query->result();
    }

    /**
     * Fetch all seasons and return an array for dropdown menu
     * @return array                 List of season
     */
    public function fetchForDropdown()
    {
        $options = array();

        $i = $this->fetchCurrentSeason();
        while ($i >= Configuration::get('earliest_season')) {
            $options[$i] = $i . "/" . ($i + 1) ;

            $i--;
        }

        return $options;
    }

}