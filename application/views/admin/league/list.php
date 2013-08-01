    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables">
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
                <td data-title="<?php echo $this->lang->line('league_name'); ?>"><?php echo League_helper::shortName($league); ?></td>
                <td data-title="<?php echo $this->lang->line('league_season'); ?>"><?php echo Utility_helper::formattedSeason($league->season); ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/league/edit/id/{$league->id}"); ?>"><?php echo $this->lang->line('league_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/league/delete/id/{$league->id}"); ?>"><?php echo $this->lang->line('league_delete'); ?></a>
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

    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>