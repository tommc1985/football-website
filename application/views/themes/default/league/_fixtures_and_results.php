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