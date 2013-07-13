<div class="row-fluid">
    <div class="span12">
        <h2><?php echo sprintf($this->lang->line('match_fixtures_and_results_for_season'), Utility_helper::formattedSeason($season)); ?></h2>

        <table class="no-more-tables">
            <thead>
                <tr>
                    <td><?php echo $this->lang->line('match_date'); ?></td>
                    <td><?php echo $this->lang->line('match_opposition'); ?></td>
                    <td><?php echo $this->lang->line('match_competition'); ?></td>
                    <td><?php echo $this->lang->line('match_venue'); ?></td>
                    <td><?php echo $this->lang->line('match_score'); ?></td>
                </tr>
            </thead>
        <?php
        if ($matches) { ?>
            <tbody>
        <?php
            foreach ($matches as $match) { ?>
                <tr itemscope itemtype="http://schema.org/SportsEvent">
                    <td data-title="<?php echo $this->lang->line('match_date'); ?>"><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($match->date, "c"); ?>"><?php echo $match->date ? Utility_helper::formattedDate($match->date, "jS M 'y") : $this->lang->line('match_t_b_c'); ?></time></td>
                    <td itemprop="name" itemscope itemtype="http://schema.org/SportsTeam" data-title="<?php echo $this->lang->line('match_opposition'); ?>"><span itemprop="name" itemprop="legalName"><?php echo Opposition_helper::name($match->opposition_id); ?></span></td>
                    <td data-title="<?php echo $this->lang->line('match_competition'); ?>"><?php echo Match_helper::fullCompetitionNameCombined($match); ?></td>
                    <td data-title="<?php echo $this->lang->line('match_venue'); ?>"><?php echo Match_helper::longVenue($match); ?></td>
                    <td data-title="<?php echo $this->lang->line('match_score'); ?>"><a itemprop="url" href="<?php echo site_url('/match/view/id/' . $match->id); ?>"><?php echo Match_helper::score($match); ?></a></td>
                </tr>
        <?php
            }
        } else { ?>
                <tr>
                    <td colspan="5"><?php echo sprintf($this->lang->line('match_no_matches_found'), Utility_helper::formattedSeason($season)); ?></td>
                </td>
        <?php
        } ?>
            </tbody>
        </table>
    </div>
</div>