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
     * Database Views
     * @var array
     */
    public $databaseViews;

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
            'ci_sessions',
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
            'login_attempts',
            'matches',
            'official',
            'opposition',
            'player',
            'player_registration',
            'player_to_position',
            'position',
            'tag',
            'user_autologin',
            'user_profiles',
            'users',
        );

        $this->databaseViews = array(
            'view_appearances_ages',
            'view_appearances_matches',
            'view_competitive_matches',
            'view_featured_stories',
            'view_league_tables',
            'view_match_assists',
            'view_match_discipline',
            'view_match_goals',
            'view_matches',
            'view_yellow_count',
            'view_appearances_matches_combined',
            'view_match_affected_results',
        );
    }

    /**
     * Create Database Tables
     * @return boolean    Was the creation successful?
     */
    public function createDatabaseTables()
    {
        foreach ($this->databaseTables as $tableName) {
            $methodName = $this->_generateCreateTableMethodName($tableName);

            if (method_exists($this, $methodName)) {
                $this->$methodName($tableName);
            }
        }
    }

    /**
     * Create Database Views
     * @return boolean    Was the creation successful?
     */
    public function createDatabaseViews()
    {
        foreach ($this->databaseViews as $viewName) {
            $methodName = $this->_generateCreateViewMethodName($viewName);

            if (method_exists($this, $methodName)) {
                $this->$methodName();
            }
        }
    }

    /**
     * Insert Data
     * @return boolean    Was the insert successful?
     */
    public function insertData()
    {
        foreach ($this->databaseTables as $tableName) {
            $methodName = $this->_generateInsertDataMethodName($tableName);

            if (method_exists($this, $methodName)) {
                $this->$methodName($tableName);
            }
        }
    }

    /**
     * Generate the name of the Create Table method based on passed argument
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
     * Generate the name of the Create View method based on passed argument
     * @return string    Method Name
     */
    protected function _generateCreateViewMethodName($viewName)
    {
        $methodName = str_replace("_", " ", $viewName); // Change underscores to spaces
        $methodName = ucwords($methodName); // Convert first letter of each word to upper case
        $methodName = str_replace(" ", "", $methodName); // Remove spaces
        $methodName = "create{$methodName}View";

        return $methodName;
    }

    /**
     * Generate the name of the Insert Data method based on passed argument
     * @return string    Method Name
     */
    protected function _generateInsertDataMethodName($tableName)
    {
        $methodName = str_replace("_", " ", $tableName); // Change underscores to spaces
        $methodName = ucwords($methodName); // Convert first letter of each word to upper case
        $methodName = str_replace(" ", "", $methodName); // Remove spaces
        $methodName = "insert{$methodName}Data";

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
     * Create 'ci_sessions' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createCiSessionsTable($tableName)
    {
        $fields = array(
            'session_id' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 40,
                'default'        => 0,
            ),
            'ip_address' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 16,
                'default'        => 0,
            ),
            'user_agent' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ),
            'last_activity' => array(
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => TRUE,
                'default'        => 0,
            ),
            'user_data' => array(
                'type'           => 'TEXT',
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('session_id', TRUE);

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

    /**
     * Create 'goal' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createGoalTable($tableName)
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
            'scorer_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'assist_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'minute' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'type' => array(
                'type'           => 'INT',
                'constraint'     => 2,
            ),
            'body_part' => array(
                'type'           => 'INT',
                'constraint'     => 1,
            ),
            'distance' => array(
                'type'           => 'INT',
                'constraint'     => 1,
            ),
            'rating' => array(
                'type'           => 'INT',
                'constraint'     => 2,
            ),
            'description' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 1000,
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
     * Create 'image' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createImageTable($tableName)
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
            'filename' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 20,
            ),
            'extension' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 4,
            ),
            'directory' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 15,
            ),
            'width' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'height' => array(
                'type'           => 'INT',
                'constraint'     => 5,
            ),
            'filesize' => array(
                'type'           => 'INT',
                'constraint'     => 20,
            ),
            'comment' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 1000,
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
     * Create 'image_tag' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createImageTagTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'image_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'tag_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
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
     * Create 'league' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createLeagueTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'competition_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'season' => array(
                'type'           => 'INT',
                'constraint'     => 11,
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
            'points_for_win' => array(
                'type'           => 'INT',
                'constraint'     => 3,
            ),
            'points_for_draw' => array(
                'type'           => 'INT',
                'constraint'     => 3,
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
     * Create 'league_match' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createLeagueMatchTable($tableName)
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
            'date' => array(
                'type'           => 'DATE',
                'null'           => true,
            ),
            'h_opposition_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'a_opposition_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'h_score' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'null'           => true,
            ),
            'a_score' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'null'           => true,
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => "'hw','aw','p','a'",
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
     * Create 'league_registration' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createLeagueRegistrationTable($tableName)
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
            'opposition_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
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
     * Create 'login_attempts' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createLoginAttemptsTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => TRUE,
            ),
            'ip_address' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 40,
            ),
            'login' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ),
            'time' => array(
                'type'           => 'timestamp',
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
     * Create 'matches' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createMatchesTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'opposition_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'competition_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'competition_stage_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'null'           => true,
            ),
            'venue' => array(
                'type' => 'ENUM',
                'constraint' => "'h','a','n'",
                'null'           => true,
            ),
            'location' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ),
            'official_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'default'        => 0,
            ),
            'h' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'null'           => true,
            ),
            'a' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'null'           => true,
            ),
            'report' => array(
                'type'           => 'TEXT',
                'null'           => true,
            ),
            'date' => array(
                'type'           => 'DATETIME',
                'null'           => true,
            ),
            'h_et' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'null'           => true,
            ),
            'a_et' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'null'           => true,
            ),
            'h_pen' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'null'           => true,
            ),
            'a_pen' => array(
                'type'           => 'INT',
                'constraint'     => 3,
                'null'           => true,
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => "'hw','aw','p','a'",
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
     * Create 'official' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createOfficialTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'first_name' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ),
            'surname' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 50,
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
     * Create 'opposition' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createOppositionTable($tableName)
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
     * Create 'player' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createPlayerTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'first_name' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ),
            'surname' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ),
            'dob' => array(
                'type'           => 'DATE',
                'null'           => true,
            ),
            'nationality' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'null'           => true,
            ),
            'profile' => array(
                'type'           => 'TEXT',
                'null'           => true,
            ),
            'current' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => true,
            ),
            'image_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'null'           => true,
            ),
            'gender' => array(
                'type' => 'ENUM',
                'constraint' => "'m','f'",
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
     * Create player_registration' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createPlayerRegistrationTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'season' => array(
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
     * Create player_to_position' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createPlayerToPositionTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'player_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
            ),
            'position_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
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
     * Create 'position' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createPositionTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'abbreviation' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 5,
            ),
            'long_name' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 30,
            ),
            'sort_order' => array(
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
     * Create 'tag' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createTagTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ),
            'tag' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
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
     * Create 'user_autologin' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createUserAutologinTable($tableName)
    {
        $fields = array(
            'key_id' => array(
                'type'           => 'CHAR',
                'constraint'     => 32,
            ),
            'user_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'default'        => 0,
            ),
            'user_agent' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ),
            'last_ip' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 40,
            ),
            'last_login' => array(
                'type'           => 'timestamp',
            ),
        );

        $this->ci->dbforge->add_field($fields);
        $this->ci->dbforge->add_key('key_id', TRUE);
        $this->ci->dbforge->add_key('user_id', TRUE);

        if ($this->ci->dbforge->create_table($tableName, TRUE)) {
            return true;
        }

        return false;
    }

    /**
     * Create 'user_profiles' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createUserProfilesTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => TRUE,
            ),
            'user_id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
            ),
            'country' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
            ),
            'website' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
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
     * Create 'users' table
     * @param  string $tableName Database table name
     * @return boolean           Result of table creation attempt
     */
    public function createUsersTable($tableName)
    {
        $fields = array(
            'id' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => TRUE,
            ),
            'username' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ),
            'password' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ),
            'email' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ),
            'activated' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 1,
            ),
            'banned' => array(
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
            ),
            'ban_reason' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ),
            'new_password_key' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ),
            'new_password_requested' => array(
                'type'           => 'DATETIME',
                'null'           => true,
            ),
            'new_email' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => true,
            ),
            'new_email_key' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ),
            'last_ip' => array(
                'type'           => 'VARCHAR',
                'constraint'     => 40,
            ),
            'last_login' => array(
                'type'           => 'DATETIME',
                'default'        => '0000-00-00 00:00:00',
            ),
            'created' => array(
                'type'           => 'DATETIME',
                'default'        => '0000-00-00 00:00:00',
            ),
            'modified' => array(
                'type'           => 'timestamp',
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
     * Create 'view_appearances_ages' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewAppearancesAgesView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_appearances_ages` AS select `a`.`id` AS `id`,`a`.`match_id` AS `match_id`,`a`.`player_id` AS `player_id`,`a`.`motm` AS `motm`,`a`.`position` AS `position`,`a`.`status` AS `status`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`c`.`type` AS `competition_type`,`m`.`venue` AS `venue`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`date` AS `date`,`c`.`competitive` AS `competitive`,`p`.`first_name` AS `first_name`,`p`.`surname` AS `surname`,`p`.`dob` AS `dob`,(to_days(`m`.`date`) - to_days(`p`.`dob`)) AS `age` from ((((`appearance` `a` left join `matches` `m` on((`a`.`match_id` = `m`.`id`))) left join `competition` `c` on((`m`.`competition_id` = `c`.`id`))) left join `opposition` `o` on((`m`.`opposition_id` = `o`.`id`))) left join `player` `p` on((`a`.`player_id` = `p`.`id`))) where ((`a`.`deleted` = 0) and (`m`.`deleted` = 0) and (`c`.`deleted` = 0) and (`p`.`deleted` = 0) and (`o`.`deleted` = 0)) ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_appearances_matches' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewAppearancesMatchesView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_appearances_matches` AS select `a`.`id` AS `id`,`a`.`match_id` AS `match_id`,`a`.`player_id` AS `player_id`,`a`.`motm` AS `motm`,`a`.`position` AS `position`,`a`.`status` AS `status`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`c`.`type` AS `competition_type`,`m`.`venue` AS `venue`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`date` AS `date`,`c`.`competitive` AS `competitive`,`p`.`first_name` AS `first_name`,`p`.`surname` AS `surname`,`p`.`dob` AS `dob` from ((((`appearance` `a` left join `matches` `m` on((`a`.`match_id` = `m`.`id`))) left join `competition` `c` on((`m`.`competition_id` = `c`.`id`))) left join `player` `p` on((`a`.`player_id` = `p`.`id`))) left join `opposition` `o` on((`m`.`opposition_id` = `o`.`id`))) where ((`a`.`deleted` = 0) and (`m`.`deleted` = 0) and (`c`.`deleted` = 0) and (`p`.`deleted` = 0) and (`o`.`deleted` = 0)) ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_appearances_matches_combined' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewAppearancesMatchesCombinedView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_appearances_matches_combined` AS select `a`.`id` AS `id`,`a`.`match_id` AS `match_id`,`a`.`player_id` AS `player_id`,`a`.`motm` AS `motm`,`a`.`position` AS `position`,`a`.`rating` AS `rating`,`a`.`status` AS `status`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`c`.`type` AS `competition_type`,`m`.`venue` AS `venue`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`date` AS `date`,`c`.`competitive` AS `competitive`,`p`.`first_name` AS `first_name`,`p`.`surname` AS `surname`,`p`.`dob` AS `dob`,(select count(`g`.`id`) AS `COUNT(g.id)` from `goal` `g` where ((`g`.`match_id` = `a`.`match_id`) and (`g`.`scorer_id` = `a`.`player_id`) and (`g`.`deleted` = 0))) AS `goals`,(select count(`g`.`id`) AS `COUNT(g.id)` from `goal` `g` where ((`g`.`match_id` = `a`.`match_id`) and (`g`.`assist_id` = `a`.`player_id`) and (`g`.`deleted` = 0))) AS `assists`,(select count(`vyc`.`yellows`) AS `count(vyc.yellows)` from `view_yellow_count` `vyc` where ((`vyc`.`match_id` = `a`.`match_id`) and (`vyc`.`player_id` = `a`.`player_id`) and (`vyc`.`yellows` = 1))) AS `yellows`,((select count(`c`.`id`) AS `COUNT(c.id)` from `card` `c` where ((`c`.`match_id` = `a`.`match_id`) and (`c`.`player_id` = `a`.`player_id`) and (`c`.`type` = 'r') and (`c`.`deleted` = 0))) + (select count(`vyc`.`yellows`) AS `count(vyc.yellows)` from `view_yellow_count` `vyc` where ((`vyc`.`match_id` = `a`.`match_id`) and (`vyc`.`player_id` = `a`.`player_id`) and (`vyc`.`yellows` = 2)))) AS `reds`,(to_days(`m`.`date`) - to_days(`p`.`dob`)) AS `age` from ((((`appearance` `a` left join `matches` `m` on((`a`.`match_id` = `m`.`id`))) left join `competition` `c` on((`m`.`competition_id` = `c`.`id`))) left join `player` `p` on((`a`.`player_id` = `p`.`id`))) left join `opposition` `o` on((`m`.`opposition_id` = `o`.`id`))) where ((`a`.`deleted` = 0) and (`m`.`deleted` = 0) and (`c`.`deleted` = 0) and (`p`.`deleted` = 0) and (`o`.`deleted` = 0)) ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_competitive_matches' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewCompetitiveMatchesView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_competitive_matches` AS select `m`.`id` AS `id`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`m`.`venue` AS `venue`,`m`.`location` AS `location`,`m`.`official_id` AS `official_id`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`report` AS `report`,`m`.`date` AS `date`,`m`.`h_et` AS `h_et`,`m`.`a_et` AS `a_et`,`m`.`h_pen` AS `h_pen`,`m`.`a_pen` AS `a_pen`,`m`.`status` AS `status`,`m`.`date_added` AS `date_added`,`m`.`date_updated` AS `date_updated`,`m`.`deleted` AS `deleted`,`o`.`name` AS `opposition_name`,`c`.`name` AS `competition_name`,`c`.`short_name` AS `competition_short_name`,`c`.`abbreviation` AS `competition_abbreviation`,`c`.`competitive` AS `competitive`,`c`.`type` AS `type`,`cs`.`name` AS `competition_stage_name`,`cs`.`abbreviation` AS `competition_stage_abbreviation` from (((`matches` `m` left join `opposition` `o` on((`o`.`id` = `m`.`opposition_id`))) left join `competition` `c` on((`c`.`id` = `m`.`competition_id`))) left join `competition_stage` `cs` on((`cs`.`id` = `m`.`competition_stage_id`))) where ((`c`.`competitive` = 1) and (`m`.`deleted` = 0) and (`o`.`deleted` = 0) and (`c`.`deleted` = 0) and ((`cs`.`deleted` = 0) or isnull(`m`.`competition_stage_id`))) order by `m`.`date` desc ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_featured_stories' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewFeaturedStoriesView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_featured_stories` AS (select `c`.`id` AS `id`,`c`.`title` AS `title`,`c`.`image_id` AS `image_id`,`c`.`content` AS `content`,`c`.`type` AS `type`,`c`.`publish_date` AS `date`,'' AS `opposition_id`,'' AS `competition_id`,'' AS `competition_stage_id`,'' AS `venue`,'' AS `location`,'' AS `official_id`,'' AS `h`,'' AS `a`,'' AS `h_et`,'' AS `a_et`,'' AS `h_pen`,'' AS `a_pen`,'' AS `status` from `content` `c` where ((`c`.`type` in ('article','news')) and (`c`.`deleted` = 0))) union (select `m`.`id` AS `id`,'' AS `title`,'0' AS `image_id`,`m`.`report` AS `content`,'match' AS `type`,`m`.`date` AS `date`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`m`.`venue` AS `venue`,`m`.`location` AS `location`,`m`.`official_id` AS `official_id`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`h_et` AS `h_et`,`m`.`a_et` AS `a_et`,`m`.`h_pen` AS `h_pen`,`m`.`a_pen` AS `a_pen`,`m`.`status` AS `status` from `matches` `m` where (((`m`.`h` is not null) or (`m`.`status` is not null)) and (`m`.`date` < now()) and (`m`.`deleted` = 0))) ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_league_tables' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewLeagueTablesView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_league_tables` AS select `lr`.`league_id` AS `league_id`,`lr`.`opposition_id` AS `opposition_id`,`clrc1`.`played` AS `overall_played`,`clrc1`.`won` AS `overall_won`,`clrc1`.`drawn` AS `overall_drawn`,`clrc1`.`lost` AS `overall_lost`,`clrc1`.`gf` AS `overall_gf`,`clrc1`.`ga` AS `overall_ga`,`clrc1`.`gd` AS `overall_gd`,`clrc1`.`points` AS `overall_points`,`clrc1`.`form` AS `overall_form`,`clrc2`.`played` AS `home_played`,`clrc2`.`won` AS `home_won`,`clrc2`.`drawn` AS `home_drawn`,`clrc2`.`lost` AS `home_lost`,`clrc2`.`gf` AS `home_gf`,`clrc2`.`ga` AS `home_ga`,`clrc2`.`gd` AS `home_gd`,`clrc2`.`points` AS `home_points`,`clrc2`.`form` AS `home_form`,`clrc3`.`played` AS `away_played`,`clrc3`.`won` AS `away_won`,`clrc3`.`drawn` AS `away_drawn`,`clrc3`.`lost` AS `away_lost`,`clrc3`.`gf` AS `away_gf`,`clrc3`.`ga` AS `away_ga`,`clrc3`.`gd` AS `away_gd`,`clrc3`.`points` AS `away_points`,`clrc3`.`form` AS `away_form` from (((`league_registration` `lr` left join `cache_league_results_collated` `clrc1` on((`lr`.`opposition_id` = `clrc1`.`opposition_id`))) left join `cache_league_results_collated` `clrc2` on((`lr`.`opposition_id` = `clrc2`.`opposition_id`))) left join `cache_league_results_collated` `clrc3` on((`lr`.`opposition_id` = `clrc3`.`opposition_id`))) where ((`lr`.`league_id` = `clrc1`.`league_id`) and (`lr`.`league_id` = `clrc2`.`league_id`) and (`lr`.`league_id` = `clrc3`.`league_id`) and (`clrc1`.`type` = 'overall') and (`clrc2`.`type` = 'home') and (`clrc3`.`type` = 'away') and (`lr`.`deleted` = 0)) ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_matches' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewMatchesView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_matches` AS select `m`.`id` AS `id`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`m`.`venue` AS `venue`,`m`.`location` AS `location`,`m`.`official_id` AS `official_id`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`report` AS `report`,`m`.`date` AS `date`,`m`.`h_et` AS `h_et`,`m`.`a_et` AS `a_et`,`m`.`h_pen` AS `h_pen`,`m`.`a_pen` AS `a_pen`,`m`.`status` AS `status`,`m`.`date_added` AS `date_added`,`m`.`date_updated` AS `date_updated`,`m`.`deleted` AS `deleted`,`o`.`name` AS `opposition_name`,`c`.`name` AS `competition_name`,`c`.`short_name` AS `competition_short_name`,`c`.`abbreviation` AS `competition_abbreviation`,`c`.`competitive` AS `competitive`,`c`.`type` AS `type`,`cs`.`name` AS `competition_stage_name`,`cs`.`abbreviation` AS `competition_stage_abbreviation` from (((`matches` `m` left join `opposition` `o` on((`o`.`id` = `m`.`opposition_id`))) left join `competition` `c` on((`c`.`id` = `m`.`competition_id`))) left join `competition_stage` `cs` on((`cs`.`id` = `m`.`competition_stage_id`))) where ((`m`.`deleted` = 0) and (`o`.`deleted` = 0) and (`c`.`deleted` = 0) and ((`cs`.`deleted` = 0) or isnull(`m`.`competition_stage_id`))) order by `m`.`date` desc ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_match_affected_results' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewMatchAffectedResultsView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_match_affected_results` AS select `vmg`.`id` AS `id`,`vmg`.`match_id` AS `match_id`,`vmg`.`player_id` AS `player_id`,`vmg`.`motm` AS `motm`,`vmg`.`position` AS `position`,`vmg`.`status` AS `status`,`vmg`.`opposition_id` AS `opposition_id`,`vmg`.`competition_id` AS `competition_id`,`vmg`.`competition_stage_id` AS `competition_stage_id`,`vmg`.`competition_type` AS `competition_type`,`vmg`.`venue` AS `venue`,`vmg`.`h` AS `h`,`vmg`.`a` AS `a`,`vmg`.`date` AS `date`,`vmg`.`competitive` AS `competitive`,`vmg`.`first_name` AS `first_name`,`vmg`.`surname` AS `surname`,`vmg`.`dob` AS `dob`,`vmg`.`goals` AS `goals`,`vmg`.`assists` AS `assists`,((`vmg`.`h` - `vmg`.`goals`) - `vmg`.`assists`) AS `adjusted_h`,if((`vmg`.`h` > `vmg`.`a`),3,if((`vmg`.`h` = `vmg`.`a`),1,0)) AS `points`,if((((`vmg`.`h` - `vmg`.`goals`) - `vmg`.`assists`) > `vmg`.`a`),3,if((((`vmg`.`h` - `vmg`.`goals`) - `vmg`.`assists`) = `vmg`.`a`),1,0)) AS `adjusted_points` from `view_appearances_matches_combined` `vmg` ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_match_assists' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewMatchAssistsView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_match_assists` AS select `a`.`id` AS `id`,`a`.`match_id` AS `match_id`,`a`.`player_id` AS `player_id`,`a`.`motm` AS `motm`,`a`.`position` AS `position`,`a`.`status` AS `status`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`c`.`type` AS `competition_type`,`m`.`venue` AS `venue`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`date` AS `date`,`c`.`competitive` AS `competitive`,`p`.`first_name` AS `first_name`,`p`.`surname` AS `surname`,`p`.`dob` AS `dob`,(select count(`g`.`id`) AS `COUNT(g.id)` from `goal` `g` where ((`g`.`match_id` = `a`.`match_id`) and (`g`.`assist_id` = `a`.`player_id`) and (`g`.`deleted` = 0))) AS `assists` from ((((`appearance` `a` left join `matches` `m` on((`a`.`match_id` = `m`.`id`))) left join `competition` `c` on((`m`.`competition_id` = `c`.`id`))) left join `player` `p` on((`a`.`player_id` = `p`.`id`))) left join `opposition` `o` on((`m`.`opposition_id` = `o`.`id`))) where ((`a`.`deleted` = 0) and (`m`.`deleted` = 0) and (`c`.`deleted` = 0) and (`p`.`deleted` = 0) and (`o`.`deleted` = 0)) ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_match_discipline' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewMatchDisciplineView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_match_discipline` AS select `a`.`id` AS `id`,`a`.`match_id` AS `match_id`,`a`.`player_id` AS `player_id`,`a`.`motm` AS `motm`,`a`.`position` AS `position`,`a`.`status` AS `status`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`c`.`type` AS `competition_type`,`m`.`venue` AS `venue`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`date` AS `date`,`c`.`competitive` AS `competitive`,`p`.`first_name` AS `first_name`,`p`.`surname` AS `surname`,`p`.`dob` AS `dob`,(select count(`vyc`.`yellows`) AS `COUNT(yellows )` from `view_yellow_count` `vyc` where ((`vyc`.`match_id` = `a`.`match_id`) and (`vyc`.`player_id` = `a`.`player_id`) and (`vyc`.`yellows` = 1))) AS `yellows`,((select count(`c`.`id`) AS `COUNT(c.id)` from `card` `c` where ((`c`.`match_id` = `a`.`match_id`) and (`c`.`player_id` = `a`.`player_id`) and (`c`.`type` = 'r') and (`c`.`deleted` = 0))) + (select count(`vyc`.`yellows`) AS `COUNT(yellows )` from `view_yellow_count` `vyc` where ((`vyc`.`match_id` = `a`.`match_id`) and (`vyc`.`player_id` = `a`.`player_id`) and (`vyc`.`yellows` = 2)))) AS `reds` from ((((`appearance` `a` left join `matches` `m` on((`a`.`match_id` = `m`.`id`))) left join `competition` `c` on((`m`.`competition_id` = `c`.`id`))) left join `player` `p` on((`a`.`player_id` = `p`.`id`))) left join `opposition` `o` on((`m`.`opposition_id` = `o`.`id`))) where ((`a`.`deleted` = 0) and (`m`.`deleted` = 0) and (`c`.`deleted` = 0) and (`p`.`deleted` = 0) and (`o`.`deleted` = 0)) ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_match_goals' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewMatchGoalsView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_match_goals` AS select `a`.`id` AS `id`,`a`.`match_id` AS `match_id`,`a`.`player_id` AS `player_id`,`a`.`motm` AS `motm`,`a`.`position` AS `position`,`a`.`status` AS `status`,`m`.`opposition_id` AS `opposition_id`,`m`.`competition_id` AS `competition_id`,`m`.`competition_stage_id` AS `competition_stage_id`,`c`.`type` AS `competition_type`,`m`.`venue` AS `venue`,`m`.`h` AS `h`,`m`.`a` AS `a`,`m`.`date` AS `date`,`c`.`competitive` AS `competitive`,`p`.`first_name` AS `first_name`,`p`.`surname` AS `surname`,`p`.`dob` AS `dob`,(select count(`g`.`id`) AS `COUNT(g.id)` from `goal` `g` where ((`g`.`match_id` = `a`.`match_id`) and (`g`.`scorer_id` = `a`.`player_id`) and (`g`.`deleted` = 0))) AS `goals` from ((((`appearance` `a` left join `matches` `m` on((`a`.`match_id` = `m`.`id`))) left join `competition` `c` on((`m`.`competition_id` = `c`.`id`))) left join `player` `p` on((`a`.`player_id` = `p`.`id`))) left join `opposition` `o` on((`m`.`opposition_id` = `o`.`id`))) where ((`a`.`deleted` = 0) and (`m`.`deleted` = 0) and (`c`.`deleted` = 0) and (`p`.`deleted` = 0) and (`o`.`deleted` = 0)) ;";

        $this->db->query($sql);
    }

    /**
     * Create 'view_yellow_count' view
     * @return boolean           Result of view creation attempt
     */
    public function createViewYellowCountView()
    {
        $sql = "CREATE OR REPLACE VIEW `view_yellow_count` AS select `c`.`player_id` AS `player_id`,`c`.`match_id` AS `match_id`,count(distinct `c`.`id`) AS `yellows` from `card` `c` where ((`c`.`type` = 'y') and (`c`.`deleted` = 0)) group by `c`.`player_id`,`c`.`match_id` ;";

        $this->db->query($sql);
    }

    /**
     * Insert 'competition' table
     * @param  string $tableName Database table name
     * @return boolean           Result of data insert attempt
     */
    public function insertCompetitionData($tableName)
    {
        $this->db->truncate($tableName);

        $data = array(
            array(
                'name' => 'Friendly' ,
                'short_name' => 'Friendly',
                'abbreviation' => 'FR',
                'type' => 'friendly',
                'starts' => 11,
                'subs' => 9,
                'competitive' => 0,
                'date_added' => time(),
                'date_updated' => time(),
            ),
        );

        if ($this->db->insert_batch($tableName, $data)) {
            return true;
        }

        return false;
    }

    /**
     * Insert 'competition_stage' table
     * @param  string $tableName Database table name
     * @return boolean           Result of data insert attempt
     */
    public function insertCompetitionStageData($tableName)
    {
        $this->db->truncate($tableName);

        $data = array(
            array(
                'name' => 'Final' ,
                'abbreviation' => 'F',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => 'Semi Final' ,
                'abbreviation' => 'SF',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => 'Semi Final 1st Leg' ,
                'abbreviation' => 'SF1',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => 'Semi Final 2nd Leg' ,
                'abbreviation' => 'SF2',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => 'Quarter Final' ,
                'abbreviation' => 'QF',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => 'Quarter Final 1st Leg' ,
                'abbreviation' => 'QF1',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => 'Quarter Final Final 2nd Leg' ,
                'abbreviation' => 'QF2',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => 'Last 16' ,
                'abbreviation' => 'L16',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '6th Round' ,
                'abbreviation' => '6R',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '6th Round Replay' ,
                'abbreviation' => '6RR',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '6th Round 1st Leg' ,
                'abbreviation' => '6R1L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '6th Round 2nd Leg' ,
                'abbreviation' => '6R2L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '5th Round' ,
                'abbreviation' => '5R',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '5th Round Replay' ,
                'abbreviation' => '5RR',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '5th Round 1st Leg' ,
                'abbreviation' => '5R1L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '5th Round 2nd Leg' ,
                'abbreviation' => '5R2L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '4th Round' ,
                'abbreviation' => '4R',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '4th Round Replay' ,
                'abbreviation' => '4RR',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '4th Round 1st Leg' ,
                'abbreviation' => '4R1L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '4th Round 2nd Leg' ,
                'abbreviation' => '4R2L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '3rd Round' ,
                'abbreviation' => '3R',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '3rd Round Replay' ,
                'abbreviation' => '3RR',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '3rd Round 1st Leg' ,
                'abbreviation' => '3R1L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '3rd Round 2nd Leg' ,
                'abbreviation' => '3R2L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '2nd Round' ,
                'abbreviation' => '2R',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '2nd Round Replay' ,
                'abbreviation' => '2RR',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '2nd Round 1st Leg' ,
                'abbreviation' => '2R1L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '2nd Round 2nd Leg' ,
                'abbreviation' => '2R2L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '1st Round' ,
                'abbreviation' => '1R',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '1st Round Replay' ,
                'abbreviation' => '1RR',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '1st Round 1st Leg' ,
                'abbreviation' => '1R1L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'name' => '1st Round 2nd Leg' ,
                'abbreviation' => '1R2L',
                'date_added' => time(),
                'date_updated' => time(),
            ),
        );

        if ($this->db->insert_batch($tableName, $data)) {
            return true;
        }

        return false;
    }

    /**
     * Insert 'position' table
     * @param  string $tableName Database table name
     * @return boolean           Result of data insert attempt
     */
    public function insertPositionData($tableName)
    {
        $this->db->truncate($tableName);

        $data = array(
            array(
                'id' => 1,
                'abbreviation' => 'GK' ,
                'long_name' => 'Goalkeeper',
                'sort_order' => 1,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 2,
                'abbreviation' => 'RB' ,
                'long_name' => 'Right Back',
                'sort_order' => 2,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 3,
                'abbreviation' => 'LB' ,
                'long_name' => 'Left Back',
                'sort_order' => 3,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 4,
                'abbreviation' => 'CB' ,
                'long_name' => 'Centre Back',
                'sort_order' => 4,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 5,
                'abbreviation' => 'SW' ,
                'long_name' => 'Sweeper',
                'sort_order' => 5,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 6,
                'abbreviation' => 'RWB' ,
                'long_name' => 'Right Wing Back',
                'sort_order' => 6,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 7,
                'abbreviation' => 'LWB' ,
                'long_name' => 'Left Wing Back',
                'sort_order' => 7,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 8,
                'abbreviation' => 'DM' ,
                'long_name' => 'Defensive Midfielder',
                'sort_order' => 8,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 9,
                'abbreviation' => 'RM' ,
                'long_name' => 'Right Midfielder',
                'sort_order' => 9,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 10,
                'abbreviation' => 'LM' ,
                'long_name' => 'Left Midfielder',
                'sort_order' => 10,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 11,
                'abbreviation' => 'CM' ,
                'long_name' => 'Central Midfielder',
                'sort_order' => 11,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 12,
                'abbreviation' => 'AM' ,
                'long_name' => 'Attacking Midfielder',
                'sort_order' => 12,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 13,
                'abbreviation' => 'RW' ,
                'long_name' => 'Right Winger',
                'sort_order' => 13,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 14,
                'abbreviation' => 'LW' ,
                'long_name' => 'Left Winger',
                'sort_order' => 14,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 15,
                'abbreviation' => 'SS' ,
                'long_name' => 'Support Striker',
                'sort_order' => 15,
                'date_added' => time(),
                'date_updated' => time(),
            ),
            array(
                'id' => 16,
                'abbreviation' => 'ST' ,
                'long_name' => 'Striker',
                'sort_order' => 16,
                'date_added' => time(),
                'date_updated' => time(),
            ),
        );

        if ($this->db->insert_batch($tableName, $data)) {
            return true;
        }

        return false;
    }
}