<?php
if (count($leagues) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('league_name'); ?></td>
                <td><?php echo $this->lang->line('league_season'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($leagues as $league) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('league_name'); ?>" class="width-65-percent"><?php echo League_helper::shortName($league); ?></td>
                <td data-title="<?php echo $this->lang->line('league_season'); ?>" class="width-20-percent text-align-center"><?php echo Utility_helper::formattedSeason($league->season); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/league/edit/id/{$league->id}"); ?>"><?php echo $this->lang->line('league_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/league/delete/id/{$league->id}"); ?>"><?php echo $this->lang->line('league_delete'); ?></a>
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
        <?php echo $this->lang->line('league_no_leagues'); ?>
    </div>
<?php
} ?>