<div class="row-fluid">
    <div class="span12">
<?php
echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
        <h2><?php echo $this->lang->line('league_title'); ?> - <?php echo League_helper::name($id); ?></h2><?php
$inputDateUntil = array(
    'name'       => 'date-until',
    'id'         => 'date-until',
    'options'    => array('overall' => 'Today') + $dropdownDates,
    'value'      => set_value('date-until', $dateUntil),
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('league_refresh'),
    'class'   => 'btn',
); ?>

<fieldset>
        <legend><?php echo $this->lang->line('global_filters');?></legend>
        <div class="control-group">
            <?php echo form_label($this->lang->line('league_include_date_upto'), $inputDateUntil['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo form_dropdown($inputDateUntil['name'], $inputDateUntil['options'], $inputDateUntil['value'], "id='{$inputDateUntil['id']}'"); ?>
                <?php
                echo form_submit($submit); ?>
            </div>
        </div>
</fieldset>

        <div class="row-fluid">
            <div class="span10 offset1">
                <h3><?php echo $this->lang->line('league_league_table'); ?></h3>
                <h4><?php echo sprintf($this->lang->line('league_as_of'), $dateUntil != 'overall' ? Utility_helper::shortDate($dateUntil) : Utility_helper::shortDate(time())); ?></h4>
                <?php
                if ($standings) { ?>
                <table class="no-more-tables width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-60-percent">&nbsp;</td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_p"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_w"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_d"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_l"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_f"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_a"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_gd"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_pts"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($standings as $standing) { ?>
                        <tr itemscope itemtype="http://schema.org/SportsTeam">
                            <td itemprop="name" data-title="<?php echo $this->lang->line('league_team'); ?>"><?php echo Opposition_helper::name($standing->opposition_id); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_played'); ?>"><?php echo $standing->played; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_won'); ?>"><?php echo $standing->won; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_drawn'); ?>"><?php echo $standing->drawn; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_lost'); ?>"><?php echo $standing->lost; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_for'); ?>"><?php echo $standing->gf; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_against'); ?>"><?php echo $standing->ga; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_goal_difference'); ?>"><?php echo $standing->gd; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_points'); ?>"><?php echo $standing->points; ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
                <?php
                } else { ?>
                <p><?php echo $this->lang->line("league_no_data"); ?></p>
                <?php
                } ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line("league_form"); ?></h3>
<?php
$inputFormMatchCount = array(
    'name'       => 'form-match-count',
    'id'         => 'form-match-count',
    'options'    => $this->League_Collated_Results_model->fetchMaxCountFormDropdown($id, $dateUntil),
    'value'      => set_value('form-match-count', $formMatchCount),
    'attributes' => 'class="input-mini"',
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('league_refresh'),
    'class'   => 'btn',
); ?>

<fieldset>
        <div class="control-group">
            <?php echo form_label($this->lang->line('league_form_over_the_last'), $inputFormMatchCount['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <div class="input-append">
                <?php echo form_dropdown($inputFormMatchCount['name'], $inputFormMatchCount['options'], $inputFormMatchCount['value'], "id='{$inputFormMatchCount['id']}' {$inputFormMatchCount['attributes']}"); ?>
  <span class="add-on"><?php echo $this->lang->line('league_matches'); ?></span>
                </div>
                <?php
                echo form_submit($submit); ?>
            </div>
        </div>
</fieldset>

                <?php
                if ($dateUntil != 'overall') { ?>
                    <h4><?php echo sprintf($formMatchCount == 1 ? $this->lang->line("league_last_x_match") : $this->lang->line("league_last_x_matches"), $formMatchCount) . ' ' . sprintf(strtolower($this->lang->line('league_as_of')), $dateUntil != 'overall' ? Utility_helper::shortDate($dateUntil) : Utility_helper::shortDate(time())); ?></h4>
                <?php
                } else { ?>
                    <h4><?php echo sprintf($formMatchCount == 1 ? $this->lang->line("league_last_x_match") : $this->lang->line("league_last_x_matches"), $formMatchCount); ?></h4>
                <?php
                } ?>
                <?php
                if ($formTeams) { ?>
                <table class="no-more-tables width-100-percent table table-striped table-condensed">
                    <tbody>
                    <?php
                    foreach ($formTeams as $opposition_id => $formTeam) { ?>
                        <tr itemscope itemtype="http://schema.org/SportsTeam">
                            <td class="width-70-percent" itemprop="name" data-title="<?php echo $this->lang->line('league_team'); ?>"><?php echo Opposition_helper::name($standings[$opposition_id]->opposition_id); ?></td>
                            <td class="width-70-percent" data-title="<?php echo $this->lang->line('league_form'); ?>"><?php echo League_helper::formattedForm($standings[$opposition_id]->form, $formMatchCount); ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
                <?php
                } else { ?>
                <p><?php echo $this->lang->line("league_no_data"); ?></p>
                <?php
                } ?>
            </div>
            <div class="span6">
                <h3><?php echo $this->lang->line("league_fixtures_and_results"); ?></h3>
<?php
$inputMatchDate = array(
    'name'       => 'match-date',
    'id'         => 'match-date',
    'options'    => $dropdownDates,
    'value'      => set_value('match-date', $matchDate),
    'attributes' => 'class="input-medium"',
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('league_refresh'),
    'class'   => 'btn',
); ?>

<fieldset>
        <div class="control-group">
            <?php echo form_label($this->lang->line('league_date'), $inputMatchDate['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo form_dropdown($inputMatchDate['name'], $inputMatchDate['options'], $inputMatchDate['value'], "id='{$inputMatchDate['id']}' {$inputMatchDate['attributes']}"); ?>
                <?php
                echo form_submit($submit); ?>
            </div>
        </div>
</fieldset>
                <h4><?php echo Utility_helper::shortDate($matchDate); ?></h4>
                <?php
                if ($leagueMatches) { ?>
                <table class="no-more-tables width-100-percent table table-striped table-condensed">
                    <tbody>
                    <?php
                    foreach ($leagueMatches as $leagueMatch) { ?>
                        <tr>
                            <td class="width-40-percent text-align-right" data-title="<?php echo $this->lang->line('league_match_home_team'); ?>"><?php echo Opposition_helper::name($leagueMatch->h_opposition_id); ?></td>
                            <td class="width-20-percent text-align-center" data-title="<?php echo $this->lang->line('league_match_score'); ?>"><?php echo League_Match_helper::score($leagueMatch); ?></td>
                            <td class="width-40-percent text-align-left" data-title="<?php echo $this->lang->line('league_match_away_team'); ?>"><?php echo Opposition_helper::name($leagueMatch->a_opposition_id); ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
                <?php
                } else { ?>
                <p><?php echo $this->lang->line("league_no_matches_on_date"); ?></p>
                <?php
                } ?>
            </div>
        </div>
<?php
echo form_close(); ?>
    </div>
</div>