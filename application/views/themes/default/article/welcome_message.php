<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('article_title'); ?></h2>

        <div class="pagination">
        <?php
        echo $pagination; ?>
        </div>

        <?php
        if ($articles) {
            foreach ($articles as $article) { ?>
            <h3><?php echo $article->title; ?></h3>
            <p><?php echo Utility_helper::shortDate($article->publish_date); ?></p>
            <p><?php echo Utility_helper::partialContent($article->content, 250, " "); ?> (<a href="/article/view/id/<?php echo $article->id; ?>">read more</a>)</p>
        <?php
            }
        } else { ?>
            <?php echo $this->lang->line('article_no_article_found'); ?></td>
        <?php
        } ?>

        <div class="pagination">
        <?php
        echo $pagination; ?>
        </div>
    </div>
</div>