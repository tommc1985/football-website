<?php
echo $pagination; ?>
    <table class="no-more-tables">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('player_registration_player'); ?></td>
                <td><?php echo $this->lang->line('player_registration_season'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($playerRegistrations) > 0) {
        foreach ($playerRegistrations as $playerRegistration) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('player_registration_player'); ?>"><?php echo Player_helper::fullNameReverse($playerRegistration->player_id, false); ?></td>
                <td data-title="<?php echo $this->lang->line('player_registration_season'); ?>"><?php echo Utility_helper::formattedSeason($playerRegistration->season); ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/player-registration/edit/id/{$playerRegistration->id}"); ?>"><?php echo $this->lang->line('player_registration_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/player-registration/delete/id/{$playerRegistration->id}"); ?>"><?php echo $this->lang->line('player_registration_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo $this->lang->line('player_registration_no_player_registrations'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>