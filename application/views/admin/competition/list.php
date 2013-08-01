<?php
echo $pagination; ?>
    <table>
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
                <td><?php echo Competition_helper::shortName($competition); ?></td>
                <td><?php echo Competition_helper::type($competition); ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/competition/edit/id/{$competition->id}"); ?>"><?php echo $this->lang->line('competition_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/competition/delete/id/{$competition->id}"); ?>"><?php echo $this->lang->line('competition_delete'); ?></a>
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
<?php
echo $pagination; ?>