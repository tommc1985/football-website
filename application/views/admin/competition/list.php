    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('competition_short_name'); ?></td>
                <td><?php echo $this->lang->line('competition_type'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($competitions) > 0) {
        foreach ($competitions as $competition) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('competition_short_name'); ?>"><?php echo Competition_helper::shortName($competition); ?></td>
                <td data-title="<?php echo $this->lang->line('competition_type'); ?>"><?php echo Competition_helper::type($competition); ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/competition/edit/id/{$competition->id}"); ?>"><?php echo $this->lang->line('competition_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/competition/delete/id/{$competition->id}"); ?>"><?php echo $this->lang->line('competition_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo $this->lang->line('competition_no_competitions'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>

    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>