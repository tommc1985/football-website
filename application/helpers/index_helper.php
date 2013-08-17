<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Index Helper
 */
class Index_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * Display Latest News Section
     * @param  object $article   News Article
     * @return NULL
     */
    public static function latestNewsArticle($article)
    {
        $ci =& get_instance();
        $ci->load->helper('utility'); ?>

        <h2><?php echo $ci->lang->line('index_latest_news'); ?></h2>
        <h3><?php echo $article->title; ?></h3>
        <p><?php echo Utility_helper::truncate($article->content, 200); ?> (<a href="<?php echo site_url("news/view/id/{$article->id}"); ?>"><?php echo $ci->lang->line('index_read_more'); ?></a>)</p>
        <?php
    }

    /**
     * Display Top Scorers Section
     * @param  array $players   Players
     * @return NULL
     */
    public static function topScorers($players)
    {
        $ci =& get_instance();
        $ci->load->helper(array('player', 'utility')); ?>

        <h2><?php echo $ci->lang->line('index_top_scorers'); ?></h2>
        <table class="width-100-percent table table-condensed table-striped">
            <thead>
                <tr>
                    <td class="width-85-percent"><?php echo $ci->lang->line('player_player'); ?></td>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line('player_goals'); ?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($players as $player) { ?>
                <tr>
                    <td class="width-85-percent" data-title="<?php echo $ci->lang->line('player_player'); ?>"><?php echo Player_helper::fullNameReverse($player); ?></td>
                    <td class="width-15-percent text-align-center" data-title="<?php echo $ci->lang->line('player_goals'); ?>"><?php echo $player->goals; ?></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Display Top Assisters Section
     * @param  array $players   Players
     * @return NULL
     */
    public static function topAssisters($players)
    {
        $ci =& get_instance();
        $ci->load->helper(array('player', 'utility')); ?>

        <h2><?php echo $ci->lang->line('index_top_assisters'); ?></h2>
        <table class="width-100-percent table table-condensed table-striped">
            <thead>
                <tr>
                    <td class="width-85-percent"><?php echo $ci->lang->line('player_player'); ?></td>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line('player_assists'); ?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($players as $player) { ?>
                <tr>
                    <td class="width-85-percent" data-title="<?php echo $ci->lang->line('player_player'); ?>"><?php echo Player_helper::fullNameReverse($player); ?></td>
                    <td class="width-15-percent text-align-center" data-title="<?php echo $ci->lang->line('player_assists'); ?>"><?php echo $player->assists; ?></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Display Most MotMs Section
     * @param  array $players   Players
     * @return NULL
     */
    public static function mostMotMs($players)
    {
        $ci =& get_instance();
        $ci->load->helper(array('player', 'utility')); ?>

        <h2><?php echo $ci->lang->line('index_most_motms'); ?></h2>
        <table class="width-100-percent table table-condensed table-striped">
            <thead>
                <tr>
                    <td class="width-85-percent"><?php echo $ci->lang->line('player_player'); ?></td>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line('player_motms'); ?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($players as $player) { ?>
                <tr>
                    <td class="width-85-percent" data-title="<?php echo $ci->lang->line('player_player'); ?>"><?php echo Player_helper::fullNameReverse($player); ?></td>
                    <td class="width-15-percent text-align-center" data-title="<?php echo $ci->lang->line('player_motms'); ?>"><?php echo $player->motms; ?></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Display Worst Discipline Section
     * @param  array $players   Players
     * @return NULL
     */
    public static function worstDiscipline($players)
    {
        $ci =& get_instance();
        $ci->load->helper(array('player', 'utility')); ?>

        <h2><?php echo $ci->lang->line('index_worst_discipline'); ?></h2>
        <table class="width-100-percent table table-condensed table-striped">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $ci->lang->line('player_player'); ?></td>
                    <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/red-16x16.png'); ?>" alt="<?php echo $ci->lang->line('player_reds'); ?>"></td>
                    <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/yellow-16x16.png'); ?>" alt="<?php echo $ci->lang->line('player_yellows'); ?>"></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($players as $player) { ?>
                <tr>
                    <td class="width-80-percent" data-title="<?php echo $ci->lang->line('player_player'); ?>"><?php echo Player_helper::fullNameReverse($player); ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $ci->lang->line('player_reds'); ?>"><?php echo $player->reds; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $ci->lang->line('player_yellows'); ?>"><?php echo $player->yellows; ?></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Display Top Fantasy Footballers Section
     * @param  array $players   Players
     * @return NULL
     */
    public static function fantasyFootballers($players)
    {
        $ci =& get_instance();
        $ci->load->helper(array('player', 'utility')); ?>

        <h2><?php echo $ci->lang->line('index_top_fantasy_footballers'); ?></h2>
        <table class="width-100-percent table table-condensed table-striped">
            <thead>
                <tr>
                    <td class="width-85-percent"><?php echo $ci->lang->line('fantasy_football_player'); ?></td>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line('fantasy_football_points'); ?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($players as $player) { ?>
                <tr>
                    <td class="width-85-percent" data-title="<?php echo $ci->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($player); ?></td>
                    <td class="width-15-percent text-align-center" data-title="<?php echo $ci->lang->line('fantasy_football_points'); ?>"><?php echo $player->total_points; ?></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Display On This Day Section
     * @param  array $matches   Matches
     * @return NULL
     */
    public static function onThisDay($matches)
    {
        $ci =& get_instance();
        $ci->load->helper(array('competition', 'match', 'opposition', 'player', 'utility'));

        $index = array_rand($matches);
        $match = $matches[$index]; ?>

        <h2><?php echo $ci->lang->line('index_on_this_day'); ?></h2>
        <h3><?php echo Utility_helper::formattedDate($match->date, "Y"); ?></h3>
        <h4><?php echo Match_helper::score($match); ?> <?php echo $ci->lang->line('match_vs'); ?> <?php echo Opposition_helper::name($match->opposition_id); ?></h4>
        <p><?php echo Match_helper::shortCompetitionNameCombined($match); ?></p>
        <p><a href="<?php echo site_url("match/view/id/{$match->id}"); ?>"><?php echo $ci->lang->line('index_view_match_details'); ?></a></p>
        <?php
    }

    /**
     * Display Recent Results Section
     * @param  array $matches   Matches
     * @return NULL
     */
    public static function recentResults($matches)
    {
        $ci =& get_instance();
        $ci->load->helper(array('competition', 'match', 'opposition', 'player', 'utility')); ?>

        <h2><?php echo $ci->lang->line('index_recent_results'); ?></h2>
        <table class="no-more-tables table table-condensed table-striped">
            <thead>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo $ci->lang->line('match_date'); ?></td>
                    <td class="width-35-percent"><?php echo $ci->lang->line('match_opposition'); ?></td>
                    <td class="width-35-percent"><?php echo $ci->lang->line('match_competition'); ?></td>
                    <td class="width-5-percent">&nbsp;</td>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line('match_score'); ?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($matches as $match) { ?>
                <tr>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $ci->lang->line('match_date'); ?>"><?php echo Utility_helper::formattedDate($match->date, "d/m"); ?></td>
                    <td class="width-35-percent" data-title="<?php echo $ci->lang->line('match_opposition'); ?>"><?php echo Opposition_helper::name($match->opposition_id); ?></td>
                    <td class="width-35-percent" data-title="<?php echo $ci->lang->line('match_competition'); ?>"><?php echo Match_helper::shortCompetitionNameCombined($match); ?></td>
                    <td class="width-5-percent text-align-center" data-title="<?php echo $ci->lang->line('match_venue'); ?>"><?php echo Match_helper::venue($match); ?></td>
                    <td class="width-15-percent text-align-center" data-title="<?php echo $ci->lang->line('match_score'); ?>"><a itemprop="url" href="<?php echo site_url('/match/view/id/' . $match->id); ?>"><?php echo Match_helper::score($match); ?></a></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Display Upcoming Fixtures Section
     * @param  array $matches   Matches
     * @return NULL
     */
    public static function upcomingFixtures($matches)
    {
        $ci =& get_instance();
        $ci->lang->load('match');
        $ci->load->helper(array('competition', 'match', 'opposition', 'player', 'utility')); ?>

        <h2><?php echo $ci->lang->line('index_upcoming_fixtures'); ?></h2>
        <table class="no-more-tables table table-condensed table-striped">
            <thead>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo $ci->lang->line('match_date'); ?></td>
                    <td class="width-35-percent"><?php echo $ci->lang->line('match_opposition'); ?></td>
                    <td class="width-35-percent"><?php echo $ci->lang->line('match_competition'); ?></td>
                    <td class="width-5-percent">&nbsp;</td>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line('match_score'); ?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($matches as $match) { ?>
                <tr>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $ci->lang->line('match_date'); ?>"><?php echo $match->date ? Utility_helper::formattedDate($match->date, "d/m") : $ci->lang->line('match_t_b_c'); ?></td>
                    <td class="width-35-percent" data-title="<?php echo $ci->lang->line('match_opposition'); ?>"><?php echo Opposition_helper::name($match->opposition_id); ?></td>
                    <td class="width-35-percent" data-title="<?php echo $ci->lang->line('match_competition'); ?>"><?php echo Match_helper::shortCompetitionNameCombined($match); ?></td>
                    <td class="width-5-percent text-align-center" data-title="<?php echo $ci->lang->line('match_venue'); ?>"><?php echo Match_helper::venue($match); ?></td>
                    <td class="width-15-percent text-align-center" data-title="<?php echo $ci->lang->line('match_score'); ?>"><a itemprop="url" href="<?php echo site_url('/match/view/id/' . $match->id); ?>"><?php echo Match_helper::score($match); ?></a></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Display Upcoming Events Section
     * @param  array $events   Events
     * @return NULL
     */
    public static function upcomingEvents($events)
    {
        $ci =& get_instance();
        $ci->load->helper(array('competition', 'match', 'opposition', 'player', 'utility')); ?>

        <h2><?php echo $ci->lang->line('index_upcoming_events'); ?></h2>
        <table class="no-more-tables table table-condensed table-striped">
            <thead>
                <tr>
                    <td class="width-25-percent">Start Date</td>
                    <td class="width-25-percent">End Date</td>
                    <td class="width-50-percent">Event</td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($events as $event) { ?>
                <tr>
                    <td class="width-25-percent" data-title="<?php echo $ci->lang->line('index_start_date'); ?>"><?php
                    if ($event->all_day) {
                        echo Utility_helper::formattedDate($event->start_datetime, "jS M");
                    } else {
                        echo Utility_helper::formattedDate($event->start_datetime, "jS M, H:i");
                    }; ?></td>
                    <td class="width-25-percent" data-title="<?php echo $ci->lang->line('index_end_date'); ?>"><?php
                    if ($event->all_day) {
                         echo $ci->lang->line('index_all_day_event');
                    } else {
                        echo Utility_helper::formattedDate($event->end_datetime, "jS M, H:i");
                    }; ?></td>
                    <td class="width-50-percent" data-title="<?php echo $ci->lang->line('index_event'); ?>"><?php echo $event->name; ?></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
        <?php
    }
}