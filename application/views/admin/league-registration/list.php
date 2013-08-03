<?php
if (count($leagueRegistrations) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('league_registration_league'); ?></td>
                <td><?php echo $this->lang->line('league_registration_team'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($leagueRegistrations as $leagueRegistration) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('league_registration_league'); ?>" class="width-45-percent text-align-center"><?php echo League_helper::shortName($leagueRegistration->league_id); ?></td>
                <td data-title="<?php echo $this->lang->line('league_registration_team'); ?>" class="width-40-percent text-align-center"><?php echo Opposition_helper::name($leagueRegistration->opposition_id); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-mini" href="<?php echo site_url("admin/league-registration/edit/id/{$leagueRegistration->id}"); ?>"><?php echo $this->lang->line('league_registration_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/league-registration/delete/id/{$leagueRegistration->id}"); ?>"><?php echo $this->lang->line('league_registration_delete'); ?></a>
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
        <?php echo $this->lang->line('league_registration_no_league_registrations'); ?>
    </div>
<?php
} ?>