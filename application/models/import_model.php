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
        $this->importCardData();
        $this->importCompetitionData();
        $this->importCompetitionStageData();
        $this->importContentData();
        $this->importGoalData();
        $this->importImageData();
        $this->importMatchesData();
        $this->importOfficialData();
        $this->importOppositionData();
        $this->importPlayerData();
        $this->importPlayerRegistrationData();
        $this->importPlayerToPositionData();
        $this->importPositionData();
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
            if ($object->position == 0 && $object->status != 'unused') {
                die('Problem with appearance data');
            }
            $object = $object;
            $object->position =  $object->position == 0 ? NULL : $object->position;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('appearance', $data);
    }

    /**
     * Insert Card Data
     * @return NULL
     */
    public function importCardData()
    {
        $objects = $this->fetchAll('_card');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->offence = $object->type == 'y' ? 8 : 15;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('card', $data);
    }

    /**
     * Insert Competition Data
     * @return NULL
     */
    public function importCompetitionData()
    {
        $objects = $this->fetchAll('_competition');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('competition', $data);
    }

    /**
     * Insert Competition Stage Data
     * @return NULL
     */
    public function importCompetitionStageData()
    {
        $objects = $this->fetchAll('_competition_stage');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('competition_stage', $data);
    }

    /**
     * Insert Content Data
     * @return NULL
     */
    public function importContentData()
    {
        $objects = $this->fetchAll('_content');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            unset($object->insert_date);
            unset($object->last_updated);
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('content', $data);
    }

    /**
     * Insert Goal Data
     * @return NULL
     */
    public function importGoalData()
    {
        $objects = $this->fetchAll('_goal');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('goal', $data);
    }

    /**
     * Insert Image Data
     * @return NULL
     */
    public function importImageData()
    {
        $objects = $this->fetchAll('_image');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('image', $data);
    }

    /**
     * Insert Matches Data
     * @return NULL
     */
    public function importMatchesData()
    {
        $objects = $this->fetchAll('_matches');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->opposition_id = $object->opposition;
            unset($object->opposition);
            $object->competition_id = $object->competition;
            unset($object->competition);
            $object->competition_stage_id = $object->stage;
            unset($object->stage);
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('matches', $data);
    }

    /**
     * Insert Official Data
     * @return NULL
     */
    public function importOfficialData()
    {
        $objects = $this->fetchAll('_official');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('official', $data);
    }

    /**
     * Insert Opposition Data
     * @return NULL
     */
    public function importOppositionData()
    {
        $objects = $this->fetchAll('_opposition');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('opposition', $data);
    }

    /**
     * Insert Player Data
     * @return NULL
     */
    public function importPlayerData()
    {
        $objects = $this->fetchAll('_player');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            unset($object->nationality);
            $object->nationality_id = 1; // All Players imported as English
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('player', $data);
    }

    /**
     * Insert Player Registration Data
     * @return NULL
     */
    public function importPlayerRegistrationData()
    {
        $this->ci->load->model('Season_model');
        $currentSeason = Season_model::fetchCurrentSeason();
        $season = Season_model::fetchEarliestYear();

        $data = array();

        while ($season <= $currentSeason) {
            $dates = Season_model::generateStartEndDates($season);

            $this->db->select('DISTINCT(player_id) as id')
                ->from('view_appearances_matches')
                ->where("(date {$dates['startDate']} AND date {$dates['endDate']})")
                ->order_by('id', 'asc');

            $players = $this->db->get()->result();

            foreach ($players as $player) {
                $data[] = array(
                    'player_id'    => $player->id,
                    'season'       => $season,
                    'date_added'   => time(),
                    'date_updated' => time(),
                );
            }

            $season++;
        }

        $this->db->insert_batch('player_registration', $data);
    }

    /**
     * Insert Player To Position Data
     * @return NULL
     */
    public function importPlayerToPositionData()
    {
        $objects = $this->fetchAll('_player_to_position');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('player_to_position', $data);
    }

    /**
     * Insert Position Data
     * @return NULL
     */
    public function importPositionData()
    {
        $objects = $this->fetchAll('_position');

        $data = array();

        foreach ($objects as $object) {
            $object = $object;
            $object->abbreviation = $object->short_name;
            unset($object->short_name);
            $object->date_added = time();
            $object->date_updated = time();

            $data[] = (array) $object;
        }

        $this->db->insert_batch('position', $data);
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