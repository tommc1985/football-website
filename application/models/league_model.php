<?php
class League_model extends CI_Model {

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

        $this->leagueTableName = 'league';
        $this->leagueMatchTableName = 'league_match';
        $this->leagueRegistrationTableName = 'league_registration';
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
     * @param  int $leagueId     League ID
     * @return array List of matches
     */
    public function fetchMatches($leagueId)
    {
        $this->db->select('*')
            ->from("{$this->leagueMatchTableName} lm")
            ->where('lm.league_id', $leagueId)
            ->where('lm.deleted', 0)
            ->order_by('lm.date', 'asc');

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
            ->where('lr.deleted', 0);

        return $this->db->get()->result();
    }

}