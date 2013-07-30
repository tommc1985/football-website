<?php
require_once('_base_frontend_model.php');

/**
 * Model for Match Page
 */
class Frontend_Match_model extends Base_Frontend_Model {

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
     * @param  mixed $season        Four digit season or 'career'
     * @param  string $type         Competition type
     * @param  string $orderBy      Field to order data by
     * @param  string $order        Sort Order of data ('asc' or 'desc')
     * @param  string $includeTBC   Include matches that are not yet set
     * @return array                List of Players
     */
    public function fetchMatchList($season, $type, $orderBy, $order, $includeTBC = false)
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

        if ($includeTBC) {
            $this->db->or_where('date', NULL);
        }

        return $this->db->get()->result();
    }

    /**
     * Fetch data on a particular match for Match Details Page
     * @param  int $id         Match ID
     * @return object          Match Details
     */
    public function fetchMatchDetails($id)
    {
        // Basic Match details
        $match = $this->fetch($id);

        if ($match === false) {
            return false;
        }

        // Match's Appearances
        $match->appearances = $this->fetchAppearances($id);

        // Match's Scorers
        $match->goals = $this->fetchGoals($id);

        // Match's Cards
        $match->cards = $this->fetchCards($id);

        // Match's Milestones
        $match->milestones = $this->fetchMilestones($match);

        // Match's Factfile
        $match->factfile = $this->fetchFactfile($match);

        return $match;
    }

    /**
     * Fetch a particular Match's Appearances
     * @param  int $id         Match ID
     * @return array           Appearances
     */
    public function fetchAppearances($id)
    {
        $this->db->select('a.*')
            ->from('appearance a')
            ->where('a.match_id', $id)
            ->where('a.deleted', 0)
            ->order_by('a.order', 'asc');

        return $this->db->get()->result();
    }

    /**
     * Fetch a particular Match's Goals
     * @param  int $id         Matches ID
     * @return array           Goals
     */
    public function fetchGoals($id)
    {
        $this->db->select('g.*')
            ->from('goal g')
            ->where('g.match_id', $id)
            ->where('g.deleted', 0)
            ->order_by('g.minute', 'asc');

        return $this->db->get()->result();
    }

    /**
     * Fetch a particular Match's Cards
     * @param  int $id         Match ID
     * @return array           Cards
     */
    public function fetchCards($id)
    {
        $this->db->select('c.*')
            ->from('card c')
            ->where('c.match_id', $id)
            ->where('c.deleted', 0)
            ->order_by('c.minute', 'asc');

        return $this->db->get()->result();
    }

    /**
     * Fetch a particular Match's milestones
     * @param  object $match      Match Object
     * @return array           Milestones
     */
    public function fetchMilestones($match)
    {
        if (is_null($match->status) && is_null($match->h) && !is_null($match->date)) {
            return $this->fetchPossibleMilestones($match->id);
        }

        return $this->fetchAchievedMilestones($match->id);
    }

    /**
     * Fetch a particular Match's Factfile
     * @param  int $match      Match Object
     * @return array           Factfile Info
     */
    public function fetchFactfile($match)
    {
        $this->ci->load->model('frontend/Factfile_model');

        return $this->ci->Factfile_model->fetchForMatch($match);
    }

    /**
     * Fetch a particular Match's Achieved milestones
     * @param  int $id         Match ID
     * @return array           Achieved Milestones
     */
    public function fetchAchievedMilestones($id)
    {
        $this->ci->load->model('frontend/Milestone_model');
        $this->ci->load->model('Competition_model');
        $this->ci->load->model('Season_model');
        $conditions['match_id'] = $id;

        // Basic Match details
        $match = $this->fetch($id);

        if ($match === false) {
            return array();
        }

        $competition = $this->ci->Competition_model->fetch($match->competition_id);

        $matchSeason = Season_model::fetchSeasonFromDateTime($match->date);
        $seasons = array(
            'career',
            $matchSeason
        );

        $types = array(
            'overall',
            $competition->type
        );

        $milestones = $this->ci->Milestone_model->fetchDebutPast($conditions);

        foreach ($seasons as $season) {
            foreach ($types as $type) {
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchAppearancePast($conditions, $season, $type));
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchGoalPast($conditions, $season, $type));
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchAssistPast($conditions, $season, $type));
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchYellowCardPast($conditions, $season, $type));
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchRedCardPast($conditions, $season, $type));
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchMotmPast($conditions, $season, $type));
            }
        }

        return $milestones;
    }

    /**
     * Fetch a particular Match's Possible milestones
     * @param  int $id         Match ID
     * @return array           Possible Milestones
     */
    public function fetchPossibleMilestones($id)
    {
        $this->ci->load->model('frontend/Milestone_model');
        $this->ci->load->model('Competition_model');
        $this->ci->load->model('Season_model');
        $conditions['match_id'] = $id;

        // Basic Match details
        $match = $this->fetch($id);

        if ($match === false) {
            return array();
        }

        $competition = $this->ci->Competition_model->fetch($match->competition_id);

        $matchSeason = Season_model::fetchSeasonFromDateTime($match->date);
        $seasons = array(
            'career',
            $matchSeason
        );

        $types = array(
            'overall',
            $competition->type
        );

        $milestones = $this->ci->Milestone_model->fetchDebutFuture($matchSeason);

        foreach ($seasons as $season) {
            foreach ($types as $type) {
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchAppearanceFuture($matchSeason, $season, $type));
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchGoalFuture($matchSeason, $season, $type));
                $milestones = array_merge($milestones, $this->ci->Milestone_model->fetchAssistFuture($matchSeason, $season, $type));
            }
        }

        return $milestones;
    }

    /**
     * Fetch list of players based on season, competition type, and ordered by a particular field in asc or desc order
     * @param  mixed $season   Four digit season or 'career'
     * @param  string $type    Competition type
     * @param  string $orderBy Field to order data by
     * @param  string $order   Sort Order of data ('asc' or 'desc')
     * @return array           List of Players
     */
    public function fetchMatchesByOpposition($oppositionId)
    {
        $this->db->select('*')
            ->from('view_matches')
            ->where('opposition_id', $oppositionId)
            ->where('competitive', 1)
            ->where('deleted', 0)
            ->order_by($this->getOrderBy(''), $this->getOrder('desc'));

        return $this->db->get()->result();
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