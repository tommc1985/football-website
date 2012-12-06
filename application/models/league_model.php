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
     * @param  int $leagueId      League ID
     * @param  int|NULL $clubId  Club ID
     * @return array              List of matches
     */
    public function fetchMatches($leagueId, $clubId = NULL)
    {
        $this->db->select('*')
            ->from("{$this->leagueMatchTableName} lm")
            ->where('lm.league_id', $leagueId)
            ->where('lm.deleted', 0)
            ->order_by('lm.date', 'asc');

        if (!is_null($clubId)) {
            $this->db->where("(lm.h_opposition_id = {$clubId} OR lm.a_opposition_id = {$clubId})");
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

}