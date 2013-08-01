<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('news_title'); ?></h2>

        <div class="pagination">
        <?php
        echo $pagination; ?>
        </div>

        <?php
        if ($articles) { ?>
                <div class="row-fluid">
                <?php
                $i = 0;
                foreach ($articles as $article) { ?>
                <div class="span6" itemscope itemtype="http://schema.org/NewsArticle">
                    <h3 itemprop="headline"><?php echo $article->title; ?></h3>
                    <p><time itemprop="datePublished" datetime="<?php echo Utility_helper::formattedDate($article->publish_date, "c"); ?>"><?php echo Utility_helper::shortDate($article->publish_date); ?></time></p>
                    <p itemprop="articleSection"><?php echo Utility_helper::partialContent($article->content, 250, " "); ?> (<a href="<?php echo site_url("/news/view/id/{$article->id}"); ?>">read more</a>)</p>
                </div>
            <?php
                    if (($i % 2) == 1) { ?>
            </div>
            <div class="row-fluid">
                    <?php
                    }

                    $i++;
                }

                if (($i % 2) == 0) { ?>
            </div>
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