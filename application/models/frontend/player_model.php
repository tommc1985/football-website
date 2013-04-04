<?php
require_once('_base_frontend_model.php');

/**
 * Model for Player Page
 */
class Player_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'player';
    }

    /**
     * Fetch list of players based on season, competition type, and ordered by a particular field in asc or desc order
     * @param  mixed $season   Four digit season or 'career'
     * @param  string $type    Competition type
     * @param  string $orderBy Field to order data by
     * @param  string $order   Sort Order of data ('asc' or 'desc')
     * @return array           List of Players
     */
    public function fetchPlayerList($season, $type, $orderBy, $order)
    {
        $this->db->select('p.*, cpas.*')
            ->from('cache_player_accumulated_statistics cpas')
            ->join('player p', 'p.id = cpas.player_id')
            ->where('cpas.type', $type)
            ->where('cpas.season', $season)
            ->where('p.deleted', 0)
            ->order_by($orderBy, $order);

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

        // Player's Debuts across all competition types
        $player->debut = $this->fetchPlayerDebuts($id);

        // Player's First Goals across all competition types
        $player->firstGoal = $this->fetchPlayerFirstGoals($id);

        // Player's Accumulated Season Statistics
        $player->accumulatedStatistics = $this->fetchPlayerAccumulatedStatistics($id);

        return $player;
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
     * Return string of fields to order data by
     * @param  string $orderBy Fields passwed
     * @return string          Processed string of fields
     */
    public function getOrderBy($orderBy)
    {
        switch ($orderBy) {
            case 'firstname':
                return 'p.surname';
                break;
            case 'dob':
                return '(p.dob IS NULL), p.dob';
                break;
            case 'nationality':
                return 'p.nationality';
                break;
            case 'appearances':
                return 'cpas.appearances';
                break;
            case 'goals':
                return 'cpas.goals';
                break;
            case 'assists':
                return 'cpas.assist';
                break;
            case 'motms':
                return 'cpas.motms';
                break;
            case 'yellows':
                return 'cpas.yellows';
                break;
            case 'reds':
                return 'cpas.reds';
                break;
            case 'ratings':
                return 'cpas.average_rating';
                break;
        }

        return 'p.surname';
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