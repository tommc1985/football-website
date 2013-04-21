<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('player_first_name'); ?></td>
                <td><?php echo $this->lang->line('player_surname'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($players) > 0) {
        foreach ($players as $player) { ?>
            <tr>
                <td><?php echo $player->first_name; ?></td>
                <td><?php echo $player->surname; ?></td>
                <td><a href="/admin/player/edit/id/<?php echo $player->id;?>"><?php echo $this->lang->line('player_edit'); ?></a></td>
                <td><a href="/admin/player/delete/id/<?php echo $player->id;?>"><?php echo $this->lang->line('player_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo $this->lang->line('player_no_players'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>