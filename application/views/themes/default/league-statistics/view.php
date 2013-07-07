<div class="row-fluid">
    <div class="span12">

        <h2><?php echo $this->lang->line('league_statistics_title'); ?> - <?php echo League_helper::name($id); ?></h2>

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
                League_Statistics_helper::$method($statistics, $venue); ?>
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