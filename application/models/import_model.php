<?php
/**
 * Import Model
 */
class Import_Model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    /**
     * Name of source database
     * @var string
     */
    public $sourceDatabase;

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();

        $this->load->database();
        $this->sourceDatabase = $this->load->database('old', true);
    }

    /**
     * Import Data from existing database
     * @return NULL
     */
    public function importData()
    {
        $this->importAppearanceData();
    }

    /**
     * Insert Appearance Data
     * @return NULL
     */
    public function importAppearanceData()
    {
        $objects = $this->fetchAll('_appearance');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('appearance', $data);
    }

    /**
     * Fetch all rows for specified table
     * @param  string $tableName     Database Table
     * @param  string $orderBy       Which fields to order results by
     * @param  string $order         Order the results Ascending or Descending
     * @return array                 Returned rows
     */
    public function fetchAll($tableName, $orderBy = 'id', $order = 'asc')
    {
        $this->sourceDatabase->select('*');
        $this->sourceDatabase->from($tableName);
        $this->sourceDatabase->order_by($orderBy, $order);

        return $this->sourceDatabase->get()->result();
    }


}