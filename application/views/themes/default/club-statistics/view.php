<h2><?php echo $this->lang->line('clubs_statistics_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?></h2>

<?php
foreach($this->Cache_Club_Statistics_model->methodMap as $statisticGroup => $method) {
    foreach ($venues as $venue) {
        Club_Statistics_helper::$method($statistics, $venue);
    }
} ?>

<?php
foreach($this->Cache_Club_Statistics_model->hungryMethodMap as $statisticGroup => $method) {
    Club_Statistics_helper::$method($statistics);
} ?>