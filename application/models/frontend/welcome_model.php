<?php
require_once('_base_frontend_model.php');

/**
 * Model for Welcome Page
 */
class Welcome_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $ci =& get_instance();
        $ci->load->model('frontend/Player_model');
        $ci->load->model('Season_model');
    }

    /**
     * Fetch Top Scorers, in order
     * @param  int $season  Season of data to pull
     * @param  int $limit   The number of players to return
     * @return array        List of Top Scorers
     */
    public function fetchTopScorers($season, $limit)
    {
        $this->db->select('p.*, cpas.*')
            ->from('cache_player_accumulated_statistics cpas')
            ->join('player p', 'p.id = cpas.player_id')
            ->where('cpas.type', 'overall')
            ->where('cpas.season', $season)
            ->where('cpas.goals > ', 0)
            ->where('p.deleted', 0)
            ->order_by('cpas.goals', 'desc');

        $players = $this->db->get()->result();

        return $this->fetchSubset($players, $limit, 'goals');
    }

    /**
     * Fetch Top Assisters, in order
     * @param  int $season  Season of data to pull
     * @param  int $limit   The number of players to return
     * @return array        List of Top Assisters
     */
    public function fetchTopAssisters($season, $limit)
    {
        $this->db->select('p.*, cpas.*')
            ->from('cache_player_accumulated_statistics cpas')
            ->join('player p', 'p.id = cpas.player_id')
            ->where('cpas.type', 'overall')
            ->where('cpas.season', $season)
            ->where('cpas.assists > ', 0)
            ->where('p.deleted', 0)
            ->order_by('cpas.assists', 'desc');

        $players = $this->db->get()->result();

        return $this->fetchSubset($players, $limit, 'assists');
    }

    /**
     * Fetch Most MotMs, in order
     * @param  int $season  Season of data to pull
     * @param  int $limit   The number of players to return
     * @return array        List of Most MotMs
     */
    public function fetchMostMotMs($season, $limit)
    {
        $this->db->select('p.*, cpas.*')
            ->from('cache_player_accumulated_statistics cpas')
            ->join('player p', 'p.id = cpas.player_id')
            ->where('cpas.type', 'overall')
            ->where('cpas.season', $season)
            ->where('cpas.motms > ', 0)
            ->where('p.deleted', 0)
            ->order_by('cpas.motms', 'desc');

        $players = $this->db->get()->result();

        return $this->fetchSubset($players, $limit, 'motms');
    }

    /**
     * Fetch Worst Discipline, in order
     * @param  int $season  Season of data to pull
     * @param  int $limit   The number of players to return
     * @return array        List of Worst Discipline
     */
    public function fetchWorstDiscipline($season, $limit)
    {
        $this->db->select('p.*, cpas.*, CONCAT(cpas.yellows,"_",cpas.reds) as concatenated_cards')
            ->from('cache_player_accumulated_statistics cpas')
            ->join('player p', 'p.id = cpas.player_id')
            ->where('cpas.type', 'overall')
            ->where('cpas.season', $season)
            ->where('(cpas.reds > 0 || cpas.yellows > 0)')
            ->where('p.deleted', 0)
            ->order_by('cpas.reds DESC, cpas.yellows DESC');

        $players = $this->db->get()->result();

        return $this->fetchSubset($players, $limit, 'concatenated_cards');
    }

    /**
     * Fetch Fantasy Footballers, in order
     * @param  int $season  Season of data to pull
     * @param  int $limit   The number of players to return
     * @return array        List of Fantasy Footballers
     */
    public function fetchFantasyFootballers($season, $limit)
    {
        $this->db->select('p.*, cffs.*')
            ->from('cache_fantasy_football_statistics cffs')
            ->join('player p', 'p.id = cffs.player_id')
            ->where('cffs.type', 'overall')
            ->where('cffs.season', $season)
            ->where('cffs.position', 'all')
            ->where('cffs.total_points >', 0)
            ->where('p.deleted', 0)
            ->order_by('cffs.total_points DESC');

        $players = $this->db->get()->result();

        return $this->fetchSubset($players, $limit, 'total_points');
    }

    /**
     * Fetch All Matches that have occurred on this day, most recent first
     * @return array        Matches that occurred on this day
     */
    public function fetchOnThisDay()
    {
        $now = time();

        $this->db->select('vcm.*')
            ->from('view_competitive_matches vcm')
            ->where('DAYOFMONTH(vcm.date)', date("j", $now))
            ->where('MONTH(vcm.date)', date("n", $now))
            ->where('YEAR(vcm.date) !=', date("Y", $now))
            ->where('vcm.status', NULL)
            ->order_by('vcm.date', 'desc');

        $matches = $this->db->get()->result();

        if ($matches) {
            $index = array_rand($matches);

            $ci =& get_instance();
            $ci->load->model('frontend/Frontend_Match_model');

            return $ci->Frontend_Match_model->fetchMatchDetails($matches[$index]->id);
        }

        return false;
    }

    /**
     * Fetch recent results
     * @param  int $limit   The number of matches to return
     * @return array        Recent Results
     */
    public function fetchRecentResults($limit)
    {
        $this->db->select('vcm.*')
            ->from('view_competitive_matches vcm')
            ->where('vcm.date IS NOT NULL', NULL, false)
            ->where('vcm.h IS NOT NULL', NULL, false)
            ->where('vcm.status', NULL)
            ->order_by('vcm.date', 'desc')
            ->limit($limit);

        return $this->db->get()->result();
    }

    /**
     * Fetch upcoming fixtures
     * @param  int $limit   The number of matches to return
     * @return array        Recent Results
     */
    public function fetchUpcomingFixtures($limit)
    {
        $this->db->select('vcm.*')
            ->from('view_competitive_matches vcm')
            ->where('vcm.h', NULL, false)
            ->where('vcm.status', NULL)
            ->order_by('(date IS NULL), date DESC')
            ->limit($limit);

        return $this->db->get()->result();
    }

    /**
     * Fetch upcoming events
     * @param  int $limit   The number of matches to return
     * @return array        Recent Results
     */
    public function fetchUpcomingEvents($limit)
    {
        $now = time();

        $ci =& get_instance();
        $ci->load->model('frontend/Calendar_model');

        $where = array();
        $where['start_datetime >='] = date("Y-m-d 00:00:00", $now);

        return $ci->Calendar_model->fetchAll($where, $limit, false, 'start_datetime', 'asc');
    }

    /**
     * Fetch latest news article
     * @param  int $limit   The number of matches to return
     * @return object       Latest News Article
     */
    public function fetchLatestNewsArticle()
    {
        $ci =& get_instance();
        $ci->load->model('frontend/Content_model');

        $articles = $ci->Content_model->fetchLatest('news', 1);

        if ($articles) {
            return $articles[0];
        }

        return false;
    }

    /**
     * Return subset of data
     * @param  array $players   Players
     * @param  int $limit       The number of items to return
     * @return array            Subset of players
     */
    public function fetchSubset($players, $limit, $comparitor)
    {
        $subset = array();

        $i = 2;
        $nthValue = false;
        foreach ($players as $player) {
            $addPlayer = true;
            if ($i > $limit) {
                $addPlayer = false;
                if ($nthValue === false) {
                    $nthValue = $player->$comparitor;
                }

                if ($nthValue == $player->$comparitor) {
                    $addPlayer = true;
                } else {
                    break;
                }
            }

            if ($addPlayer) {
                $subset[] = $player;
            }

            $i++;
        }

        $extraPlayerCount = 0;
        $playerCount = count($subset);
        if ($playerCount > $limit) {
            $extraPlayerCount = $playerCount - $limit;
        }

        return array(
            'subset'           => array_slice($subset, 0, $limit),
            'extraPlayerCount' => $extraPlayerCount,
        );
    }

}