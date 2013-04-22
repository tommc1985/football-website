<h2><?php echo $this->lang->line('article_title'); ?></h2>

<h3><?php echo $article->title; ?></h3>
<p><?php echo nl2br($article->content); ?></p>
<p><?php echo $this->lang->line('article_published_on'); ?> <?php echo Utility_helper::longDateTime($article->publish_date); ?></p>