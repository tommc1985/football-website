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
     * Fetch list of gGoal milestones made
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

}