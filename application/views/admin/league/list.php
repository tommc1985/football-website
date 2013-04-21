<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('league_name'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($leagues) > 0) {
        foreach ($leagues as $league) { ?>
            <tr>
                <td><?php echo $league->short_name; ?></td>
                <td><a href="/admin/league/edit/id/<?php echo $league->id;?>"><?php echo $this->lang->line('league_edit'); ?></a></td>
                <td><a href="/admin/league/delete/id/<?php echo $league->id;?>"><?php echo $this->lang->line('league_delete'); ?></a></td>
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