<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('news_title'); ?></h2>

        <div class="pagination">
        <?php
        echo $pagination; ?>
        </div>

        <?php
        if ($articles) {
            foreach ($articles as $article) { ?>
            <h3><?php echo $article->title; ?></h3>
            <p><?php echo Utility_helper::shortDate($article->publish_date); ?></p>
            <p><?php echo Utility_helper::partialContent($article->content, 250, " "); ?> (<a href="<?php echo site_url("/news/view/id/{$article->id}"); ?>">read more</a>)</p>
        <?php
            }
        } else { ?>
            <?php echo $this->lang->line('news_no_news_found'); ?></td>
        <?php
        } ?>

        <div class="pagination">
        <?php
        echo $pagination; ?>
        </div>
    </div>
</div>