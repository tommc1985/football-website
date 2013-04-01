<?php
echo $pagination;
if (count($leagueRegistrations) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('league_registration_league'); ?></td>
                <td><?php echo $this->lang->line('league_registration_team'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($leagueRegistrations as $leagueRegistration) { ?>
            <tr>
                <td><?php echo $leagueRegistration->league_id; ?></td>
                <td><?php echo $leagueRegistration->opposition_id; ?></td>
                <td><a href="/admin/league-registration/edit/id/<?php echo $leagueRegistration->id;?>"><?php echo $this->lang->line('league_registration_edit'); ?></a></td>
                <td><a href="/admin/league-registration/delete/id/<?php echo $leagueRegistration->id;?>"><?php echo $this->lang->line('league_registration_delete'); ?></a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>