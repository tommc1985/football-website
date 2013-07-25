<?php
require_once('_base_frontend_model.php');
require_once(APPPATH . 'libraries/icalcreator/iCalcreator.class.php');

/**
 * Model for Calendar
 */
class Calendar_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'calendar_event';

        $this->ci =& get_instance();
        $this->ci->load->model('frontend/Player_model');
        $this->ci->load->model('Player_Registration_model');
        $this->ci->load->model('Season_model');
    }

    /**
     * Return data for Calendar from Player's Birthdays, Upcoming Matches, Previous Results and One-Off Events (not yet implemented)
     * @param  array $dataToInclude   Which data to included
     * @return array                  Multidimensional array of aggregated Calendar Data
     */
    public function fetchData($dataToInclude = array())
    {
        $currentSeason = Season_model::fetchCurrentSeason();

        $data = array();
        if (in_array('players', $dataToInclude)) {
            $data['players'] = $this->ci->Player_Registration_model->fetchBySeason($currentSeason);
        }

        if (in_array('matches', $dataToInclude)) {
            $data['matches'] = $this->ci->Season_model->fetchMatches(false, $currentSeason);
        }

        if (in_array('events', $dataToInclude)) {
            $data['events'] = $this->fetchAll();
        }

        return $data;
    }

    /**
     * Generate the Calendar for the passed data
     * @param  array $calendarData    Calendar data
     * @param  string $calendarName   Name of Calendar
     * @return NULL
     */
    public function generateCalendar($calendarData, $calendarName)
    {
        $this->load->config('calendar', true);
        $timezone = $this->config->item('calendar_timezone', 'calendar');

        $configSettings = array(
            "unique_id" => site_url(),
            "TZID"      => $timezone,
        );

        $calendar = new vcalendar($configSettings);
        $calendar->setProperty("method", "PUBLISH"); // required of some calendar software
        $calendar->setProperty("x-wr-calname", $calendarName); // required of some calendar software
        $calendar->setProperty("X-WR-CALDESC", $calendarName); // required of some calendar software
        $calendar->setProperty("X-WR-TIMEZONE", $timezone); // required of some calendar software

        $extraProperties = array("X-LIC-LOCATION" => $timezone); // required of some calendar software
        iCalUtilityFunctions::createTimezone($calendar, $timezone, $extraProperties); // create timezone component(-s) opt. 1, based on present date

        if (isset($calendarData['players'])) {
            $this->_generatePlayerEvents($calendar, $calendarData['players']);
        }

        if (isset($calendarData['matches'])) {
            $this->_generateMatchEvents($calendar, $calendarData['matches']);
        }

        if (isset($calendarData['events'])) {
            $this->_generateOneOffEvents($calendar, $calendarData['events']);
        }

        $calendar->returnCalendar();
    }

    /**
     * Generate Events from passed Player data
     * @param  object $calendar  Calendar object
     * @param  array  $players   Player data
     * @return NULL
     */
    protected function _generatePlayerEvents(&$calendar, $players)
    {
        $events = array();

        foreach ($players as $player) {
            if ($player->dob) {
                $event =& $calendar->newComponent("vevent");

                $dateOfBirthTimestamp = strtotime($player->dob);
                $birthdayTimestampThisYear = mktime(0, 0, 0, date("n", $dateOfBirthTimestamp), date("j", $dateOfBirthTimestamp));

                $seasonStartDate = Season_model::fetchCurrentSeason() . '-' . Configuration::get('start_month') . '-' . Configuration::get('start_day') . '00:00:00';
                $startOfSeasonTimestamp = strtotime($seasonStartDate);

                $birthdayYear = date("Y");
                if ($birthdayTimestampThisYear < $startOfSeasonTimestamp) {
                    $birthdayYear++;
                }

                $birthdayTimeStamp = mktime(0, 0, 0, date("n", $dateOfBirthTimestamp), date("j", $dateOfBirthTimestamp), $birthdayYear);
                $playerAge = Utility_helper::getAge($dateOfBirthTimestamp, $birthdayTimeStamp);

                $event->setProperty("uid","player-{$player->id}@" . md5(site_url()));
                $event->setProperty( "dtstart", date("Ymd", $birthdayTimeStamp), array("VALUE" => "DATE"));
                $event->setProperty("summary", Player_helper::fullName($player, false) . sprintf($this->lang->line('calendar_players_nth_birthday'), Utility_helper::ordinalWithSuffix($playerAge)));
                $event->setProperty("description", Player_helper::fullName($player, false) . ' ' . sprintf($this->lang->line('calendar_player_is_age_on_this_day'), $playerAge, site_url('/player/view/id/' . $player->id)));
                $event->setProperty( "TRANSP", "TRANSPARENT" );
            }
        }
    }

    /**
     * Generate Events from passed Match data
     * @param  object $calendar  Calendar object
     * @param  array  $matches   Match data
     * @return NULL
     */
    protected function _generateMatchEvents(&$calendar, $matches)
    {
        $this->ci->lang->load('match');
        $this->ci->load->helper('match');

        foreach ($matches as $match) {
            if ($match->date) {
                $event =& $calendar->newComponent("vevent");

                $dateTimestamp = strtotime($match->date);

                $matchStartTimestamp = $dateTimestamp;
                $eventStart = array(
                    "year"  => date("Y", $matchStartTimestamp),
                    "month" => date("n", $matchStartTimestamp),
                    "day"   => date("j", $matchStartTimestamp),
                    "hour"  => date("H", $matchStartTimestamp),
                    "min"   => date("i", $matchStartTimestamp),
                    "sec"   => date("s", $matchStartTimestamp),
                );

                $matchEndTimestamp = $matchStartTimestamp + 6300; // Add an hour and 45 minutes to start time
                $eventEnd = array(
                    "year"  => date("Y", $matchEndTimestamp),
                    "month" => date("n", $matchEndTimestamp),
                    "day"   => date("j", $matchEndTimestamp),
                    "hour"  => date("H", $matchEndTimestamp),
                    "min"   => date("i", $matchEndTimestamp),
                    "sec"   => date("s", $matchEndTimestamp),
                );

                $event->setProperty("uid","match-{$match->id}@" . md5(site_url()));
                $event->setProperty("dtstart", $eventStart);
                $event->setProperty("dtend", $eventEnd);
                $event->setProperty("summary", Match_helper::competingTeamsString($match));
                $event->setProperty("description", sprintf($this->lang->line('calendar_match_details'), site_url('/match/view/id/' . $match->id)));

                if ($match->location) {
                    $event->setProperty("LOCATION", $match->location);
                }
            }
        }
    }

    /**
     * Generate Events from passed One Off Event data
     * @param  object $calendar          Calendar object
     * @param  array  $calendarEvents    Event data
     * @return NULL
     */
    protected function _generateOneOffEvents(&$calendar, $calendarEvents)
    {
        foreach ($calendarEvents as $calendarEvent) {
            $event =& $calendar->newComponent("vevent");

            if ($calendarEvent->all_day) {
                $event->setProperty( "dtstart", Utility_helper::formattedDate($calendarEvent->start_datetime, "Ymd"), array("VALUE" => "DATE"));
            } else {
                $eventStartTimestamp = strtotime($calendarEvent->start_datetime);
                $eventStart = array(
                    "year"  => date("Y", $eventStartTimestamp),
                    "month" => date("n", $eventStartTimestamp),
                    "day"   => date("j", $eventStartTimestamp),
                    "hour"  => date("H", $eventStartTimestamp),
                    "min"   => date("i", $eventStartTimestamp),
                    "sec"   => date("s", $eventStartTimestamp),
                );
                $event->setProperty("dtstart", $eventStart);

                $eventEndTimestamp = strtotime($calendarEvent->end_datetime);
                $eventEnd = array(
                    "year"  => date("Y", $eventEndTimestamp),
                    "month" => date("n", $eventEndTimestamp),
                    "day"   => date("j", $eventEndTimestamp),
                    "hour"  => date("H", $eventEndTimestamp),
                    "min"   => date("i", $eventEndTimestamp),
                    "sec"   => date("s", $eventEndTimestamp),
                );
                $event->setProperty("dtend", $eventEnd);
            }

            $event->setProperty("uid","event-{$calendarEvent->id}@" . md5(site_url()));
            $event->setProperty("summary", $calendarEvent->name);
            $event->setProperty("description", $calendarEvent->description);

            if ($calendarEvent->location) {
                $event->setProperty("LOCATION", $calendarEvent->location);
            }
        }
    }

}