<div class="row-fluid">
    <div class="span12">

    <?php
    echo form_open($this->uri->uri_string()); ?>

        <h2><?php echo $this->lang->line('fantasy_football_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("fantasy_football_matches") . ')' : ''); ?></h2>

        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
<?php
$inputType = array(
    'name'    => 'type',
    'id'      => 'type',
    'options' => array('overall' => 'Overall') + Competition_model::fetchTypes(),
    'value'   => set_value('type', $type),
);

$inputSeason = array(
    'name'    => 'season',
    'id'      => 'season',
    'options' => array('all-time' => 'All Time') + $this->Season_model->fetchForDropdown(),
    'value'   => set_value('season', $season),
); ?>
                    <div class="span4">
<?php
echo form_label($this->lang->line('fantasy_football_competition_type'), $inputType['name']);
echo form_dropdown($inputType['name'], $inputType['options'], $inputType['value']); ?>
                    </div>
                    <div class="span4">
<?php
echo form_label($this->lang->line('fantasy_football_season'), $inputSeason['name']);
echo form_dropdown($inputSeason['name'], $inputSeason['options'], $inputSeason['value']); ?>
                    </div>
                    <div class="span4">
<?php
echo form_submit('submit', $this->lang->line('fantasy_football_show')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <h3><?php echo $this->lang->line("fantasy_football_leaderboard"); ?></h3>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
<?php
$inputPosition = array(
    'name'    => 'position',
    'id'      => 'position',
    'options' => array('all' => $this->lang->line('fantasy_football_all')) + $this->Position_model->fetchForDropdown(),
    'value'   => set_value('position', $position),
);

$inputOrderBy = array(
    'name'    => 'order_by',
    'id'      => 'order_by',
    'options' => $this->Fantasy_Football_model->fetchOrderByForDropdown(),
    'value'   => set_value('order_by', $orderBy),
); ?>
                    <div class="span4">
<?php
echo form_label($this->lang->line('fantasy_football_position'), $inputPosition['name']);
echo form_dropdown($inputPosition['name'], $inputPosition['options'], $inputPosition['value']); ?>
                    </div>
                    <div class="span4">
<?php
echo form_label($this->lang->line('fantasy_football_order_by'), $inputOrderBy['name']);
echo form_dropdown($inputOrderBy['name'], $inputOrderBy['options'], $inputOrderBy['value']); ?>
                    </div>
                    <div class="span4">
<?php
echo form_submit('submit', $this->lang->line('fantasy_football_show')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span7">
                <h4><?php echo $this->lang->line('fantasy_football_leaderboard'); ?></h4>
                <table class="no-more-tables width-100-percent">
                    <thead>
                        <tr>
                            <td class="width-40-percent"><?php echo $this->lang->line('fantasy_football_player'); ?></td>
                            <td class="width-15-percent"><?php echo $this->lang->line('fantasy_football_appearances'); ?></td>
                            <td class="width-15-percent"><?php echo $this->lang->line('fantasy_football_average'); ?></td>
                            <td class="width-15-percent"><?php echo $this->lang->line('fantasy_football_points'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($fantasyFootballData) {
                            foreach ($fantasyFootballData as $player) { ?>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($player->player_id); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_appearances'); ?>"><?php echo $player->appearances; ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_average'); ?>"><?php echo $player->points_per_game; ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo $player->total_points; ?></td>
                        </tr>
                        <?php
                            }
                        } else { ?>
                        <tr>
                            <td colspan="4"><?php echo $this->lang->line('fantasy_football_no_data'); ?></td>
                        </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>

            <div class="span5">
                <h4><?php echo $this->lang->line('fantasy_football_scoring_system'); ?></h4>
                <table class="width-100-percent">
                    <thead>
                        <tr>
                            <td class="width-75-percent"><?php echo $this->lang->line('fantasy_football_criteria'); ?></td>
                            <td class="width-25-percent"><?php echo $this->lang->line('fantasy_football_points'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_starting_appearance'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('starting_appearance_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_substitute_appearance'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('substitute_appearance_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_clean_sheet_by_defender'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('clean_sheet_by_defender_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_clean_sheet_by_midfielder'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('clean_sheet_by_midfielder_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_assist_by_goalkeeper'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('assist_by_goalkeeper_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_assist_by_defender'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('assist_by_defender_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_assist_by_midfielder'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('assist_by_midfielder_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_assist_by_striker'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('assist_by_striker_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_goal_by_goalkeeper'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('goal_by_goalkeeper_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_goal_by_defender'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('goal_by_defender_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_goal_by_midfielder'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('goal_by_midfielder_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_goal_by_striker'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('goal_by_striker_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_man_of_the_match'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('man_of_the_match_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_yellow_card'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('yellow_card_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_red_card'); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('red_card_points'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <h3><?php echo $this->lang->line("fantasy_football_best_lineup"); ?></h3>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
<?php
$inputMeasurement = array(
    'name'    => 'measurement',
    'id'      => 'measurement',
    'options' => $this->Fantasy_Football_model->fetchMeasurementForDropdown(),
    'value'   => set_value('measurement', $measurement),
);

$inputFormation = array(
    'name'    => 'formation',
    'id'      => 'formation',
    'options' => $this->Fantasy_Football_model->fetchFormationsForDropdown(),
    'value'   => set_value('formation', $formation),
); ?>
                    <div class="span4">
<?php
echo form_label($this->lang->line('fantasy_football_measurement'), $inputMeasurement['name']);
echo form_dropdown($inputMeasurement['name'], $inputMeasurement['options'], $inputMeasurement['value']); ?>
                    </div>
                    <div class="span4">
<?php
echo form_label($this->lang->line('fantasy_football_formation'), $inputFormation['name']);
echo form_dropdown($inputFormation['name'], $inputFormation['options'], $inputFormation['value']); ?>
                    </div>
                    <div class="span4">
<?php
echo form_submit('submit', $this->lang->line('fantasy_football_show')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span7">
                <div id="stadium">
                    <div id="pitch" class="formation-<?php echo $formation; ?>">
                    <?php
                    if ($bestLineup !== false) {
                        foreach ($formationInfo['positions'] as $position) {
                            if (strpos($position, 'sub') === false) { ?>
                        <div class="position <?php echo $position; ?>">
                            <span class="marker"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></span>
                            <span class="player"><?php echo Player_helper::initialSurname($bestLineup[$position], false); ?></span>
                            <span class="points">(<?php echo rand(5, 200); ?> pts)</span>
                        </div>
                    <?php
                            }
                        } ?>
                    </div>
                    <div id="dugout">
                    <?php
                        foreach ($formationInfo['positions'] as $position) {
                            if (strpos($position, 'sub') !== false) { ?>
                        <div class="position <?php echo Fantasy_Football_helper::fetchSimplePosition($position); ?>">
                            <span class="marker"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></span>
                            <span class="player"><?php echo Player_helper::initialSurname($bestLineup[$position], false); ?></span>
                            <span class="points">(<?php echo rand(5, 200); ?> pts)</span>
                        </div>
                    <?php
                            }
                        }
                    } ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>

            <div class="span5">
                <table class="width-100-percent">
                    <thead>
                        <tr>
                            <td><?php echo $this->lang->line('fantasy_football_player'); ?></td>
                            <td><?php echo $this->lang->line('fantasy_football_position'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($bestLineup !== false) {
                            foreach ($formationInfo['positions'] as $position) { ?>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($bestLineup[$position]); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_position'); ?>"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></td>
                        </tr>
                        <?php
                            }
                        } else { ?>
                        <tr>
                            <td colspan="2"><?php echo $this->lang->line('fantasy_football_no_data'); ?></td>
                        </tr>
                        <?php
                        }  ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <?php
    echo form_close(); ?>
</div>