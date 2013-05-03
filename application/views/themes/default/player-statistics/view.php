<h2><?php echo $this->lang->line('player_statistics_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("player_statistics_matches") . ')' : ''); ?></h2>

<p><?php
echo $this->lang->line('player_statistics_threshold_current');
if ($unit == 'percentage') {
    echo sprintf($this->lang->line('player_statistics_threshold_percentage'), $thresholdPercentage);
} else {
    echo sprintf($this->lang->line('player_statistics_threshold_matches'), $thresholdMatches);
} ?></p>

<?php
foreach($this->Cache_Player_Statistics_model->methodMap as $statisticGroup => $method) {
    foreach ($venues as $venue) {
        Player_Statistics_helper::$method($statistics, $venue, $thresholdMatches);
    }
} ?>

<?php
foreach($this->Cache_Player_Statistics_model->hungryMethodMap as $statisticGroup => $method) {
    Player_Statistics_helper::$method($statistics, $thresholdMatches);
} ?>