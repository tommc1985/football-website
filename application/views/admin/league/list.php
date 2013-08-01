<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('league_name'); ?></td>
                <td><?php echo $this->lang->line('league_season'); ?></td>
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
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/league/edit/id/{$league->id}"); ?>"><?php echo $this->lang->line('league_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/league/delete/id/{$league->id}"); ?>"><?php echo $this->lang->line('league_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo $this->lang->line('league_no_leagues'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>