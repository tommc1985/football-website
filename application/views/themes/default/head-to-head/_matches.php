<h3><?php echo $this->lang->line('head_to_head_matches'); ?></h3>
<table class="no-more-tables width-100-percent table table-striped table-condensed">
    <thead>
        <tr>
            <td class="width-25-percent"><?php echo $this->lang->line("match_date"); ?></td>
            <td class="width-50-percent"><?php echo $this->lang->line("match_competition"); ?></td>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("match_venue"); ?></td>
            <td class="width-15-percent text-align-center"><?php echo $this->lang->line("match_score"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($matches) {
        foreach ($matches as $match) { ?>
            <tr itemscope itemtype="http://schema.org/SportsEvent">
                <td data-title="<?php echo $this->lang->line("match_date"); ?>"><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($match->date, "c"); ?>"><?php echo $match->date ? Utility_helper::formattedDate($match->date, "jS M 'y") : $this->lang->line('match_t_b_c'); ?></time></td>
                <td data-title="<?php echo $this->lang->line("match_competition"); ?>"><?php echo Match_helper::shortCompetitionNameCombined($match); ?></td>
                <td class="text-align-center" data-title="<?php echo $this->lang->line("match_venue"); ?>"><?php echo Match_helper::venue($match); ?></td>
                <td class="text-align-center" data-title="<?php echo $this->lang->line("match_score"); ?>"><a href="<?php echo site_url("match/view/id/{$match->id}"); ?>" title=""><?php echo Match_helper::score($match); ?></a></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo sprintf($this->lang->line('head_to_head_no_statistics'), Configuration::get('team_name'), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>