<?php
if ($dateUntil != 'overall') { ?>
    <h4><?php echo sprintf($formMatchCount == 1 ? $this->lang->line("league_last_x_match") : $this->lang->line("league_last_x_matches"), $formMatchCount, $this->lang->line("league_{$type}")) . ' ' . sprintf(strtolower($this->lang->line('league_as_of')), $dateUntil != 'overall' ? Utility_helper::shortDate($dateUntil) : Utility_helper::shortDate(time())); ?></h4>
<?php
} else { ?>
    <h4><?php echo sprintf($formMatchCount == 1 ? $this->lang->line("league_last_x_match") : $this->lang->line("league_last_x_matches"), $formMatchCount, $this->lang->line("league_{$type}")); ?></h4>
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