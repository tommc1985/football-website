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
     * Insert a League from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'competition_id' => $this->ci->form_validation->set_value('competition_id', NULL),
            'season' => $this->ci->form_validation->set_value('season', NULL),
            'name' => $this->ci->form_validation->set_value('name', NULL),
            'short_name' => $this->ci->form_validation->set_value('short_name', NULL),
            'abbreviation' => $this->ci->form_validation->set_value('abbreviation', NULL),
            'points_for_win' => $this->ci->form_validation->set_value('points_for_win', NULL),
            'points_for_draw' => $this->ci->form_validation->set_value('points_for_draw', NULL),
        ));
    }

    /**
     * Update a League from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'competition_id' => $this->ci->form_validation->set_value('competition_id', NULL),
            'season' => $this->ci->form_validation->set_value('season', NULL),
            'name' => $this->ci->form_validation->set_value('name', NULL),
            'short_name' => $this->ci->form_validation->set_value('short_name', NULL),
            'abbreviation' => $this->ci->form_validation->set_value('abbreviation', NULL),
            'points_for_win' => $this->ci->form_validation->set_value('points_for_win', NULL),
            'points_for_draw' => $this->ci->form_validation->set_value('points_for_draw', NULL),
        ));
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        switch ($orderBy) {
            case 'season':
                return 'season desc, name';
                break;
        }

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
        $this->ci->form_validation->set_rules('season', 'Season', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('name', 'Name', "trim|required|max_length[" . $this->config->item('name_max_length', 'league') . "]|xss_clean");
        $this->ci->form_validation->set_rules('short_name', 'Short Name', "trim|required|max_length[" . $this->config->item('short_name_max_length', 'league') . "]|xss_clean");
        $this->ci->form_validation->set_rules('abbreviation', 'Abbreviation', "trim|required|max_length[" . $this->config->item('abbreviation_max_length', 'league') . "]|regex_match[/^[A-Za-z0-9']+$/]|strtoupper|xss_clean");
        $this->ci->form_validation->set_rules('points_for_win', 'Points for win', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('points_for_draw', 'Points for draw', 'trim|required|integer|xss_clean');
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

    /**
     * Fetch all values and return an array for dropdown menu
     * @return array                 List of objects
     */
    public function fetchForDropdown()
    {
        $rows = $this->fetchAll(false, false, 'season', 'asc');

        $options = array();

        foreach ($rows as $row) {
            $options[$row->id] = $row->season . "/" . ($row->season + 1) . " - " . $row->short_name;
        }

        return $options;
    }

    /**
     * Can the League be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified League be deleted?
     */
    public function isDeletable($id)
    {
        $ci =& get_instance();
        $ci->load->model('League_Match_model');
        $ci->load->model('League_Registration_model');

        $leagueMatches = $ci->League_Match_model->fetchAllByField('league_id', $id);
        $leagueRegistrations = $ci->League_Registration_model->fetchAllByField('league_id', $id);

        if ($leagueMatches || $leagueRegistrations) {
            return false;
        }

        return true;
    }

}