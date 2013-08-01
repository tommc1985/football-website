<?php
echo $pagination; ?>
    <table>
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
    if (count($leagueMatches) > 0) {
        foreach ($leagueMatches as $leagueMatch) { ?>
            <tr>
                <td><?php echo $leagueMatch->date; ?></td>
                <td><?php echo Opposition_helper::name($leagueMatch->h_opposition_id); ?></td>
                <td><?php echo League_Match_helper::score($leagueMatch); ?></td>
                <td><?php echo Opposition_helper::name($leagueMatch->a_opposition_id); ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/league-match/edit/id/{$leagueMatch->id}"); ?>"><?php echo $this->lang->line('league_match_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/league-match/delete/id/{$leagueMatch->id}"); ?>"><?php echo $this->lang->line('league_match_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="5"><?php echo $this->lang->line('league_match_no_league_matches'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>