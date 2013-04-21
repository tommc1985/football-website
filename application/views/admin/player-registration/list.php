<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('player_registration_player'); ?></td>
                <td><?php echo $this->lang->line('player_registration_season'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($playerRegistrations) > 0) {
        foreach ($playerRegistrations as $playerRegistration) { ?>
            <tr>
                <td><?php echo Player_helper::fullNameReverse($playerRegistration->player_id); ?></td>
                <td><?php echo Utility_helper::formattedSeason($playerRegistration->season); ?></td>
                <td><a href="/admin/player-registration/edit/id/<?php echo $playerRegistration->id;?>"><?php echo $this->lang->line('player_registration_edit'); ?></a></td>
                <td><a href="/admin/player-registration/delete/id/<?php echo $playerRegistration->id;?>"><?php echo $this->lang->line('player_registration_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo $this->lang->line('player_registration_no_player_registrations'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>