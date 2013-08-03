<?php
if (count($playerRegistrations) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('player_registration_player'); ?></td>
                <td><?php echo $this->lang->line('player_registration_season'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($playerRegistrations as $playerRegistration) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('player_registration_player'); ?>" class="width-70-percent"><?php echo Player_helper::fullNameReverse($playerRegistration->player_id, false); ?></td>
                <td data-title="<?php echo $this->lang->line('player_registration_season'); ?>" class="width-15-percent text-align-center"><?php echo Utility_helper::formattedSeason($playerRegistration->season); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-mini" href="<?php echo site_url("admin/player-registration/edit/id/{$playerRegistration->id}"); ?>"><?php echo $this->lang->line('player_registration_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/player-registration/delete/id/{$playerRegistration->id}"); ?>"><?php echo $this->lang->line('player_registration_delete'); ?></a>
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
        <?php echo $this->lang->line('player_registration_no_player_registrations'); ?>
    </div>
<?php
} ?>