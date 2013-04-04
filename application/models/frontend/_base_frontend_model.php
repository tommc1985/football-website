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
     * Return string of fields to order data by
     * @param  string $orderBy Fields passwed
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