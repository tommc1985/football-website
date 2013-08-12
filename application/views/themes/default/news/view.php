<div class="row-fluid">
    <div class="span12" itemscope itemtype="http://schema.org/NewsArticle">
        <h2><?php echo $this->lang->line('news_title'); ?></h2>

        <h3 itemprop="headline"><?php echo $article->title; ?></h3>
        <p class="muted"><?php echo $this->lang->line('news_published_on'); ?> <time itemprop="datePublished" datetime="<?php echo Utility_helper::formattedDate($article->publish_date, "c"); ?>"><?php echo Utility_helper::longDateTime($article->publish_date); ?></time></p>
        <p itemprop="articleBody"><?php echo nl2br($article->content); ?></p>
    </div>
</div>