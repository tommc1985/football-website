<?php
require_once('_base_frontend_model.php');

/**
 * Model for Factfile
 */
class Factfile_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'view_competitive_matches';
        $this->ci->load->model('frontend/Frontend_Match_model');
    }

    /**
     * Fetch list of debuts made
     * @param  int $match    Match Object
     * @return array         Factfile Details for Match
     */
    public function fetchForMatch($match)
    {
        $data = array();

        // Record in matches against the opposition
        $data['opposition_matches'] = $this->fetchMatchCounts(
            array(
                'opposition_id' => $match->opposition_id,
            )
        );

        // Record in matches against the opposition at the same venue
        $data['opposition_venue_matches'] = $this->fetchMatchCounts(
            array(
                'opposition_id' => $match->opposition_id,
                'venue'         => $match->venue,
            )
        );

        // Record in matches against the opposition in the same competition
        $data['opposition_competition_matches'] = $this->fetchMatchCounts(
            array(
                'opposition_id'  => $match->opposition_id,
                'competition_id' => $match->competition_id,
            )
        );

        // Record in matches against the opposition in the same competition at the same venue
        $data['opposition_competition_venue_matches'] = $this->fetchMatchCounts(
            array(
                'opposition_id'  => $match->opposition_id,
                'competition_id' => $match->competition_id,
                'venue'          => $match->venue,
            )
        );

        // Record in matches in the same competition
        $data['competition_matches'] = $this->fetchMatchCounts(
            array(
                'competition_id' => $match->competition_id,
            )
        );

        // Record in matches in the same competition at the same venue
        $data['competition_venue_matches'] = $this->fetchMatchCounts(
            array(
                'competition_id' => $match->competition_id,
                'venue'          => $match->venue,
            )
        );

        return $data;
    }

    /**
     * Fetch match count values based on where conditions
     * @param  array $conditions    Other Conditions to include in query (i.e. match/player id)
     * @return array                Factfile Details for Match
     */
    public function fetchMatchCounts($conditions = array())
    {
        $this->db->select('COUNT(IF(!ISNULL(h), id, NULL)) as played,
COUNT(IF(h > a, id, NULL)) as won,
COUNT(IF(h = a, id, NULL)) as drawn,
COUNT(IF(h < a, id, NULL)) as lost,
IFNULL(SUM(h + a), 0) as total_goals,
IFNULL(SUM(h), 0) as goals_for,
IFNULL(SUM(a), 0) as goals_against,
IFNULL(AVG(h + a), 0) as average_goals,
IFNULL(AVG(h), 0) as average_goals_for,
IFNULL(AVG(a), 0) as average_goals_against', false)
            ->from($this->tableName);

        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }

        $result = $this->db->get()->result();

        return reset($result);
    }
}