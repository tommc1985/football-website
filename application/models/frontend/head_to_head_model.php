<?php
require_once('_base_frontend_model.php');

/**
 * Model for Head to Head page
 */
class Head_To_Head_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = '';
    }

    /**
     * Calculate Head to Head Accumulation Data
     * @param  array $matches  List of matches to use to calculate the data
     * @return array           Calculated data
     */
    public function calculateHeadToHeadAccumulatedData($matches)
    {
        $data = array('h' => (object)
            array(
            'p' => 0,
            'w' => 0,
            'd' => 0,
            'l' => 0,
            'f' => 0,
            'a' => 0,
            'gd' => 0),
        'a' => (object)
            array(
            'p' => 0,
            'w' => 0,
            'd' => 0,
            'l' => 0,
            'f' => 0,
            'a' => 0,
            'gd' => 0),
        'n' => (object)
            array(
            'p' => 0,
            'w' => 0,
            'd' => 0,
            'l' => 0,
            'f' => 0,
            'a' => 0,
            'gd' => 0),
        'overall' => (object)
            array(
            'p' => 0,
            'w' => 0,
            'd' => 0,
            'l' => 0,
            'f' => 0,
            'a' => 0,
            'gd' => 0));

        foreach ($matches as $match) {
            $matchPlayed = false;
            if (!is_null($match->date)) :
                if (!is_null($match->h_pen)) :
                    if ($match->h_pen > $match->a_pen) :
                        $data[$match->venue]->w++;
                        $data['overall']->w++;
                    elseif ($match->h_pen < $match->a_pen) :
                        $data[$match->venue]->l++;
                        $data['overall']->l++;
                    endif;
                    $matchPlayed = true;
                else :
                    if (!is_null($match->h)) :
                        if ($match->h > $match->a) :
                            $data[$match->venue]->w++;
                            $data['overall']->w++;
                        elseif ($match->h < $match->a) :
                            $data[$match->venue]->l++;
                            $data['overall']->l++;
                        else :
                            $data[$match->venue]->d++;
                            $data['overall']->d++;

                        endif;
                        $matchPlayed = true;
                    endif;
                endif;
            endif;

            if ($matchPlayed) :
                $data[$match->venue]->p++;
                $data['overall']->p++;

                $data[$match->venue]->f += (int) $match->h;
                $data['overall']->f += (int) $match->h;

                $data[$match->venue]->a += (int) $match->a;
                $data['overall']->a += (int) $match->a;

                $data[$match->venue]->gd = (int) $data[$match->venue]->f - (int) $data[$match->venue]->a;
                $data['overall']->gd = (int) $data['overall']->f - (int) $data['overall']->a;
            endif;
        }

        return $data;
    }

    /**
     * Fetch List of Top Scorers against a particular opposition
     * @param  int $oppositionId    Opposition ID
     * @return array                List of Scorers
     */
    public function fetchTopScorers($oppositionId)
    {
        $this->db->select('player_id, SUM(goals) as goals')
            ->from('view_match_goals')
            ->where('competitive', 1)
            ->where('opposition_id', $oppositionId)
            ->group_by('player_id')
            ->order_by('goals', 'desc')
            ->having('goals > 0');

        return $this->db->get()->result();
    }

    /**
     * Fetch List of Top Assisters against a particular opposition
     * @param  int $oppositionId    Opposition ID
     * @return array                List of Assisters
     */
    public function fetchTopAssisters($oppositionId)
    {
        $this->db->select('player_id, SUM(assists) as assists')
            ->from('view_match_assists')
            ->where('competitive', 1)
            ->where('opposition_id', $oppositionId)
            ->group_by('player_id')
            ->order_by('assists', 'desc')
            ->having('assists > 0');

        return $this->db->get()->result();
    }

    /**
     * Fetch List of Players with the Worst Discipline against a particular opposition
     * @param  int $oppositionId    Opposition ID
     * @return array                List of Players with the Worst Discipline
     */
    public function fetchWorstDiscipline($oppositionId)
    {
        $this->db->select('player_id, SUM(yellows) as yellows, SUM(reds) as reds')
            ->from('view_match_discipline')
            ->where('competitive', 1)
            ->where('opposition_id', $oppositionId)
            ->group_by('player_id')
            ->order_by('yellows, reds', 'desc')
            ->having('reds > 0 OR yellows > 0');

        return $this->db->get()->result();
    }

    /**
     * Fetch List of Players with the Points Gained against a particular opposition
     * @param  int $oppositionId    Opposition ID
     * @return array                List of Players with the Points Gained
     */
    public function fetchPointsGained($oppositionId)
    {
        $this->db->select('player_id, SUM(points) - SUM(adjusted_points)  as points')
            ->from('view_match_affected_results')
            ->where('competitive', 1)
            ->where('opposition_id', $oppositionId)
            ->group_by('player_id')
            ->order_by('points', 'desc')
            ->having('points > 0');

        return $this->db->get()->result();
    }

}