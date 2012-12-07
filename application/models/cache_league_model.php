<?php
class Cache_League_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public $tableName;
    public $queueTableName;

    public $clubs;
    public $leagueData;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
        $this->ci->load->model('League_model');
        $this->ci->load->model('Season_model');

        $this->tableName = 'cache_league_results_collated';
        $this->queueTableName = 'cache_queue_league_results_collated';
    }

    /**
     * Insert row into process queue table to be processed
     * @return NULL
     */
    public function insertEntries()
    {
        $leagues = $this->fetchAllLeagues();

        foreach ($leagues as $league) {
            $this->insertEntry($league->id);
        }
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int $leagueId     League ID
     * @return boolean
     */
    public function insertEntry($leagueId)
    {
        $data = array(
            'league_id' => $leagueId,
            'date_added' => time(),
            'date_updated' => time());

        return $this->db->insert($this->queueTableName, $data);
    }

    /**
     * Update row in process queue table to be processed
     * @param  object $object   Existing row in table
     * @return boolean
     */
    public function updateEntry($object)
    {
        $object->date_updated = time();
        $this->db->where('id', $object->id);
        return $this->db->update($this->queueTableName, $object);
    }

    /**
     * Fetch all leagues
     * @return array
     */
    public function fetchAllLeagues()
    {
        $this->db->select('*')
            ->from('league l')
            ->where('l.deleted', 0)
            ->order_by('l.id', 'asc');

        return $this->db->get()->result();
    }

    /**
     * Fetch latest rows to be processed/cached
     * @param  int     $limit  Number of rows to return
     * @return results Query Object
     */
    public function fetchLatest($limit = 1)
    {
        $this->db->select('*')
            ->from($this->queueTableName)
            ->where('in_progress', 0)
            ->where('completed', 0)
            ->where('deleted', 0)
            ->order_by('date_added, id', 'asc')
            ->limit($limit, 0);

        return $this->db->get()->result();
    }

    /**
     * Process latest tasks in queue
     * @return int  Number of rows processed
     */
    public function processQueuedRows()
    {
        $rowCount = 0;
        $rows = $this->fetchLatest();

        foreach($rows as $row) {
            $this->processQueuedRow($row);
            $rowCount++;
        }

        return $rowCount;
    }

    /**
     * Process latest task in queue
     * @param  object $row  Row from queued table
     * @return boolean      Result of exectuted query
     */
    public function processQueuedRow($row)
    {
        $startUnixTime = time();

        // Flag the row is being processed
        $row->in_progress = 1;
        $this->updateEntry($row);

        $this->generateStatistics($row->league_id);

        // Flag that row is no longer being processed and is complete
        $row->in_progress = 0;
        $row->completed = 1;

        $finishUnixTime = time();
        $row->process_duration = $finishUnixTime - $startUnixTime;

        $row->peak_memory_usage = number_format(memory_get_peak_usage(true) / 1048576, 2);

        return $this->updateEntry($row);
    }

    /**
     * Create League Results object
     * @param  int  $leagueId          Unique League Id
     * @param  int  $oppositionId      Unique Opposition Id
     * @param  string $type            Type ("overall", "home", "away")
     * @param  string|NULL $dateUntil  The date the league table data in the rest of the object includes
     * @return object League Table Statistics object
     */
    public function createObject($leagueId, $oppositionId, $type, $dateUntil = NULL)
    {
        $row = new stdClass();

        $row->league_id = $leagueId;
        $row->opposition_id = $oppositionId;
        $row->type = $type;

        $row->played = 0;
        $row->won = 0;
        $row->drawn = 0;
        $row->lost = 0;
        $row->gf = 0;
        $row->ga = 0;
        $row->gd = 0;
        $row->points = 0;
        $row->form = '';
        $row->date_until = is_null($dateUntil) ? 'overall' : $dateUntil;

        return $row;
    }

    /**
     * Calculate points from the specified match
     * @param  object   $match    Match Object
     * @return NULL
     */
    public function calculatePointsFromMatch($match)
    {
        $homeClub = $this->clubs[$match->h_opposition_id];
        $awayClub = $this->clubs[$match->a_opposition_id];

        switch (true) {
            case ((!is_null($match->h_score) && !is_null($match->h_score)) || in_array($match->status, array('hw', 'aw'))):

                // Home team - Begin
                // Played - Begin
                $homeClub['overall']->played++;
                $homeClub['home']->played++;
                // Played - End

                // Won - Begin
                if ($match->h_score > $match->a_score || $match->status == 'hw') {
                    $homeClub['overall']->won++;
                    $homeClub['home']->won++;

                    $homeClub['overall']->points += $this->leagueData->points_for_win;
                    $homeClub['home']->points += $this->leagueData->points_for_win;

                    $homeClub['overall']->form = '3' . $homeClub['overall']->form;
                    $homeClub['home']->form = '3' . $homeClub['home']->form;
                }
                // Won - End

                // Drawn - Begin
                if ($match->h_score == $match->a_score && !is_null($match->h_score)) {
                    $homeClub['overall']->drawn++;
                    $homeClub['home']->drawn++;

                    $homeClub['overall']->points += $this->leagueData->points_for_draw;
                    $homeClub['home']->points += $this->leagueData->points_for_draw;

                    $homeClub['overall']->form = '1' . $homeClub['overall']->form;
                    $homeClub['home']->form = '1' . $homeClub['home']->form;
                }
                // Drawn - End

                // Lost - Begin
                if ($match->h_score < $match->a_score || $match->status == 'aw') {
                    $homeClub['overall']->lost++;
                    $homeClub['home']->lost++;

                    $homeClub['overall']->form = '0' . $homeClub['overall']->form;
                    $homeClub['home']->form = '0' . $homeClub['home']->form;
                }
                // Lost - End

                // Goals For - Begin
                $homeClub['overall']->gf += $match->h_score;
                $homeClub['home']->gf += $match->h_score;
                // Goals For - End

                // Goals Against - Begin
                $homeClub['overall']->ga += $match->a_score;
                $homeClub['home']->ga += $match->a_score;
                // Goals Against - End

                // Goal Difference - Begin
                $homeClub['overall']->gd = $homeClub['overall']->gf - $homeClub['overall']->ga;
                $homeClub['home']->gd = $homeClub['home']->gf - $homeClub['home']->ga;
                // Goal Difference - End
                // Home team - End





                // Away team - Begin
                // Played - Begin
                $awayClub['overall']->played++;
                $awayClub['away']->played++;
                // Played - End

                // Won - Begin
                if ($match->h_score < $match->a_score || $match->status == 'aw') {
                    $awayClub['overall']->won++;
                    $awayClub['away']->won++;

                    $awayClub['overall']->points += $this->leagueData->points_for_win;
                    $awayClub['away']->points += $this->leagueData->points_for_win;

                    $awayClub['overall']->form = '3' . $awayClub['overall']->form;
                    $awayClub['away']->form = '3' . $awayClub['away']->form;
                }
                // Won - End

                // Drawn - Begin
                if ($match->h_score == $match->a_score && !is_null($match->h_score)) {
                    $awayClub['overall']->drawn++;
                    $awayClub['away']->drawn++;

                    $awayClub['overall']->points += $this->leagueData->points_for_draw;
                    $awayClub['away']->points += $this->leagueData->points_for_draw;

                    $awayClub['overall']->form = '1' . $awayClub['overall']->form;
                    $awayClub['away']->form = '1' . $awayClub['away']->form;
                }
                // Drawn - End

                // Lost - Begin
                if ($match->h_score > $match->a_score || $match->status == 'hw') {
                    $awayClub['overall']->lost++;
                    $awayClub['away']->lost++;

                    $awayClub['overall']->form = '0' . $awayClub['overall']->form;
                    $awayClub['away']->form = '0' . $awayClub['away']->form;
                }
                // Lost - End

                // Goals For - Begin
                $awayClub['overall']->gf += $match->a_score;
                $awayClub['away']->gf += $match->a_score;
                // Goals For - End

                // Goals Against - Begin
                $awayClub['overall']->ga += $match->h_score;
                $awayClub['away']->ga += $match->h_score;
                // Goals Against - End

                // Goal Difference - Begin
                $awayClub['overall']->gd = $awayClub['overall']->gf - $awayClub['overall']->ga;
                $awayClub['away']->gd = $awayClub['away']->gf - $awayClub['away']->ga;
                // Goal Difference - End
                // Away team - End
                break;
        }
    }

    /**
     * Generate statistics
     * @param  int $leagueId     League ID
     * @return boolean           Whether query was executed correctly
     */
    public function generateStatistics($leagueId)
    {
        $this->deleteStatistics($leagueId);

        $this->leagueData = $this->ci->League_model->fetchLeagueData($leagueId);

        $distinctDates = $this->ci->League_model->fetchDistinctMatchDates($leagueId);

        $object->date = NULL;
        $distinctDates[] = $object;

        foreach ($distinctDates as $distinctDate) {
            $matches = $this->ci->League_model->fetchMatches($leagueId, NULL, NULL, $distinctDate->date);

            $this->clubs = array();

            foreach($this->ci->League_model->fetchClubRegistrations($leagueId) as $club) {
                $this->clubs[$club->opposition_id] = array(
                    'overall' => $this->createObject($leagueId, $club->opposition_id, 'overall', $distinctDate->date),
                    'home' => $this->createObject($leagueId, $club->opposition_id, 'home', $distinctDate->date),
                    'away' => $this->createObject($leagueId, $club->opposition_id, 'away', $distinctDate->date),
                );
            }

            foreach ($matches as $match) {
                $this->calculatePointsFromMatch($match);
            }

            foreach ($this->clubs as $club) {
                foreach ($club as $resultsObject) {
                    $this->db->insert($this->tableName, $resultsObject);
                }
            }
        }
    }

    /**
     * Delete statistics
     * @param  int $leagueId      League ID
     * @return boolean            Were rows deleted
     */
    public function deleteStatistics($leagueId)
    {
        return $this->deleteRows($leagueId);
    }

    /**
     * Particular League Table Data, based on Season and/or Competition Type
     * @param  int $leagueId      League ID
     * @return boolean            Whether query was executed correctly
     */
    public function deleteRows($leagueId)
    {
        $whereConditions = array();

        $whereConditions['league_id'] = $leagueId;

        return $this->db->delete($this->tableName, $whereConditions);
    }

    /**
     * Empty table of cached
     * @return boolean Whether query was executed correctly
     */
    public function emptyCache()
    {
        return $this->db->truncate($this->tableName);
    }
}