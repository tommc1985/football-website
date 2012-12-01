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
     * Fetch which season a particular date is in, depending upon the Timestamp supplied
     * @param  int $startMonth                  Month season starts
     * @param  int $startDay                    May season starts
     * @param  int|NULL $comparisonTimestamp    Timestamp for comparison, leave empty for current timestamp
     * @return int                              Four digit integer
     */
    public static function fetchSeason($startMonth, $startDay, $comparisonTimestamp = NULL)
    {
        $comparisonTimestamp = is_null($comparisonTimestamp) ? time() : $comparisonTimestamp;
        $endTimestamp        = mktime(0, 0, 0, $startMonth, $startDay);

        return (int) ($comparisonTimestamp < $endTimestamp ? date("Y", $comparisonTimestamp) - 1 : date("Y", $comparisonTimestamp));
    }

    public function fetchEarliestYear()
    {
        $match = $this->ci->Match_model->fetchEarliest(1);

        if (is_array($match)) {
            return self::fetchCurrentSeason();
        }

        $matchTimestamp = strtotime($match->date);

        return self::fetchSeason(self::$startMonth, self::$startDay, $matchTimestamp);
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
     * @param  integer $limit Number of matches to return
     * @return array|object   Earliest match/matches
     */
    public function fetchMatches($type = false, $season = NULL)
    {
        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }
        $whereConditions[] = "(c.competitive = 1)";

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation
FROM matches m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
" . (count($whereConditions) > 0 ? "
WHERE " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND c.competitive = 1
    AND m.deleted = 0
    AND o.deleted = 0
    AND c.deleted = 0
    AND cs.deleted = 0
ORDER BY m.date ASC";

        $query = $this->db->query($sql);

        return $query->result();
    }

}