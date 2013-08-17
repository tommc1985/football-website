<div class="row-fluid">
    <div class="span12">
        <h1><?php echo $this->lang->line('index_title'); ?></h1>

            <div class="row-fluid section">
                <div class="span6">
                <?php Index_helper::latestNewsArticle($section['latestNewsArticle']); ?>
                </div>

                <div class="span6">
                <?php Index_helper::upcomingEvents($section['upcomingEvents']); ?>
                </div>
            </div>

            <div class="row-fluid section">
                <div class="span6">
                <?php Index_helper::upcomingFixtures($section['upcomingFixtures']); ?>
                </div>

                <div class="span6">
                <?php Index_helper::recentResults($section['recentResults']); ?>
                </div>
            </div>

            <div class="row-fluid section">
                <div class="span4">
                <?php Index_helper::topScorers($section['topScorers']); ?>
                </div>

                <div class="span4">
                <?php Index_helper::topAssisters($section['topAssisters']); ?>
                </div>

                <div class="span4">
                <?php Index_helper::onThisDay($section['onThisDay']); ?>
                </div>
            </div>

            <div class="row-fluid section">
                <div class="span4">
                <?php Index_helper::mostMotMs($section['mostMotMs']); ?>
                </div>

                <div class="span4">
                <?php Index_helper::fantasyFootballers($section['fantasyFootballers']); ?>
                </div>

                <div class="span4">
                <?php Index_helper::worstDiscipline($section['worstDiscipline']); ?>
                </div>
            </div>
    </div>
</div>