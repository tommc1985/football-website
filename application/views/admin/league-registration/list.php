<?php
echo $pagination; ?>
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
    if (count($leagueRegistrations) > 0) {
        foreach ($leagueRegistrations as $leagueRegistration) { ?>
            <tr>
                <td><?php echo League_helper::shortName($leagueRegistration->league_id); ?></td>
                <td><?php echo Opposition_helper::name($leagueRegistration->opposition_id); ?></td>
                <td><a href="/admin/league-registration/edit/id/<?php echo $leagueRegistration->id;?>"><?php echo $this->lang->line('league_registration_edit'); ?></a></td>
                <td><a href="/admin/league-registration/delete/id/<?php echo $leagueRegistration->id;?>"><?php echo $this->lang->line('league_registration_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo $this->lang->line('league_registration_no_league_registrations'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>