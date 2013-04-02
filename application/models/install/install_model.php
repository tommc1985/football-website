<?php
/**
 * Install Model
 */
class Install_Model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    /**
     * Database Tables
     * @var array
     */
    public $databaseTables;

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();

        $this->ci->load->dbforge();

        $this->databaseTables = array(
            'appearance',
            'cache_club_statistics',
            'cache_fantasy_football_statistics',
            'cache_league_results_collated',
            'cache_league_statistics',
            'cache_player_accumulated_statistics',
            'cache_player_goal_statistics',
            'cache_player_statistics',
            'cache_queue_club_statistics',
            'cache_queue_fantasy_football_statistics',
            'cache_queue_league_results_collated',
            'cache_queue_league_statistics',
            'cache_queue_player_accumulated_statistics',
            'cache_queue_player_goal_statistics',
            'cache_queue_player_statistics',
            'card',
            'competition',
            'competition_stage',
            'configuration',
            'configuration_group',
            'content',
            'goal',
            'image',
            'image_tag',
            'league',
            'league_match',
            'league_registration',
            'matches',
            'official',
            'opposition',
            'player',
            'player_registration',
            'player_to_position',
            'position',
            'tag',
            'user',
        );
    }

    /**
     * Create Database Tables
     * @return boolean    Was the creation successful?
     */
    public function createDatabaseTables()
    {
        echo '<pre>';
        foreach ($this->databaseTables as $tableName) {
            $methodName = $this->_generateCreateTableMethodName($tableName);

            print_r($methodName);
            if (method_exists($this, $methodName)) {
                $this->$methodName($tableName);
                echo "\tExists\n";
            } else{
                echo "\tDoes not exist\n";
            }
        }
        echo '</pre>';
    }

    /**
     * Generate the name of the Create method based on passed argument
     * @return string    Method Name
     */
    protected function _generateCreateTableMethodName($tableName)
    {
        $methodName = str_replace("_", " ", $tableName); // Change underscores to spaces
        $methodName = ucwords($methodName); // Convert first letter of each word to upper case
        $methodName = str_replace(" ", "", $methodName); // Remove spaces
        $methodName = "create{$methodName}Table";

        return $methodName;
    }


}