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

    /**
     * Create 'appearance' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createAppearanceTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'match_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'captain' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'rating' => array(
                'type'           => 'INT',
                'constraint'     => 2,
            ),
            'motm' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'injury' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
            ),
            'position' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'order' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'shirt' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => "'starter','substitute','unused'",
            ),
            'on' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'NULL'           => true,
            ),
            'off' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'NULL'           => true,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_club_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheClubStatisticsTable($tableName)
    {
        $fields = array(
            'type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 15,
            ),
            'season' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 7,
            ),
            'statistic_group' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ),
            'statistic_key' => array(
                'type'           => 'TEXT',
            ),
            'statistic_value' => array(
                'type'           => 'TEXT',
            ),
        );

        $this->ci->dbforge->add_field($fields);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_fantasy_football_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheFantasyFootballStatisticsTable($tableName)
    {
        $fields = array(
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 15,
            ),
            'season' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 7,
            ),
            'starter_appearances_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'substitute_appearances_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'clean_sheets_by_goalkeeper_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'clean_sheets_by_defender_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'clean_sheets_by_midfielder_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'assists_by_goalkeeper_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'assists_by_defender_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'assists_by_midfielder_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'assists_by_striker_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'goals_by_goalkeeper_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'goals_by_defender_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'goals_by_midfielder_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'goals_by_striker_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'man_of_the_match_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'yellow_card_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'red_card_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'appearances' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'total_points' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'points_per_game' => array(
                'type'           => 'decimal',
                'constraint'     =>  array(10, 3),
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('player_id', TRUE);
        $this->ci->dbforge->add_key('type', TRUE);
        $this->ci->dbforge->add_key('season', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_league_results_collated' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheLeagueResultsCollatedTable($tableName)
    {
        $fields = array(
            'league_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'opposition_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 10,
            ),
            'played' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'won' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'drawn' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'lost' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'gf' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'ga' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'gd' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'points' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'form' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'date_until' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 10,
                'NULL'           => true,
            ),
        );

        $this->ci->dbforge->add_field($fields);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_league_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheLeagueStatisticsTable($tableName)
    {
        $fields = array(
            'league_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'statistic_group' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ),
            'statistic_key' => array(
                'type'           => 'TEXT',
            ),
            'statistic_value' => array(
                'type'           => 'TEXT',
            ),
        );

        $this->ci->dbforge->add_field($fields);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_player_accumulated_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCachePlayerAccumulatedStatisticsTable($tableName)
    {
        $fields = array(
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 15,
            ),
            'season' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 7,
            ),
            'appearances' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'starter_appearances' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'substitute_appearances' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'goals' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'assists' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'motms' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'yellows' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'reds' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'average_rating' => array(
                'type'           => 'decimal',
                'constraint'     =>  array(7, 4),
            ),
        );

        $this->ci->dbforge->add_field($fields);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_player_goal_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCachePlayerGoalStatisticsTable($tableName)
    {
        $fields = array(
            'type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 15,
            ),
            'season' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 7,
            ),
            'statistic_group' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ),
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'statistic_key' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'statistic_value' => array(
                'type'           => 'decimal',
                'constraint'     =>  array(8, 3),
            ),
        );

        $this->ci->dbforge->add_field($fields);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_player_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCachePlayerStatisticsTable($tableName)
    {
        $fields = array(
            'type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 15,
            ),
            'season' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 7,
            ),
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'statistic_group' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ),
            'statistic_key' => array(
                'type'           => 'TEXT',
            ),
            'statistic_value' => array(
                'type'           => 'TEXT',
            ),
        );

        $this->ci->dbforge->add_field($fields);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_queue_club_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheQueueClubStatisticsTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'by_type' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
            ),
            'season' => array(
                'type'           => 'INT',
                'constraint'     => 4,
                'null'           => true,
            ),
            'cache_data' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => true,
            ),
            'in_progress' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
                'default'        => 0,
            ),
            'completed' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'process_duration' => array(
                'type'           => 'INT',
                'constraint'     => 5,
                'null'           => true,
            ),
            'peak_memory_usage' => array(
                'type'           => 'decimal',
                'constraint'     => array(5, 2),
                'null'           => true,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_queue_fantasy_football_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheQueueFantasyFootballStatisticsTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'by_type' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
            ),
            'season' => array(
                'type'           => 'INT',
                'constraint'     => 4,
                'null'           => true,
            ),
            'in_progress' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
                'default'        => 0,
            ),
            'completed' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'process_duration' => array(
                'type'           => 'INT',
                'constraint'     => 5,
                'null'           => true,
            ),
            'peak_memory_usage' => array(
                'type'           => 'decimal',
                'constraint'     => array(5, 2),
                'null'           => true,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_queue_league_results_collated' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheQueueLeagueResultsCollatedTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'league_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'in_progress' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
                'default'        => 0,
            ),
            'completed' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'process_duration' => array(
                'type'           => 'INT',
                'constraint'     => 5,
                'null'           => true,
            ),
            'peak_memory_usage' => array(
                'type'           => 'decimal',
                'constraint'     => array(5, 2),
                'null'           => true,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_queue_league_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheQueueLeagueStatisticsTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'league_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'cache_data' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => TRUE,
            ),
            'in_progress' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
                'default'        => 0,
            ),
            'completed' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'process_duration' => array(
                'type'           => 'INT',
                'constraint'     => 5,
                'null'           => true,
            ),
            'peak_memory_usage' => array(
                'type'           => 'decimal',
                'constraint'     => array(5, 2),
                'null'           => true,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_queue_player_accumulated_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheQueuePlayerAccumulatedStatisticsTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'by_type' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
            ),
            'season' => array(
                'type'           => 'INT',
                'constraint'     => 4,
                'null'           => true,
            ),
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'null'           => true,
            ),
            'in_progress' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
                'default'        => 0,
            ),
            'completed' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'process_duration' => array(
                'type'           => 'INT',
                'constraint'     => 5,
                'null'           => true,
            ),
            'peak_memory_usage' => array(
                'type'           => 'decimal',
                'constraint'     => array(5, 2),
                'null'           => true,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_queue_player_goal_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheQueuePlayerGoalStatisticsTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'by_type' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
            ),
            'season' => array(
                'type'           => 'INT',
                'constraint'     => 4,
                'null'           => true,
            ),
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'null'           => true,
            ),
            'cache_data' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => TRUE,
                'default'        => '',
            ),
            'in_progress' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'completed' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'process_duration' => array(
                'type'           => 'INT',
                'constraint'     => 5,
                'null'           => true,
            ),
            'peak_memory_usage' => array(
                'type'           => 'decimal',
                'constraint'     => array(5, 2),
                'null'           => true,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'cache_queue_player_statistics' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCacheQueuePlayerStatisticsTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'by_type' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
            ),
            'season' => array(
                'type'           => 'INT',
                'constraint'     => 4,
                'null'           => true,
            ),
            'cache_data' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => TRUE,
            ),
            'in_progress' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => TRUE,
                'default'        => 0,
            ),
            'completed' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'process_duration' => array(
                'type'           => 'INT',
                'constraint'     => 5,
                'null'           => true,
            ),
            'peak_memory_usage' => array(
                'type'           => 'decimal',
                'constraint'     => array(5, 2),
                'null'           => true,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'card' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCardTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'match_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'type' => array(
                'type' => 'ENUM',
                'constraint' => "'y','r'",
            ),
            'minute' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'offence' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'competition' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCompetitionTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'name' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'short_name' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'abbreviation' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 10,
            ),
            'type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 10,
            ),
            'starts' => array(
                'type'           => 'INT',
                'constraint'     => 2,
            ),
            'subs' => array(
                'type'           => 'INT',
                'constraint'     => 2,
            ),
            'competitive' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'competition_stage' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCompetitionStageTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'name' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'abbreviation' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 5,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'configuration' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createConfigurationTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'name' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'description' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 500,
            ),
            'key' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'value' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 25,
            ),
            'options' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 10000,
            ),
            'group_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'level_access' => array(
                'type'           => 'INT',
                'constraint'     => 2,
            ),
            'sort_order' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'visible' => array(
                'type'           => 'INT',
                'constraint'     => 1,
                'default'        => 1,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'configuration_group' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createConfigurationGroupTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'title' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'level_access' => array(
                'type'           => 'INT',
                'constraint'     => 2,
            ),
            'sort_order' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'visible' => array(
                'type'           => 'INT',
                'constraint'     => 1,
                'default'        => 1,
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'content' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createContentTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'title' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'image_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'content' => array(
                'type'           => 'TEXT',
            ),
            'type' => array(
                'type' => 'ENUM',
                'constraint' => "'article','news','page'",
            ),
            'author_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'menu_order' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'publish_date' => array(
                'type'           => 'DATETIME',
            ),
            'date_added' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'date_updated' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'deleted' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }


}