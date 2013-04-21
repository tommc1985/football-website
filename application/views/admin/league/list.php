<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('league_name'); ?></td>
                <td><?php echo $this->lang->line('league_season'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($leagues) > 0) {
        foreach ($leagues as $league) { ?>
            <tr>
                <td><?php echo League_helper::shortName($league); ?></td>
                <td><?php echo Utility_helper::formattedSeason($league->season); ?></td>
                <td><a href="/admin/league/edit/id/<?php echo $league->id;?>"><?php echo $this->lang->line('league_edit'); ?></a></td>
                <td><a href="/admin/league/delete/id/<?php echo $league->id;?>"><?php echo $this->lang->line('league_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo $this->lang->line('league_no_leagues'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>