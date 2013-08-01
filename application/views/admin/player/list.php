<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('player_name'); ?></td>
                <td><?php echo $this->lang->line('player_dob'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($players) > 0) {
        foreach ($players as $player) { ?>
            <tr>
                <td><?php echo Player_helper::fullNameReverse($player, false); ?></td>
                <td><?php echo Utility_helper::shortDate($player->dob); ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/player/edit/id/{$player->id}"); ?>"><?php echo $this->lang->line('player_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/player/delete/id/{$player->id}"); ?>"><?php echo $this->lang->line('player_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo $this->lang->line('player_no_players'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>