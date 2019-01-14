<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo __('Frequently Asked Questions');?> - <?php echo __('Search Results'); ?></h3>
                </div>
                <div class="box-body">
                    <?php
                    if ($faqs->exists(true)) {
                        echo '<div id="faq">'.sprintf(__('%d FAQs matched your search criteria.'),
                        $faqs->count())
                        .'<ol>';
                        foreach ($faqs as $F) {
                        echo sprintf(
                        '<li><a href="faq.php?id=%d" class="previewfaq">%s</a></li>',
                        $F->getId(), $F->getLocalQuestion(), $F->getVisibilityDescription());
                        }               
                        echo '</ol></div>';
                    } else {
                        echo '<strong class="muted">'.__('The search did not match any FAQs.').'</strong>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-body">
                    <form method="get" action="faq.php">
                        <input type="hidden" name="a" value="search"/>
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="<?php
                            echo __('Search our knowledge base'); ?>">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <br/>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                            <?php echo __('Help Topics'); ?>
                            </h4>
                        </div>
                        <div class="box-body">
                            <?php
                            foreach (Topic::objects()
                            ->annotate(array('faqs_count'=>SqlAggregate::count('faqs')))
                            ->filter(array('faqs_count__gt'=>0))
                            as $t) { ?>
                                <div><a href="?topicId=<?php echo urlencode($t->getId()); ?>"
                                ><?php echo $t->getFullName(); ?></a></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <?php echo __('Categories'); ?>
                            </h4>
                        </div>
                        <div class="box-body">
                            <?php
                            foreach (Category::objects()
                                ->annotate(array('faqs_count'=>SqlAggregate::count('faqs')))
                                ->filter(array('faqs_count__gt'=>0))
                                as $C) { ?>
                                <div><a href="?cid=<?php echo urlencode($C->getId()); ?>"
                                ><?php echo $C->getLocalName(); ?></a></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
