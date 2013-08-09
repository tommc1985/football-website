<div class="row-fluid">
    <div class="span12">

        <h2><?php echo $this->lang->line('league_statistics_title'); ?> - <?php echo League_helper::name($id); ?></h2>

        <h3><?php echo $this->lang->line("league_statistics_statistics_menu"); ?></h3>
        <ul class="nav nav-tabs nav-stacked">
        <?php
        foreach ($this->Cache_League_Statistics_model->methodMap as $statisticGroup => $method) {
            foreach ($venues as $venue) { ?>
            <li><a href="#<?php echo $statisticGroup; ?>"><?php echo $this->lang->line("league_statistics_{$statisticGroup}" . (strlen($venue) > 0 ? "_{$venue}" : '')); ?></a></li>
        <?php
            }
        } ?>
        </ul>

<?php
$i = 0;
foreach ($this->Cache_League_Statistics_model->methodMap as $statisticGroup => $method) {
    foreach ($venues as $venue) {
        if (0 == $i % 2) { ?>
        <div class="row-fluid">
        <?php
        } ?>
            <div class="span6">
                <?php
                League_Statistics_helper::$method($statistics, $season, $venue); ?>
            </div>
        <?php
        if (1 == $i % 2) { ?>
        </div>
        <?php
        }

        $i++;
    }
}

if (1 == $i % 2) { ?>
</div>
<?php
} ?>
    </div>
</div>