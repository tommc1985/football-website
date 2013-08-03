<?php
if (count($leagueMatches) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('league_match_date'); ?></td>
                <td><?php echo $this->lang->line('league_match_home_team'); ?></td>
                <td><?php echo $this->lang->line('league_match_score'); ?></td>
                <td><?php echo $this->lang->line('league_match_away_team'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($leagueMatches as $leagueMatch) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('league_match_date'); ?>" class="width-20-percent text-align-center"><?php echo $leagueMatch->date; ?></td>
                <td data-title="<?php echo $this->lang->line('league_match_home_team'); ?>" class="width-20-percent"><?php echo Opposition_helper::name($leagueMatch->h_opposition_id); ?></td>
                <td data-title="<?php echo $this->lang->line('league_match_score'); ?>" class="width-15-percent text-align-center"><?php echo League_Match_helper::score($leagueMatch); ?></td>
                <td data-title="<?php echo $this->lang->line('league_match_away_team'); ?>" class="width-20-percent text-align-right"><?php echo Opposition_helper::name($leagueMatch->a_opposition_id); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-mini" href="<?php echo site_url("admin/league-match/edit/id/{$leagueMatch->id}"); ?>"><?php echo $this->lang->line('league_match_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/league-match/delete/id/{$leagueMatch->id}"); ?>"><?php echo $this->lang->line('league_match_delete'); ?></a>
                    </div>
                </td>
            </tr>
        <?php
        } ?>
        </tbody>
    </table>

    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>
<?php
} else { ?>
    <div class="alert alert-error">
        <?php echo $this->lang->line('league_match_no_league_matches'); ?>
    </div>
<?php
} ?>