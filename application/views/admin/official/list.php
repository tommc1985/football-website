<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('official_name'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($officials) > 0) {
        foreach ($officials as $official) { ?>
            <tr>
                <td><?php echo Official_helper::fullNameReverse($official); ?></td>
                <td><a href="/admin/official/edit/id/<?php echo $official->id;?>"><?php echo $this->lang->line('official_edit'); ?></a></td>
                <td><a href="/admin/official/delete/id/<?php echo $official->id;?>"><?php echo $this->lang->line('official_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo $this->lang->line('official_no_officials'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>