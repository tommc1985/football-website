<?php
echo $pagination;
if (count($leagueMatches) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td>Date</td>
                <td>Home Team</td>
                <td>Score</td>
                <td>Away Team</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($leagueMatches as $leagueMatch) { ?>
            <tr>
                <td><?php echo $leagueMatch->date; ?></td>
                <td><?php echo $leagueMatch->h_opposition_id; ?></td>
                <td><?php echo $leagueMatch->h_score; ?> - <?php echo $leagueMatch->a_score; ?></td>
                <td><?php echo $leagueMatch->a_opposition_id; ?></td>
                <td><a href="/admin/league-match/edit/id/<?php echo $leagueMatch->id;?>">Edit</a></td>
                <td><a href="/admin/league-match/delete/id/<?php echo $leagueMatch->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>