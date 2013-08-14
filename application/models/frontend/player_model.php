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
     * @param  int $id            Player ID
     * @param  mixed $extraData   Extra Data to include as part of the Player's Details
     * @return object             Player Details
     */
    public function fetchPlayerDetails($id, $extraData = false)
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

        // Player's Time between Debut & First Goal
        $player->timeBetweenDebutAndFirstGoal = $this->fetchPlayerTimeBetweenDebutAndFirstGoal($id);

        // Player's Games between Debut & First Goal
        $player->gamesBetweenDebutAndFirstGoal = $this->fetchPlayerGamesBetweenDebutAndFirstGoal($id);

        // Player's Awards
        $player->awards = $this->fetchPlayerAwards($id);

        // Player's Accumulated Season Statistics
        $player->accumulatedStatistics  = $this->fetchPlayerAccumulatedStatistics($id);

        if (isset($extraData['data'])) {
            switch ($extraData['data']) {
                case 'appearances':
                    // Player's Appearances by Season
                    $player->appearancesBySeason = $this->fetchPlayerAppearancesBySeason($id, $extraData['season']);
                    break;
                case 'goal-statistics':
                    // Player's Goal Statistics by Season
                    $player->goalStatisticsBySeason = $this->fetchPlayerGoalStatisticsBySeason($id, $extraData['season'], $extraData['type']);
                    break;
                case 'records':
                    // Player's Records by Season
                    $player->recordsBySeason = $this->fetchPlayerRecordsBySeason($id, $extraData['season']);
                    break;
            }
        }

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
            ->where('cpas.type !=', '')
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
     * Fetch a particular Player's Awards
     * @param  int $id         Player ID
     * @return array           Awards
     */
    public function fetchPlayerAwards($id)
    {
        $this->db->select('a.*, pta.season, pta.placing')
            ->from('player_to_award pta')
            ->join('player p', 'p.id = pta.player_id')
            ->join('award a', 'a.id = pta.award_id')
            ->where('pta.player_id', $id)
            ->where('p.deleted', 0)
            ->where('pta.deleted', 0)
            ->where('a.deleted', 0)
            ->order_by('pta.placing ASC, pta.season DESC, a.importance ASC');

        return $this->db->get()->result();
    }

    /**
     * Fetch a particular Player's Appearances by Season
     * @param  string $season     Season of Data
     * @param  int $id            Player ID
     * @return array              A Player's Appearances
     */
    public function fetchPlayerAppearancesBySeason($id, $season)
    {
        $this->db->select('vamc.*')
            ->from('view_appearances_matches_combined vamc')
            ->where('vamc.player_id', $id)
            ->order_by('date', 'desc');

        if ($season != 'all-time') {
            $startEndDates = Season_model::generateStartEndDates($season, NULL, NULL, false);
            $this->db->where($startEndDates);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch a particular Player's Goal Statistics by Season
     * @param  string $season     Season of Data
     * @param  int $id            Player ID
     * @return array              A Player's Appearances
     */
    public function fetchPlayerGoalStatisticsBySeason($id, $season, $type)
    {
        $ci =& get_instance();
        $ci->load->model('frontend/Player_Goal_Statistics_model');

        $data = array();

        $data['by_scorer'] = $ci->Player_Goal_Statistics_model->fetchByScorer($id, $season, $type);
        $data['by_assister'] = $ci->Player_Goal_Statistics_model->fetchByAssister($id, $season, $type);
        $data['by_goal_type'] = $ci->Player_Goal_Statistics_model->fetchByGoalType($id, $season, $type);
        $data['by_body_part'] = $ci->Player_Goal_Statistics_model->fetchByBodyPart($id, $season, $type);
        $data['by_distance'] = $ci->Player_Goal_Statistics_model->fetchByDistance($id, $season, $type);
        $data['by_minute_interval'] = $ci->Player_Goal_Statistics_model->fetchByMinuteInterval($id, $season, $type);
        $data['assist_by_goal_type'] = $ci->Player_Goal_Statistics_model->fetchAssistByGoalType($id, $season, $type);
        $data['assist_by_body_part'] = $ci->Player_Goal_Statistics_model->fetchAssistByBodyPart($id, $season, $type);
        $data['assist_by_distance'] = $ci->Player_Goal_Statistics_model->fetchAssistByDistance($id, $season, $type);
        $data['assist_by_minute_interval'] = $ci->Player_Goal_Statistics_model->fetchAssistByMinuteInterval($id, $season, $type);

        return $data;
    }

    /**
     * Fetch a particular Player's Reocrds by Season
     * @param  string $season     Season of Data
     * @param  int $id            Player ID
     * @return array              A Player's Appearances
     */
    public function fetchPlayerRecordsBySeason($id, $season)
    {

    }

    /**
     * Fetch list of genders
     * @return array List of genders
     */
    public static function fetchGenders()
    {
        $ci =& get_instance();
        $ci->load->config('player', true);

        return $ci->config->item('genders', 'player');
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
                return 'cpas.assists';
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