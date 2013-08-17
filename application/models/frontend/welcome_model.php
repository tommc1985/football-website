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

        return $this->db->get()->result();
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

        return $this->db->get()->result();
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

        return $this->db->get()->result();
    }

    /**
     * Fetch Worst Discipline, in order
     * @param  int $season  Season of data to pull
     * @param  int $limit   The number of players to return
     * @return array        List of Worst Discipline
     */
    public function fetchWorstDiscipline($season, $limit)
    {
        $this->db->select('p.*, cpas.*')
            ->from('cache_player_accumulated_statistics cpas')
            ->join('player p', 'p.id = cpas.player_id')
            ->where('cpas.type', 'overall')
            ->where('cpas.season', $season)
            ->where('(cpas.reds > 0 || cpas.yellows > 0)')
            ->where('p.deleted', 0)
            ->order_by('cpas.reds DESC, cpas.yellows DESC');

        return $this->db->get()->result();
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

        return $this->db->get()->result();
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

        return $this->db->get()->result();
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

        return $ci->Calendar_model->fetchAll($where, $limit, false, 'start_datetime', 'desc');
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

}