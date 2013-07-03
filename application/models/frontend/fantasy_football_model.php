<?php
require_once('_base_frontend_model.php');

/**
 * Model for Fantasy Football Page
 */
class Fantasy_Football_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'cache_fantasy_football_statistics';

        $this->load->model('Cache_Fantasy_Football_model');

        $this->positions = array(
            'gk' => array(
                'ids' => array(1),
                'preferredPositions' => array(
                    1, 'gk',
                ),
            ),
            'rb' => array(
                'ids' => array(2),
                'preferredPositions' => array(
                    2, 6,
                ),
            ),
            'lb' => array(
                'ids' => array(3),
                'preferredPositions' => array(
                    3, 7,
                ),
            ),
            'cb' => array(
                'ids' => array(4),
                'preferredPositions' => array(
                    4, 5, 8,
                ),
            ),
            'sw' => array(
                'ids' => array(5),
                'preferredPositions' => array(
                    4, 5, 8,
                ),
            ),
            'rwb' => array(
                'ids' => array(6),
                'preferredPositions' => array(
                    6, 2, 9,
                ),
            ),
            'lwb' => array(
                'ids' => array(7),
                'preferredPositions' => array(
                    7, 3, 10,
                ),
            ),
            'dm' => array(
                'ids' => array(8),
                'preferredPositions' => array(
                    8, 11, 4,
                ),
            ),
            'rm' => array(
                'ids' => array(9),
                'preferredPositions' => array(
                    9, 13,
                ),
            ),
            'lm' => array(
                'ids' => array(10),
                'preferredPositions' => array(
                    10, 14,
                ),
            ),
            'cm' => array(
                'ids' => array(11),
                'preferredPositions' => array(
                    11, 8, 12,
                ),
            ),
            'am' => array(
                'ids' => array(12),
                'preferredPositions' => array(
                    12, 15, 11,
                ),
            ),
            'rw' => array(
                'ids' => array(13),
                'preferredPositions' => array(
                    13, 9,
                ),
            ),
            'lw' => array(
                'ids' => array(14),
                'preferredPositions' => array(
                    14, 10,
                ),
            ),
            'ss' => array(
                'ids' => array(15),
                'preferredPositions' => array(
                    15, 16, 12,
                ),
            ),
            'st' => array(
                'ids' => array(16),
                'preferredPositions' => array(
                    16, 15, 12,
                ),
            ),
            'def' => array(
                'ids' => $this->Cache_Fantasy_Football_model->fetchPositions('def'),
                'preferredPositions' => array(
                    'def'
                ),
            ),
            'mid' => array(
                'ids' => $this->Cache_Fantasy_Football_model->fetchPositions('mid'),
                'preferredPositions' => array(
                    'mid'
                ),
            ),
            'att' => array(
                'ids' => $this->Cache_Fantasy_Football_model->fetchPositions('att'),
                'preferredPositions' => array(
                    'att'
                ),
            ),
            'sub' => array(
                'ids' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16),
                'preferredPositions' => array('all'),
            ),
        );

        $this->formations = array(
            '4-4-2' => array(
                'name' => '4-4-2',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "rm", "lm", "cm1", "cm2", "st1", "st2", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '4-4-2-diamond' => array(
                'name' => '4-4-2 Diamond',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "dm", "rm", "lm", "am", "st1", "st2", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '4-4-1-1' => array(
                'name' => '4-4-2',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "rm", "lm", "cm1", "cm2", "ss", "st1", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '4-3-3-narrow' => array(
                'name' => '4-3-3 Narrow',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "cm1", "cm2", "cm3", "st1", "st2", "st3", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '4-3-3-wide' => array(
                'name' => '4-3-3 Wide',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "cm1", "cm2", "cm3", "rw", "lw", "st1", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '4-3-2-1' => array(
                'name' => '4-3-2-1',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "cm1", "cm2", "cm3", "am1", "am2", "st1", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '4-2-2-2' => array(
                'name' => '4-2-2-2',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "cm1", "cm2", "am1", "am2", "st1", "st2", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '5-3-2' => array(
                'name' => '5-3-2',
                'positions' => array("gk", "cb1", "cb2", "cb3", "rwb", "lwb", "cm1", "cm2", "cm3", "st1", "st2", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '5-3-2-sweeper' => array(
                'name' => '5-3-2 Sweeper',
                'positions' => array("gk", "sw", "cb1", "cb2", "rwb", "lwb", "cm1", "cm2", "cm3", "st1", "st2", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '3-4-3' => array(
                'name' => '3-4-3',
                'positions' => array("gk", "cb1", "cb2", "cb3", "rm", "lm", "cm1", "cm2", "st1", "st2", "st3", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '3-5-2' => array(
                'name' => '3-5-2',
                'positions' => array("gk", "cb1", "cb2", "cb3", "dm", "rm", "lm", "cm1", "cm2", "st1", "st2", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '3-6-1' => array(
                'name' => '3-6-1',
                'positions' => array("gk", "cb1", "cb2", "cb3", "dm1", "dm2", "rm", "lm", "am1", "am2", "st1", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '4-5-1' => array(
                'name' => '4-5-1',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "rm", "lm", "cm1", "cm2", "cm3", "st1", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '4-2-3-1' => array(
                'name' => '4-2-3-1',
                'positions' => array("gk", "rb", "lb", "cb1", "cb2", "dm1", "dm2", "rm", "lm", "am", "st1", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '5-4-1' => array(
                'name' => '5-4-1',
                'positions' => array("gk", "cb1", "cb2", "cb3", "rwb", "lwb", "rm", "lm", "cm1", "cm2", "st1", "sub1", "sub2", "sub3", "sub4", "sub5"),
            ),
            '5-a-side' => array(
                'name' => '5-a-side',
                'positions' => array("gk", "def1", "mid1", "mid2", "att1", "sub1", "sub2", "sub3"),
            ),
            '6-a-side' => array(
                'name' => '6-a-side',
                'positions' => array("gk", "def1", "def2", "mid1", "mid2", "att1", "sub1", "sub2", "sub3"),
            ),
            '7-a-side' => array(
                'name' => '7-a-side',
                'positions' => array("gk", "def1", "def2", "mid1", "mid2", "att1", "att2", "sub1", "sub2", "sub3"),
            )
        );
    }
    /**
     * Fetch all Fantasy Football data
     * @param  string  $season          Season of Statistics
     * @param  string  $type            Type of Statistics
     * @param  string  $position        Playing Position
     * @param  string  $orderBy         Order Data by...
     * @return mixee                    List of Players
     */
    public function fetchAll($season, $type, $position, $orderBy)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('season', $season)
            ->where('type', $type)
            ->where('position', $position)
            ->order_by($this->getOrderBy($orderBy), 'desc');

        return $this->db->get()->result();
    }

    /**
     * Calculate best possible lineup for specified formation
     * @param  string $formation    The specified formation
     * @param  string $season       Which season's data to use
     * @param  string $type         Which competition's data to use
     * @param  string $measure      What measurement is being use to calculate the lineup (avg points, total points, appearances)
     * @return array                The lineup
     */
    public function fetchBestLineup($formation, $season, $type, $measure)
    {
        if (isset($this->formations[$formation])) {
            $playerCount = $this->playerCount($season, $type);

            if ($playerCount > 0) {
                // Full list of formation positions
                $positions = $this->fetchFormationPositions($this->formations[$formation]['positions'], $playerCount);

                // Calculate each position's list of preferred players, in order - Begin
                $preferredPlayers = array();
                $preferredPlayersRaw = array();
                foreach ($positions as $position) {
                    $simplePosition = Fantasy_Football_helper::fetchSimplePosition($position);

                    if (!isset($preferredPlayersRaw[$simplePosition])) {
                        $players = $this->fetchPreferredPlayersInParticularPosition($simplePosition, $season, $type, $measure);

                        $preferredPlayersRaw[$simplePosition] = explode(",", $players[0]->players);
                    }

                    $preferredPlayers[$position] = $preferredPlayersRaw[$simplePosition];
                }
                // Calculate each position's list of preferred players, in order - End

                // Calculate each player's list of preferred positions, in order - Begin
                $preferredPositions = array();
                foreach ($preferredPlayers[$position] as $playerId) {
                    $player = $this->createPlayerObject($positions);
                    $preferredPositions[$playerId] = $this->calculatePreferredPositionsForPlayer($playerId, $player, $season, $type);
                }
                // Calculate each player's list of preferred positions, in order - End

                // List of players
                $listOfPlayers = reset($preferredPlayers);

                // Find Best Lineup
                $FantasyFootballStable = new FantasyFootballStable($listOfPlayers, $positions, $preferredPositions, $preferredPlayers);

                return $FantasyFootballStable->matches;
            }
        }

        return false;
    }

    /**
     * Fetch a list of players that could play in a particular position (in order of best to worst)
     * @param  string $specifiedPosition    The position
     * @param  string $season               Which season's data to use
     * @param  string $type                 Which competition's data to use
     * @param  string $measure              What measurement is being use to calculate the lineup (avg points, total points, appearances)
     * @return mixed                        List of players, or false if none exist
     */
    public function fetchPreferredPlayersInParticularPosition($specifiedPosition, $season, $type, $measure)
    {
        $orderBy = $this->getOrderBy($measure);
        $pointOrder = 'DESC';

        if ($specifiedPosition == 'sub') {
            $pointOrder = 'ASC';
        }

        if (isset($this->positions[$specifiedPosition])) {
            $positions   = $this->positions[$specifiedPosition]['preferredPositions'];

            $orderByConditions  = array();
            $orderByConditions2 = array();

            foreach ($positions as $position) {
                $orderByConditions[]  = "cffs.position = '{$position}' DESC";
                $orderByConditions2[] = "a.position = '{$position}' DESC";
            }

            $orderByConditions[]  = "cffs.{$orderBy} {$pointOrder}";
            $orderByConditions2[] = "a.{$orderBy} {$pointOrder}";

            $sql = "SELECT GROUP_CONCAT(DISTINCT(a.player_id)) as players
FROM (SELECT *
    FROM cache_fantasy_football_statistics cffs
    WHERE cffs.season = '{$season}'
        AND cffs.type = '{$type}'
    ORDER BY " . implode(", ", $orderByConditions) . ") a
ORDER BY " . implode(", ", $orderByConditions2);

            $query = $this->db->query($sql);
            return $query->result();
        }

        return false;
    }

    /**
     * Fetch an ordered list of positions the specified player woud prefer to play in
     * @param  int $playerId        Unique Player ID
     * @param  string $season       Which season's data to use
     * @param  string $type         Which competition's data to use
     * @param  string $measure      What measurement is being use to calculate the lineup (avg points, total points, appearances)
     * @return array                List of appearances with the number of points the player earned in each position
     */
    public function fetchPreferredPositionsForParticularPlayer($playerId, $season, $type, $measure)
    {
        $orderBy = $this->getOrderBy($measure);

        $sql = "SELECT cffs.*, IFNULL(LCASE(p.abbreviation), cffs.position) as stored_position
FROM cache_fantasy_football_statistics cffs
LEFT JOIN position p ON p.id = cffs.position
WHERE cffs.season = '{$season}'
    AND cffs.type = '{$type}'
    AND cffs.player_id = '{$playerId}'
    AND cffs.position != 'all'
ORDER BY {$orderBy} DESC";

        $query = $this->db->query($sql);

        $appearances = array();
        foreach ($query->result() as $appearance) {
            $appearances[$appearance->stored_position] = $appearance->total_points;
        }

        return $appearances;
    }

    /**
     * The number of players for the specified season
     * @param  string $season       Which season's data to use
     * @param  string $type         Which competition's data to use
     * @return int                  Number of Players
     */
    public function playerCount($season, $type)
    {
        $ci = get_instance();

        $ci->load->model('frontend/Player_model');

        return count($ci->Player_model->fetchPlayerList($season, $type, '', 'DESC'));
    }

    /**
     * Calculate a Player's preferred positions based firstly on number of points earned in each position, then on the number of appearances in each position, then order the subtitute positions
     * @param  int $playerId        Unique Player ID
     * @param  array $player        Player object with all positions for this array
     * @param  string $season       Which season's data to use
     * @param  string $type         Which competition's data to use
     * @return array                Player with their preferred positions, in order from favourite to least favourite
     */
    public function calculatePreferredPositionsForPlayer($playerId, $player, $season, $type)
    {
        // Add number of points a player has earned in each position - Begin
        $playerPoints = $this->fetchPreferredPositionsForParticularPlayer($playerId, $season, $type, '');

        foreach ($player as $position => $points) {
            $simplePosition = Fantasy_Football_helper::fetchSimplePosition($position);

            if (isset($playerPoints[$simplePosition])) {
                $player[$position] = $points + (int) $playerPoints[$simplePosition];
            }
        }
        // Add number of points a player has earned in each position - End

        // Add number of appearances a player has made in each position - Begin
        $appearances = $this->fetchPlayerPositionPreference($playerId);

        foreach ($player as $position => $points) {
            $simplePosition = Fantasy_Football_helper::fetchSimplePosition($position);

            if (isset($appearances[$simplePosition])) {
                $player[$position] = $points + $appearances[$simplePosition];
            }
        }
        // Add number of appearances a player has made in each position - Begin

        // Order substitute positions - Begin
        $subCount = count($player);
        foreach ($player as $position => $points) {
            $simplePosition = Fantasy_Football_helper::fetchSimplePosition($position);

            if ($simplePosition == 'sub') {
                $player[$position] = (float) ("-0.{$subCount}");
                $subCount--;
            }
        }
        // Order substitute positions - End

        // Order a Player's preferred positions by the number of points they've gained in each position
        arsort($player);

        return array_keys($player);
    }

    /**
     * Fetch the positions a player would prefer to play in
     * @param  int $playerId    Unique Player ID
     * @return array            Array with a Player's preferred positions marked-up
     */
    public function fetchPlayerPositionPreference($playerId)
    {
        $sql = "SELECT COUNT(ptp.id) as preferred, LCASE(p.abbreviation) as position
FROM player_to_position ptp
LEFT JOIN position p ON p.id = ptp.position_id
WHERE
    ptp.player_id = {$playerId}
    AND ptp.deleted = 0
    AND p.deleted = 0
 GROUP BY ptp.position_id";

        $query = $this->db->query($sql);

        $appearances = array();
        foreach($query->result() as $appearance) {
            $appearances[$appearance->position] = $appearance->preferred;
        }

        return $appearances;
    }

    /**
     * Create a Player with all positions preferences set to zero
     * @param  array $positions  List of positions for specified formation
     * @return array             Player with all positions preferences set to zero
     */
    public function createPlayerObject($positions)
    {
        $player = array();

        foreach ($positions as $position) {
            $player[$position] = 0;
        }

        return $player;
    }

    /**
     * Return an array of all positions to for this formation (including the correct number of substitures)
     * @param  array $positions        List of positions for specified formation
     * @param  int $numberOfPlayers    Number of players involved (to return the correct number of subsitutes)
     * @return array                   List of positions with correct number of subsitutes
     */
    public function fetchFormationPositions($positions, $numberOfPlayers)
    {
        $data = array();

        $positionCount = 0;
        foreach ($positions as $position) {
            $data[] = $position;
            $positionCount++;
        }

        // Substitute positions
        $i = 1;
        while ($positionCount < $numberOfPlayers) {
            if(!in_array("sub{$i}", $data)) {
                $data[] = "sub{$i}";
                $positionCount++;
            }
            $i++;
        }

        return $data;
    }

    /**
     * Fetch info on specified formation
     * @param  string $formation   Specified Formation
     * @return array               Formation Information
     */
    public function fetchFormationInfo($formation)
    {
        if (isset($this->formations[$formation])) {
            return $this->formations[$formation];
        }

        return false;
    }

    /**
     * Return string of fields to order data by
     * @param  string $orderBy  Fields passed
     * @return string           Processed string of fields
     */
    public function getOrderBy($orderBy)
    {
        switch ($orderBy) {
            case 'average-points':
                return 'points_per_game';
                break;
            case 'appearances':
                return 'appearances';
                break;
        }

        return 'total_points';
    }

}





























class Stable
{
    public $guys = array();

    public $girls = array();

    public $guyPrefers = array();

    public $girlPrefers = array();

    public $matches = array();

    public function __construct($guys, $girls, $guyPrefers, $girlPrefers)
    {
        $this->guys        = $guys;
        $this->girls       = $girls;
        $this->guyPrefers  = $guyPrefers;
        $this->girlPrefers = $girlPrefers;

        $this->matches     = $this->match();
    }

    public function test()
    {
        foreach ($this->matches as $female => $male) {
            print "{$male} is engaged to {$female}\r\n";
        }

        if ($this->checkMatches()) {
            print "Marriages are stable\r\n";
        } else {
            print "Marriages are unstable\r\n";
        }

        $tmp = $this->girls[0];
        $this->matches[$this->girls[0]] = $this->matches[$this->girls[1]];
        $this->matches[$this->girls[1]] = $tmp;

        print "{$this->girls[0]} and {$this->girls[1]} have swapped partners\r\n";

        if ($this->checkMatches()) {
            print "Marriages are stable\r\n";
        } else {
            print "Marriages are unstable\r\n";
        }
    }

    public function match()
    {
        $engagedTo = array();
        $freeGuys = $this->guys;

        while (!empty($freeGuys)) {
            $thisGuy = array_shift($freeGuys); //get a load of THIS guy
            $thisGuyPrefers = $this->guyPrefers[$thisGuy];

            foreach ($thisGuyPrefers as $girl) {
                if (!isset($engagedTo[$girl])) { //girl is free
                    $engagedTo[$girl] = $thisGuy; //awww
                    break;
                } else {
                    $otherGuy = $engagedTo[$girl];
                    $thisGirlPrefers = $this->girlPrefers[$girl];

                    if (array_search($thisGuy, $thisGirlPrefers) < array_search($otherGuy, $thisGirlPrefers)) { //this girl prefers this guy to the guy she's engaged to
                        $engagedTo[$girl] = $thisGuy;
                        $freeGuys[] = $otherGuy;
                        break;
                    } //else no change...keep looking for this guy
                }
            }
        }

        return $engagedTo;
    }

    public function checkMatches()
    {
        if (count(array_intersect($this->girls, array_keys($this->matches))) != count($this->matches)) {
            return false;
        }

        if (count(array_intersect($this->guys, $this->matches)) != count($this->matches)) {
            return false;
        }

        $invertedMatches = array();
        foreach ($this->matches as $female => $male) {
            $invertedMatches[$male] = $female;
        }

        foreach ($this->matches as $female => $male) {
            $shePrefers = $this->girlPrefers[$female];
            $sheLikesBetter = array_slice($shePrefers, array_search($male, $shePrefers));

            $hePrefers = $this->guyPrefers[$male];
            $heLikesBetter = array_slice($hePrefers, array_search($female, $hePrefers));

            foreach ($sheLikesBetter as $girl => $guy) {
                $guysFiance = $invertedMatches[$guy];

                $thisGuyPrefers = $this->guyPrefers[$guy];

                if (array_search($guysFiance, $thisGuyPrefers) < array_search($girl, $thisGuyPrefers)) {
                    printf("%s likes %s better than %s and %s likes %s better than their current partner\n", $girl, $guy, $male, $guy, $female);
                    return false;
                }
            }

            foreach ($heLikesBetter as $guy => $girl) {
                $girlsFiance = $this->matches[$girl];
                $thisGirlPrefers = $this->girlPrefers[$girl];

                if (array_search($girlsFiance, $thisGirlPrefers) < array_search($guy, $thisGirlPrefers)) {
                    printf("%s likes %s better than %s and %s likes %s better than their current partner\n", $guy, $girl, $female, $girl, $male);
                    return false;
                }
            }
        }

        return true;
    }
}

class FantasyFootballStable extends Stable
{

}