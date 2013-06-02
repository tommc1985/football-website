<h2><?php echo sprintf($this->lang->line('match_fixtures_and_results_for_season'), Utility_helper::formattedSeason($season)); ?></h2>

<table class="no-more-tables">
    <thead>
        <tr>
            <td><?php echo $this->lang->line('match_date'); ?></td>
            <td><?php echo $this->lang->line('match_opposition'); ?></td>
            <td><?php echo $this->lang->line('match_competition'); ?></td>
            <td><?php echo $this->lang->line('match_venue'); ?></td>
            <td><?php echo $this->lang->line('match_score'); ?></td>
        </tr>
    </thead>
<?php
if ($matches) { ?>
    <tbody>
<?php
    foreach ($matches as $match) { ?>
        <tr>
            <td data-title="<?php echo $this->lang->line('match_date'); ?>"><?php echo Utility_helper::formattedDate($match->date, "jS M 'y"); ?></td>
            <td data-title="<?php echo $this->lang->line('match_opposition'); ?>"><?php echo Opposition_helper::name($match->opposition_id); ?></td>
            <td data-title="<?php echo $this->lang->line('match_competition'); ?>"><?php echo Match_helper::fullCompetitionNameCombined($match); ?></td>
            <td data-title="<?php echo $this->lang->line('match_venue'); ?>"><?php echo Match_helper::longVenue($match); ?></td>
            <td data-title="<?php echo $this->lang->line('match_score'); ?>"><?php echo anchor('/match/view/id/' . $match->id, Match_helper::score($match)); ?></td>
        </tr>
<?php
    }
} else { ?>
        <tr>
            <td colspan="5"><?php echo sprintf($this->lang->line('match_no_matches_found'), Utility_helper::formattedSeason($season)); ?></td>
        </td>
<?php
} ?>
    </tbody>
</table>