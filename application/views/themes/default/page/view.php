<div class="row-fluid">
    <div class="span12" itemscope itemtype="http://schema.org/Article">
        <h2 itemprop="headline"><?php echo $article->title; ?></h2>

        <p itemprop="articleBody"><?php echo nl2br($article->content); ?></p>
    </div>
</div>