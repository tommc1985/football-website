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
        $match->milestones = $this->fetchMilestones($id);

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
     * @param  int $id         Match ID
     * @return array           Milestones
     */
    public function fetchMilestones($id)
    {
        return array();
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