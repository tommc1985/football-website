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

    public static $startMonth = 6;
    public static $startDay = 1;

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

}