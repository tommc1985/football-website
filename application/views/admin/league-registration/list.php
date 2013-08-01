<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('league_registration_league'); ?></td>
                <td><?php echo $this->lang->line('league_registration_team'); ?></td>
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
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/league-registration/edit/id/{$leagueRegistration->id}"); ?>"><?php echo $this->lang->line('league_registration_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/league-registration/delete/id/{$leagueRegistration->id}"); ?>"><?php echo $this->lang->line('league_registration_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo $this->lang->line('league_registration_no_league_registrations'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>