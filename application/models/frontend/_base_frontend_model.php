<?php
/**
 * Base Frontend Model
 */
class Base_Frontend_Model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    /**
     * Name of database table
     * @var string
     */
    public $tableName;

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
    }

    /**
     * Fetch single row from table based on passed ID
     * @param  int $id       Unique ID for existing instance
     * @return object|false  The object (or false if not found)
     */
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

    /**
     * Fetch all rows for specified table
     * @param  array|false $where    Where Conditions
     * @param  int|false $limit      Number of rows to return
     * @param  int|false $offset     The offset
     * @param  string|false $orderBy Which fields to order results by
     * @param  string|false $order   Order the results Ascending or Descending
     * @return array                 Returned rows
     */
    public function fetchAll($where = false, $limit = false, $offset = false, $orderBy = false, $order = false)
    {
        $orderBy = $this->getOrderBy($orderBy);
        $order   = $this->getOrder($order);

        $this->db->select('*');
        $this->db->from($this->tableName);
        $this->db->where('deleted', 0);

        if (is_array($where)) {
            $this->db->where($where);
        }

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

    /**
     * Return string of fields to order data by
     * @param  string $orderBy Fields passed
     * @return string          Processed string of fields
     */
    public function getOrderBy($orderBy)
    {
        return 'id';
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