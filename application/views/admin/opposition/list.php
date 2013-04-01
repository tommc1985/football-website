<?php
echo $pagination;
if (count($oppositions) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('opposition_name'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($oppositions as $opposition) { ?>
            <tr>
                <td><?php echo $opposition->name; ?></td>
                <td><a href="/admin/opposition/edit/id/<?php echo $opposition->id;?>"><?php echo $this->lang->line('opposition_edit'); ?></a></td>
                <td><a href="/admin/opposition/delete/id/<?php echo $opposition->id;?>"><?php echo $this->lang->line('opposition_delete'); ?></a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>