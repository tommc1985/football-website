<?php
if (count($playerAwards) > 0) { ?>
   <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('player_to_award_player'); ?></td>
                <td><?php echo $this->lang->line('player_to_award_award'); ?></td>
                <td><?php echo $this->lang->line('player_to_award_season'); ?></td>
                <td><?php echo $this->lang->line('player_to_award_placing'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($playerAwards as $playerAward) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('player_to_award_player'); ?>" class="width-30-percent"><?php echo Player_helper::fullNameReverse($playerAward->player_id, false); ?></td>
                <td data-title="<?php echo $this->lang->line('player_to_award_award'); ?>" class="width-30-percent"><?php echo Award_helper::shortName($playerAward->award_id); ?></td>
                <td data-title="<?php echo $this->lang->line('player_to_award_season'); ?>" class="width-15-percent text-align-center"><?php echo Utility_helper::formattedSeason($playerAward->season); ?></td>
                <td data-title="<?php echo $this->lang->line('player_to_award_placing'); ?>" class="width-10-percent text-align-center"><?php echo Utility_helper::ordinalWithSuffix($playerAward->placing); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/player-award/edit/id/{$playerAward->id}"); ?>"><?php echo $this->lang->line('player_to_award_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/player-award/delete/id/{$playerAward->id}"); ?>"><?php echo $this->lang->line('player_to_award_delete'); ?></a>
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
        <?php echo $this->lang->line('player_to_award_no_player_awards'); ?>
    </div>
<?php
} ?>