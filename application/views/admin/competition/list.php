<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('competition_short_name'); ?></td>
                <td><?php echo $this->lang->line('competition_type'); ?></td>
                <td></td>
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
                <td><a href="/admin/competition/edit/id/<?php echo $competition->id;?>"><?php echo $this->lang->line('competition_edit'); ?></a></td>
                <td><a href="/admin/competition/delete/id/<?php echo $competition->id;?>"><?php echo $this->lang->line('competition_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo $this->lang->line('competition_no_competitions'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>