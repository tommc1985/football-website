<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('player_to_award_player'); ?></td>
                <td><?php echo $this->lang->line('player_to_award_award'); ?></td>
                <td><?php echo $this->lang->line('player_to_award_season'); ?></td>
                <td><?php echo $this->lang->line('player_to_award_placing'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($playerAwards) > 0) {
        foreach ($playerAwards as $playerAward) { ?>
            <tr>
                <td><?php echo Player_helper::fullNameReverse($playerAward->player_id, false); ?></td>
                <td><?php echo Award_helper::shortName($playerAward->award_id); ?></td>
                <td><?php echo Utility_helper::formattedSeason($playerAward->season); ?></td>
                <td><?php echo Utility_helper::ordinalWithSuffix($playerAward->placing); ?></td>
                <td><a href="/admin/player-award/edit/id/<?php echo $playerAward->id;?>"><?php echo $this->lang->line('player_to_award_edit'); ?></a></td>
                <td><a href="/admin/player-award/delete/id/<?php echo $playerAward->id;?>"><?php echo $this->lang->line('player_to_award_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo $this->lang->line('player_to_award_no_player_awards'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>