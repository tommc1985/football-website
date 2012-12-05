<?php
class Cache_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public $cacheModels;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();

        $this->cacheModels = array(
            'Cache_Club_Statistics_model',
            'Cache_Fantasy_Football_model',
            'Cache_League_model',
            'Cache_Player_Accumulated_Statistics_model',
            'Cache_Player_Goals_Statistics_model',
            'Cache_Player_Statistics_model',
        );

        $this->loadModels();
    }

    /**
     * Load Models
     * @return NULL
     */
    public function loadModels()
    {
        foreach ($this->cacheModels as $modelName) {
            $this->ci->load->model($modelName);
        }
    }

    /**
     * Insert rows from Cache Models into process queue table to be processed
     * @param  int|NULL $season     Season "career"
     * @return boolean
     */
    public function insertEntries($season = NULL)
    {
        foreach ($this->cacheModels as $modelName) {
            $this->$modelName->insertEntries($season);
        }
    }

    /**
     * Process any rows that are in the cache process queues
     * @return boolean
     */
    public function processQueuedRows()
    {
        foreach ($this->cacheModels as $modelName) {
            $processedRows = $this->ci->$modelName->processQueuedRows();

            if ($processedRows > 0) {
                break;
            }
        }
    }
}