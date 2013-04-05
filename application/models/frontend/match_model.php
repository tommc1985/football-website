<?php
require_once('_base_frontend_model.php');

/**
 * Model for Match Page
 */
class Match_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'matches';
    }

    /**
     * Fetch list of players based on season, competition type, and ordered by a particular field in asc or desc order
     * @param  mixed $season   Four digit season or 'career'
     * @param  string $type    Competition type
     * @param  string $orderBy Field to order data by
     * @param  string $order   Sort Order of data ('asc' or 'desc')
     * @return array           List of Players
     */
    public function fetchMatchList($season, $type, $orderBy, $order)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('deleted', 0)
            ->order_by($orderBy, $order);

        if ($season != 'all-time') {
            $this->ci->load->model('Season_model');
            $startEndDates = Season_model::generateStartEndDates($season, NULL, NULL, false);
            $this->db->where($startEndDates);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch data on a particular player for Player Profile Page
     * @param  int $id         Player ID
     * @return object          Player Details
     */
    public function fetchPlayerDetails($id)
    {
        // Basic Player details
        $player = $this->fetch($id);

        if ($player === false) {
            return false;
        }

        // Player's Positions
        $player->positions = $this->fetchPlayerPositions($id);

        // Player's Debuts across all competition types
        $player->debut = $this->fetchPlayerDebuts($id);

        // Player's First Goals across all competition types
        $player->firstGoal = $this->fetchPlayerFirstGoals($id);

        // Player's Accumulated Season Statistics
        $player->accumulatedStatistics = $this->fetchPlayerAccumulatedStatistics($id);

        // Player's Time between Debut & First Goal
        $player->timeBetweenDebutAndFirstGoal = $this->fetchPlayerTimeBetweenDebutAndFirstGoal($id);

        // Player's Games between Debut & First Goal
        $player->gamesBetweenDebutAndFirstGoal = $this->fetchPlayerGamesBetweenDebutAndFirstGoal($id);

        return $player;
    }

    /**
     * Fetch a particular Player's Positions
     * @param  int $id         Player ID
     * @return array           Positions
     */
    public function fetchPlayerPositions($id)
    {
        $this->db->select('ptp.*')
            ->from('player_to_position ptp')
            ->join('player p', 'p.id = ptp.player_id')
            ->join('position pos', 'pos.id = ptp.position_id')
            ->where('ptp.player_id', $id)
            ->where('p.deleted', 0)
            ->where('ptp.deleted', 0)
            ->where('pos.deleted', 0)
            ->order_by('pos.sort_order', 'asc');

        $result = $this->db->get()->result();

        $positions = array();
        foreach ($result as $position) {
            $positions[] = $position;
        }

        return $positions;
    }

    /**
     * Fetch a particular Player's debuts for the Club in different competition types
     * @param  int $id         Player ID
     * @return array           Debuts
     */
    public function fetchPlayerDebuts($id)
    {
        $this->db->select('cps.*')
            ->from('cache_player_statistics cps')
            ->join('player p', 'p.id = cps.player_id')
            ->where('cps.season', 'career')
            ->where('cps.statistic_group', 'debut')
            ->where('cps.player_id', $id)
            ->where('p.deleted', 0);

        $result = $this->db->get()->result();

        $debuts = array();
        foreach ($result as $debut) {
            $debuts[$debut->type] = unserialize($debut->statistic_value);
        }

        return $debuts;
    }

    /**
     * Fetch a particular Player's first goals for the Club
     * @param  int $id         Player ID
     * @return array           First Goals
     */
    public function fetchPlayerFirstGoals($id)
    {
        $this->db->select('cps.*')
            ->from('cache_player_statistics cps')
            ->join('player p', 'p.id = cps.player_id')
            ->where('cps.season', 'career')
            ->where('cps.statistic_group', 'first_goal')
            ->where('cps.player_id', $id)
            ->where('p.deleted', 0);

        $result = $this->db->get()->result();

        $firstGoals = array();
        foreach ($result as $firstGoal) {
            $firstGoals[$firstGoal->type] = unserialize($firstGoal->statistic_value);
        }

        return $firstGoals;
    }

    /**
     * Fetch a particular Player's accumulated statistics for the Club
     * @param  int $id         Player ID
     * @return array           First Goals
     */
    public function fetchPlayerAccumulatedStatistics($id)
    {
        $this->db->select('cpas.*')
            ->from('cache_player_accumulated_statistics cpas')
            ->join('player p', 'p.id = cpas.player_id')
            ->where('cpas.player_id', $id)
            ->where('p.deleted', 0)
            ->order_by('season', 'desc');

        $result = $this->db->get()->result();

        $statistics = array();
        foreach ($result as $statistic) {
            $statistics[$statistic->season][$statistic->type] = $statistic;
        }

        return $statistics;
    }

    /**
     * Fetch a particular Player's Time between Debut & First Goal
     * @param  int $id         Player ID
     * @return array           Time between Debut & First Goal
     */
    public function fetchPlayerTimeBetweenDebutAndFirstGoal($id)
    {
        $this->db->select('cps.*')
            ->from('cache_player_statistics cps')
            ->join('player p', 'p.id = cps.player_id')
            ->where('cps.season', 'career')
            ->where('cps.statistic_group', 'debut_and_first_goal_time_difference')
            ->where('cps.player_id', $id)
            ->where('p.deleted', 0);

        $result = $this->db->get()->result();

        $times = array();
        foreach ($result as $time) {
            $times[$time->type] = unserialize($time->statistic_value);
        }

        return $times;
    }

    /**
     * Fetch a particular Player's Number of Games between Debut & First Goal
     * @param  int $id         Player ID
     * @return array           Games between Debut & First Goal
     */
    public function fetchPlayerGamesBetweenDebutAndFirstGoal($id)
    {
        $this->db->select('cps.*')
            ->from('cache_player_statistics cps')
            ->join('player p', 'p.id = cps.player_id')
            ->where('cps.season', 'career')
            ->where('cps.statistic_group', 'debut_and_first_goal_game_difference')
            ->where('cps.player_id', $id)
            ->where('p.deleted', 0);

        $result = $this->db->get()->result();

        $games = array();
        foreach ($result as $game) {
            $games[$game->type] = unserialize($game->statistic_value);
        }

        return $games;
    }

    /**
     * Return string of fields to order data by
     * @param  string $orderBy Fields passwed
     * @return string          Processed string of fields
     */
    public function getOrderBy($orderBy)
    {
        return '(date IS NULL), date';
    }

    /**
     * Return "asc" or "desc" depending on value passed
     * @param  string $order Either "asc" or "desc"
     * @return string        Either "asc" or "desc"
     */
    public function getOrder($order)
    {
        if ($order == 'desc') {
            return 'desc';
        }

        return 'asc';
    }

}