<?php
echo $pagination;
if (count($competitions) > 0) { ?>
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
    foreach ($competitions as $competition) { ?>
            <tr>
                <td><?php echo $competition->short_name; ?></td>
                <td><?php echo $competition->type; ?></td>
                <td><a href="/admin/competition/edit/id/<?php echo $competition->id;?>"><?php echo $this->lang->line('competition_edit'); ?></a></td>
                <td><a href="/admin/competition/delete/id/<?php echo $competition->id;?>"><?php echo $this->lang->line('competition_delete'); ?></a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>