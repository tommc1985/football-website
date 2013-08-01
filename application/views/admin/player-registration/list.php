<?php
echo $pagination; ?>
    <table>
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
                <td><?php echo Player_helper::fullNameReverse($playerRegistration->player_id, false); ?></td>
                <td><?php echo Utility_helper::formattedSeason($playerRegistration->season); ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/player-registration/edit/id/{$playerRegistration->id}"); ?>"><?php echo $this->lang->line('player_registration_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/player-registration/delete/id/{$playerRegistration->id}"); ?>"><?php echo $this->lang->line('player_registration_delete'); ?></a>
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