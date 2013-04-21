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
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($leagueMatches) > 0) {
        foreach ($leagueMatches as $leagueMatch) { ?>
            <tr>
                <td><?php echo $leagueMatch->date; ?></td>
                <td><?php echo $leagueMatch->h_opposition_id; ?></td>
                <td><?php echo $leagueMatch->h_score; ?> - <?php echo $leagueMatch->a_score; ?></td>
                <td><?php echo $leagueMatch->a_opposition_id; ?></td>
                <td><a href="/admin/league-match/edit/id/<?php echo $leagueMatch->id;?>"><?php echo $this->lang->line('league_match_edit'); ?></a></td>
                <td><a href="/admin/league-match/delete/id/<?php echo $leagueMatch->id;?>"><?php echo $this->lang->line('league_match_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="6"><?php echo $this->lang->line('league_match_no_league_matches'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>