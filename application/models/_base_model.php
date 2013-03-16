<?php
/**
 * Base Model
 */
class Base_Model extends CI_Model {

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
     * Insert new instance into specified table
     * @param  array $data  Data for insertion
     * @return int          MySQL Insert ID
     */
    public function insertEntry($data)
    {
        $data['date_added'] = time();
        $data['date_updated'] = time();
        $data['deleted'] = 0;

        $this->db->insert($this->tableName, $data);

        return $this->db->insert_id();
    }

    /**
     * Update an existing instance in specified table
     * @param  int   $id    Unique ID for existing instance
     * @param  array $data  Data for insertion
     * @return NULL
     */
    public function updateEntry($id, $data)
    {
        $data['date_updated'] = time();

        $this->db->update($this->tableName, $data, array('id' => $id));
    }

    /**
     * Delete an existing instance in specified table
     * @param  int   $id    Unique ID for existing instance
     * @return int          MySQL Insert ID
     */
    public function deleteEntry($id)
    {
        $data['date_updated'] = time();
        $data['deleted'] = 1;

        $this->db->update($this->tableName, $data, array('id' => $id));
    }

    /**
     * Count all rows in the specified table
     * @return int    Number of rows
     */
    public function countAll()
    {
        $this->db->from($this->tableName);
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
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
     * @param  int|false $limit      Number of rows to return
     * @param  int|false $offset     The offset
     * @param  string|false $orderBy Which fields to order results by
     * @param  string|false $order   Order the results Ascending or Descending
     * @return array                 Returned rows
     */
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

    /**
     * Fetch all rows for specified table based on a particular field
     * @param  string $fieldName     Name of field to filter by
     * @param  string $fieldValue    Value of field to filter by
     * @param  int|false $limit      Number of rows to return
     * @param  int|false $offset     The offset
     * @param  string|false $orderBy Which fields to order results by
     * @param  string|false $order   Order the results Ascending or Descending
     * @return array                 Returned rows
     */
    public function fetchAllByField($fieldName, $fieldValue, $limit = false, $offset = false, $orderBy = false, $order = false)
    {
        $orderBy = $this->getOrderBy($orderBy);
        $order   = self::getOrder($order);

        $this->db->select('*');
        $this->db->from($this->tableName);
        $this->db->where($fieldName, $fieldValue);
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

    /**
     * Compare two objects (before and after it's been saved) to verify if they're different
     * @param  object  $dataset_1  Old data (before save)
     * @param  object  $dataset_2  New data (after save)
     * @return boolean             Is the data the different
     */
    public function isDifferent($dataset_1, $dataset_2)
    {
        if (is_array($dataset_1) && is_array($dataset_2)) {
            foreach ($dataset_1 as $index => $object) {
                unset($dataset_1[$index]->date_updated);
            }

            foreach ($dataset_2 as $index => $object) {
                unset($dataset_2[$index]->date_updated);
            }
        } else {
            unset($dataset_1->date_updated);
            unset($dataset_2->date_updated);
        }

        return md5(serialize($dataset_1)) === md5(serialize($dataset_2));
    }

}