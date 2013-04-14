<?php
require_once('_base_frontend_model.php');

/**
 * Model for Milestones
 */
class Milestone_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'cache_player_milestones';
    }

    /**
     * Fetch list of debuts made
     * @param  string $conditions    Other Conditions to include in query (i.e. match/player id)
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of Debuts that were made
     */
    public function fetchDebutPast($conditions = array(), $season = 'career', $type = 'overall')
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('type', $type)
            ->where('season', $season)
            ->where('statistic_group', 'nth_appearance')
            ->where('statistic_key', 1);

        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch list of possible debuts
     * @param  string $matchSeason   Season the match is in
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of possible debuts
     */
    public function fetchDebutFuture($matchSeason, $season = 'career', $type = 'overall')
    {
        $this->db->select("*, 'nth_appearance' as statistic_group, cpas.appearances as statistic_key", false)
            ->from('player_registration pr')
            ->join('cache_player_accumulated_statistics cpas', 'pr.player_id = cpas.player_id')
            ->where('pr.season', $matchSeason)
            ->where('cpas.season', $season)
            ->where('cpas.type', $type)
            ->where('cpas.appearances', 0)
            ->where('pr.deleted', 0);

        return $this->db->get()->result();
    }

    /**
     * Fetch list of Appearance milestones made
     * @param  string $conditions    Other Conditions to include in query (i.e. match/player id)
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of Appearance Milestones that were made
     */
    public function fetchAppearancePast($conditions = array(), $season = 'career', $type = 'overall')
    {
        $milestones = array(1, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80);
        if ($season == 'career') {
            $milestones = array(25, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300, 325, 350, 375, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000);
        }

        $this->db->select('*')
            ->from($this->tableName)
            ->where('type', $type)
            ->where('season', $season)
            ->where('statistic_group', 'nth_appearance')
            ->where_in('statistic_key', $milestones);

        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch list of possible Appearance milestones
     * @param  string $matchSeason   Season the match is in
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of possible Appearance milestones
     */
    public function fetchAppearanceFuture($matchSeason, $season = 'career', $type = 'overall')
    {
        $milestones = array(4, 9, 14, 19, 24, 29, 34, 39, 44, 49, 54, 59, 64, 69, 70, 79);
        if ($season == 'career') {
            $milestones = array(24, 49, 74, 99, 124, 149, 174, 199, 224, 249, 274, 299, 324, 349, 374, 399, 449, 499, 549, 599, 649, 699, 749, 799, 849, 899, 949, 999);
        }

        $this->db->select("*, 'nth_appearance' as statistic_group, cpas.appearances as statistic_key", false)
            ->from('player_registration pr')
            ->join('cache_player_accumulated_statistics cpas', 'pr.player_id = cpas.player_id')
            ->where('pr.season', $matchSeason)
            ->where('cpas.season', $season)
            ->where('cpas.type', $type)
            ->where_in('cpas.appearances', $milestones)
            ->where('pr.deleted', 0);

        return $this->db->get()->result();
    }

    /**
     * Fetch list of Goal milestones made
     * @param  string $conditions    Other Conditions to include in query (i.e. match/player id)
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of Goal Milestones that were made
     */
    public function fetchGoalPast($conditions = array(), $season = 'career', $type = 'overall')
    {
        $milestones = array(1, 2, 3, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100);
        if ($season == 'career') {
            $milestones = array(1, 10, 25, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300, 325, 350, 375, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000);
        }

        $this->db->select('*')
            ->from($this->tableName)
            ->where('type', $type)
            ->where('season', $season)
            ->where('statistic_group', 'nth_goal')
            ->where_in('statistic_key', $milestones);

        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch list of possible Goal milestones
     * @param  string $matchSeason   Season the match is in
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of possible Goal milestones
     */
    public function fetchGoalFuture($matchSeason, $season = 'career', $type = 'overall')
    {
        $milestones = array(0, 1, 2, 4, 9, 14, 19, 24, 29, 34, 39, 44, 49, 54, 59, 64, 69, 74, 79, 84, 89, 94, 99);
        if ($season == 'career') {
            $milestones = array(0, 9, 24, 49, 74, 99, 124, 149, 174, 199, 224, 249, 274, 299, 324, 349, 374, 399, 449, 499, 549, 599, 649, 699, 749, 799, 849, 899, 949, 999);
        }

        $this->db->select("*, 'nth_goal' as statistic_group, cpas.goals as statistic_key", false)
            ->from('player_registration pr')
            ->join('cache_player_accumulated_statistics cpas', 'pr.player_id = cpas.player_id')
            ->where('pr.season', $matchSeason)
            ->where('cpas.season', $season)
            ->where('cpas.type', $type)
            ->where_in('cpas.goals', $milestones)
            ->where('pr.deleted', 0);

        return $this->db->get()->result();
    }

    /**
     * Fetch list of Assist milestones made
     * @param  string $conditions    Other Conditions to include in query (i.e. match/player id)
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of Assist Milestones that were made
     */
    public function fetchAssistPast($conditions = array(), $season = 'career', $type = 'overall')
    {
        $milestones = array(1, 2, 3, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100);
        if ($season == 'career') {
            $milestones = array(1, 10, 25, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300, 325, 350, 375, 400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 900, 950, 1000);
        }

        $this->db->select('*')
            ->from($this->tableName)
            ->where('type', $type)
            ->where('season', $season)
            ->where('statistic_group', 'nth_assist')
            ->where('player_id !=', 0)
            ->where_in('statistic_key', $milestones);

        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch list of possible Assist milestones
     * @param  string $matchSeason   Season the match is in
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of possible Assist milestones
     */
    public function fetchAssistFuture($matchSeason, $season = 'career', $type = 'overall')
    {
        $milestones = array(0, 1, 2, 4, 9, 14, 19, 24, 29, 34, 39, 44, 49, 54, 59, 64, 69, 74, 79, 84, 89, 94, 99);
        if ($season == 'career') {
            $milestones = array(0, 9, 24, 49, 74, 99, 124, 149, 174, 199, 224, 249, 274, 299, 324, 349, 374, 399, 449, 499, 549, 599, 649, 699, 749, 799, 849, 899, 949, 999);
        }

        $this->db->select("*, 'nth_assist' as statistic_group, cpas.assists as statistic_key", false)
            ->from('player_registration pr')
            ->join('cache_player_accumulated_statistics cpas', 'pr.player_id = cpas.player_id')
            ->where('pr.season', $matchSeason)
            ->where('cpas.season', $season)
            ->where('cpas.type', $type)
            ->where_in('cpas.assists', $milestones)
            ->where('pr.deleted', 0);

        return $this->db->get()->result();
    }

    /**
     * Fetch list of Yellow Card milestones made
     * @param  string $conditions    Other Conditions to include in query (i.e. match/player id)
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of Yellow Card Milestones that were made
     */
    public function fetchYellowCardPast($conditions = array(), $season = 'career', $type = 'overall')
    {
        $milestones = array(1, 2, 3, 5, 10, 15, 20, 25, 30);
        if ($season == 'career') {
            $milestones = array(1, 5, 10, 25, 50, 75, 100, 125, 150, 175, 200, 225, 250);
        }

        $this->db->select('*')
            ->from($this->tableName)
            ->where('type', $type)
            ->where('season', $season)
            ->where('statistic_group', 'nth_yellow_card')
            ->where_in('statistic_key', $milestones);

        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch list of possible Yellow Card milestones
     * @param  string $matchSeason   Season the match is in
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of possible Yellow Card milestones
     */
    public function fetchYellowCardFuture($matchSeason, $season = 'career', $type = 'overall')
    {
        $milestones = array(0, 1, 2, 4, 9, 14, 19, 24, 29);
        if ($season == 'career') {
            $milestones = array(0, 4, 9, 24, 49, 74, 99, 124, 149, 174, 199, 224, 249);
        }

        $this->db->select("*, 'nth_yellow_card' as statistic_group, cpas.yellows as statistic_key", false)
            ->from('player_registration pr')
            ->join('cache_player_accumulated_statistics cpas', 'pr.player_id = cpas.player_id')
            ->where('pr.season', $matchSeason)
            ->where('cpas.season', $season)
            ->where('cpas.type', $type)
            ->where_in('cpas.yellows', $milestones)
            ->where('pr.deleted', 0);

        return $this->db->get()->result();
    }

    /**
     * Fetch list of Red Card milestones made
     * @param  string $conditions    Other Conditions to include in query (i.e. match/player id)
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of Red Card Milestones that were made
     */
    public function fetchRedCardPast($conditions = array(), $season = 'career', $type = 'overall')
    {
        $milestones = array(1, 2, 3, 5, 10, 15);
        if ($season == 'career') {
            $milestones = array(1, 2, 3, 5, 10, 25, 50, 75, 100);
        }

        $this->db->select('*')
            ->from($this->tableName)
            ->where('type', $type)
            ->where('season', $season)
            ->where('statistic_group', 'nth_red_card')
            ->where_in('statistic_key', $milestones);

        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch list of possible Red Card milestones
     * @param  string $matchSeason   Season the match is in
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of possible Red Card milestones
     */
    public function fetchRedCardFuture($matchSeason, $season = 'career', $type = 'overall')
    {
        $milestones = array(0, 1, 2, 4, 9, 14);
        if ($season == 'career') {
            $milestones = array(0, 1, 2, 4, 9, 24, 49, 74, 99);
        }

        $this->db->select("*, 'nth_red_card' as statistic_group, cpas.reds as statistic_key", false)
            ->from('player_registration pr')
            ->join('cache_player_accumulated_statistics cpas', 'pr.player_id = cpas.player_id')
            ->where('pr.season', $matchSeason)
            ->where('cpas.season', $season)
            ->where('cpas.type', $type)
            ->where_in('cpas.reds', $milestones)
            ->where('pr.deleted', 0);

        return $this->db->get()->result();
    }

    /**
     * Fetch list of MotM milestones made
     * @param  string $conditions    Other Conditions to include in query (i.e. match/player id)
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of MotM Milestones that were made
     */
    public function fetchMotmPast($conditions = array(), $season = 'career', $type = 'overall')
    {
        $milestones = array(1, 2, 3, 5, 10, 15, 20, 25, 30);
        if ($season == 'career') {
            $milestones = array(1, 5, 10, 25, 50, 75, 100, 125, 150, 175, 200, 225, 250);
        }

        $this->db->select('*')
            ->from($this->tableName)
            ->where('type', $type)
            ->where('season', $season)
            ->where('statistic_group', 'nth_motm')
            ->where_in('statistic_key', $milestones);

        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch list of possible Man of the Match milestones
     * @param  string $matchSeason   Season the match is in
     * @param  mixed $season         Four digit season or 'career'
     * @param  string $type          Competition type
     * @return array                 List of possible Red Card milestones
     */
    public function fetchMotmFuture($matchSeason, $season = 'career', $type = 'overall')
    {
        $milestones = array(0, 1, 2, 4, 9, 14, 19, 24, 29);
        if ($season == 'career') {
            $milestones = array(0, 4, 9, 24, 49, 74, 99, 124, 149, 174, 199, 224, 249);
        }

        $this->db->select("*, 'nth_motm' as statistic_group, cpas.motms as statistic_key", false)
            ->from('player_registration pr')
            ->join('cache_player_accumulated_statistics cpas', 'pr.player_id = cpas.player_id')
            ->where('pr.season', $matchSeason)
            ->where('cpas.season', $season)
            ->where('cpas.type', $type)
            ->where_in('cpas.motms', $milestones)
            ->where('pr.deleted', 0);

        return $this->db->get()->result();
    }

}