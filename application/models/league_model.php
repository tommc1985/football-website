<?php
require_once('_base_model.php');

/**
 * Model for League data
 */
class League_model extends Base_Model {

    public $leagueTableName;
    public $leagueMatchTableName;
    public $leagueRegistrationTableName;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'league';

        $this->leagueTableName = 'league';
        $this->leagueMatchTableName = 'league_match';
        $this->leagueRegistrationTableName = 'league_registration';
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        return 'name';
    }

    /**
     * Apply Form Validation for Adding & Updating Leagues
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('competition_id', 'Competition', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('season', 'Season', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('short_name', 'Short Name', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('abbreviation', 'Abbreviation', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('points_for_win', 'Points for win', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('points_for_draw', 'Points for draw', 'trim|xss_clean');
    }

    /**
     * Fetch all Leagues
     * @return array         Array of Leagues
     */
    public function fetchAllLeagues()
    {
        $this->db->select('*')
            ->from("{$this->leagueTableName} l")
            ->where('l.deleted', 0)
            ->order_by('l.id', 'asc');

        return $this->db->get()->result();

    }

    /**
     * Fetch league data
     * @param  int $leagueId  League ID
     * @return object         League Object
     */
    public function fetchLeagueData($leagueId)
    {
        $this->db->select('*')
            ->from("{$this->leagueTableName} l")
            ->where('l.id', $leagueId)
            ->where('l.deleted', 0)
            ->limit(1, 0);

        $leagueData = $this->db->get()->result();

        if ($leagueData !== false) {
            return $leagueData[0];
        }
    }

    /**
     * Fetch matches
     * @param  int $leagueId           League ID
     * @param  int|NULL $clubId        Club ID
     * @param  string|NULL $dateFrom   Date From (inclusive)
     * @param  string|NULL $dateUntil  Date Until (inclusive)
     * @return array                   List of matches
     */
    public function fetchMatches($leagueId, $clubId = NULL, $dateFrom = NULL, $dateUntil = NULL)
    {
        $this->db->select('*')
            ->from("{$this->leagueMatchTableName} lm")
            ->where('lm.league_id', $leagueId)
            ->where('lm.deleted', 0)
            ->order_by('lm.date', 'asc');

        if (!is_null($clubId)) {
            $this->db->where("(lm.h_opposition_id = {$clubId} OR lm.a_opposition_id = {$clubId})");
        }

        if (!is_null($dateFrom)) {
            $this->db->where("(lm.date >= '{$dateFrom}')", NULL, false);
        }

        if (!is_null($dateUntil)) {
            $this->db->where("(lm.date <= '{$dateUntil}')", NULL, false);
        }


        return $this->db->get()->result();
    }

    /**
     * Fetch Club Registrations
     * @param  int $leagueId     League ID
     * @return array List of Registrations
     */
    public function fetchClubRegistrations($leagueId)
    {
        $this->db->select('*')
            ->from("{$this->leagueRegistrationTableName} lr")
            ->where('lr.league_id', $leagueId)
            ->where('lr.deleted', 0);

        return $this->db->get()->result();
    }

    /**
     * Fetch Distinct Match Dates for the specified league
     * @param  int $leagueId         League ID
     * @param  boolean $resultsOnly  Results Only
     * @return array                 List of objects
     */
    public function fetchDistinctMatchDates($leagueId, $resultsOnly = true)
    {
        $this->db->select('DISTINCT(lm.date) as date')
            ->from("{$this->leagueMatchTableName} lm")
            ->where('lm.league_id', $leagueId)
            ->where('lm.deleted', 0)
            ->order_by('date', 'asc');

        if ($resultsOnly) {
            $this->db->where('(
(!ISNULL(lm.h_score)
    AND !ISNULL(lm.a_score))
OR lm.status = "hw"
OR lm.status = "aw"
)');
        }

        return $this->db->get()->result();
    }

}