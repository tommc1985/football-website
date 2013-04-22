<h2><?php echo $this->lang->line('news_title'); ?></h2>

<?php
if ($articles) {
    foreach ($articles as $article) { ?>
    <h3><?php echo $article->title; ?> (<?php echo Utility_helper::shortDate($article->publish_date); ?>)</h3>
    <p><?php echo Utility_helper::partialContent($article->content, 250, " "); ?> (<a href="/news/view/id/<?php echo $article->id; ?>">read more</a>)</p>
<?php
    }
} else { ?>
    <?php echo $this->lang->line('news_no_news_found'); ?></td>
<?php
} ?>