<?php
if (count($players) > 0) {?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('player_first_name'); ?></td>
                <td><?php echo $this->lang->line('player_surname'); ?></td>
                <td><?php echo $this->lang->line('player_dob'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($players as $player) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('player_first_name'); ?>" class="width-30-percent"><?php echo $player->first_name; ?></td>
                <td data-title="<?php echo $this->lang->line('player_surname'); ?>" class="width-30-percent"><?php echo $player->surname; ?></td>
                <td data-title="<?php echo $this->lang->line('player_dob'); ?>" class="width-25-percent text-align-center"><?php echo Utility_helper::shortDate($player->dob); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/player/edit/id/{$player->id}"); ?>"><?php echo $this->lang->line('player_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/player/delete/id/{$player->id}"); ?>"><?php echo $this->lang->line('player_delete'); ?></a>
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
        <?php echo $this->lang->line('player_no_players'); ?>
    </div>
<?php
} ?>