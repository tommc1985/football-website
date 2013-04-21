<?php
echo $pagination;
if (count($matches) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('match_date'); ?></td>
                <td><?php echo $this->lang->line('match_opposition'); ?></td>
                <td><?php echo $this->lang->line('match_competition'); ?></td>
                <td><?php echo $this->lang->line('match_score'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($matches as $match) { ?>
            <tr>
                <td><?php echo Utility_helper::shortDate($match->date); ?></td>
                <td><?php echo Opposition_helper::name($match->opposition_id); ?> (<?php echo Match_helper::venue($match); ?>)</td>
                <td><?php echo Competition_helper::shortName($match->competition_id); ?></td>
                <td><?php echo Match_helper::score($match); ?></td>
                <td><a href="/admin/match/edit/id/<?php echo $match->id;?>"><?php echo $this->lang->line('match_edit'); ?></a></td>
                <td><a href="/admin/match/delete/id/<?php echo $match->id;?>"><?php echo $this->lang->line('match_delete'); ?></a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>