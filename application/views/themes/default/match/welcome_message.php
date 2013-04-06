<h2><?php echo sprintf($this->lang->line('match_fixtures_and_results_for_season'), Utility_helper::formattedSeason($season)); ?></h2>

<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line('match_date'); ?></td>
            <td><?php echo $this->lang->line('match_opposition'); ?></td>
            <td><?php echo $this->lang->line('match_competition'); ?></td>
            <td><?php echo $this->lang->line('match_h_a'); ?></td>
            <td><?php echo $this->lang->line('match_score'); ?></td>
        </tr>
    </thead>
<?php
if ($matches) { ?>
    <tbody>
<?php
    foreach ($matches as $match) { ?>
        <tr>
            <td><?php echo Utility_helper::formattedDate($match->date, "jS M 'y"); ?></td>
            <td><?php echo Opposition_helper::name($match->opposition_id); ?></td>
            <td><?php echo Match_helper::fullCompetitionNameCombined($match); ?></td>
            <td><?php echo Match_helper::venue($match); ?></td>
            <td><?php echo anchor('/match/view/id/' . $match->id, Match_helper::longScore($match)); ?></td>
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