<?php
class Base_Model extends CI_Model {

    public $ci;
    public $tableName;

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
    }

    public function insertEntry($data)
    {
        $data['date_added'] = time();
        $data['date_updated'] = time();
        $data['deleted'] = 0;

        $this->db->insert($this->tableName, $data);

        return $this->db->insert_id();
    }

    public function updateEntry($id, $data)
    {
        $data['date_updated'] = time();

        $this->db->update($this->tableName, $data, array('id' => $id));
    }

    public function deleteEntry($id)
    {
        $data['date_updated'] = time();
        $data['deleted'] = 1;

        $this->db->update($this->tableName, $data, array('id' => $id));
    }

    public function countAll()
    {
        $this->db->from($this->tableName);
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    public function fetch($id)
    {
        $this->db->select('*');
        $this->db->from($this->tableName);
        $this->db->where('id', $id);
        $this->db->where('deleted', 0);

        $result = $this->db->get()->result();

        if (count($result) == 1) {
            return $result[0];
        }

        return false;
    }

    public function fetchAll($limit = false, $offset = false, $orderBy = false, $order = false)
    {
        $orderBy = $this->getOrderBy($orderBy);
        $order   = self::getOrder($order);

        $this->db->select('*');
        $this->db->from($this->tableName);
        $this->db->where('deleted', 0);

        if ($limit !== false) {
            if ($offset !== false) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }

        $this->db->order_by($orderBy, $order);

        return $this->db->get()->result();
    }

    public function getOrderBy($orderBy)
    {
        return 'id';
    }

    public function getOrder($order)
    {
        if ($order == 'desc') {
            return 'desc';
        }

        return 'asc';
    }

}