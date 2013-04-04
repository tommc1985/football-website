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
    }

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