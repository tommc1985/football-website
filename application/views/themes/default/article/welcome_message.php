<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('article_title'); ?></h2>

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
                <div class="span6" itemscope itemtype="http://schema.org/Article">
                    <h3 itemprop="headline"><?php echo $article->title; ?></h3>
                    <p class="muted"><time itemprop="datePublished" datetime="<?php echo Utility_helper::formattedDate($article->publish_date, "c"); ?>"><?php echo Utility_helper::shortDate($article->publish_date); ?></time></p>
                    <p itemprop="articleSection"><?php echo Utility_helper::partialContent($article->content, 250, " "); ?> (<a href="<?php echo site_url("article/view/id/{$article->id}"); ?>">read more</a>)</p>
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
            <?php echo $this->lang->line('article_no_article_found'); ?></td>
        <?php
        } ?>

        <div class="pagination">
        <?php
        echo $pagination; ?>
        </div>
    </div>
</div>