<div class="row-fluid">
    <div class="span12">

        <h2><?php echo $this->lang->line('club_statistics_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("club_statistics_matches") . ')' : ''); ?></h2>

<?php
$i = 0;
foreach ($this->Cache_Club_Statistics_model->methodMap as $statisticGroup => $method) {
    foreach ($venues as $venue) {
        if (0 == $i % 2) { ?>
        <div class="row-fluid">
        <?php
        } ?>
            <div class="span6">
                <?php
                Club_Statistics_helper::$method($statistics, $venue); ?>
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

<?php
$i = 0;
foreach ($this->Cache_Club_Statistics_model->hungryMethodMap as $statisticGroup => $method) {
    if (0 == $i % 2) { ?>
        <div class="row-fluid">
    <?php
    } ?>
            <div class="span6">
                        <?php
                Club_Statistics_helper::$method($statistics); ?>
            </div>
        <?php
    if (1 == $i % 2) { ?>
        </div>
    <?php
    }

    $i++;
}

if (1 == $i % 2) { ?>
        </div>
<?php
} ?>
    </div>
</div>



