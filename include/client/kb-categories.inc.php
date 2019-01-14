<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-default">
                <div class="box-body">
                    <?php
                    $categories = Category::objects()
                        ->exclude(Q::any(array(
                        'ispublic'=>Category::VISIBILITY_PRIVATE,
                        'faqs__ispublished'=>FAQ::VISIBILITY_PRIVATE,
                        )))
                        ->annotate(array('faq_count'=>SqlAggregate::COUNT('faqs')))
                        ->filter(array('faq_count__gt'=>0));
                    if ($categories->exists(true)) { ?>
                        <div><?php echo __('Click on the category to browse FAQs.'); ?></div>
                        <br/>
                        <ul id="kb">
                            <?php
                            foreach ($categories as $C) { ?>
                                <div class="media">
                                    <div class="media-left">
                                        <img src="<?php echo ASSETS_PATH; ?>images/kb_large_folder.png" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <div class="clearfix">
                                            <h4 style="margin-top: 0"><?php echo sprintf('<a href="faq.php?cid=%d">%s (%d)</a>',
                                                $C->getId(), Format::htmlchars($C->getLocalName()), $C->faq_count); ?></h4>
                                            <?php echo Format::safe_html($C->getLocalDescriptionWithImages()); ?>
                                            <?php       foreach ($C->faqs
                                                ->exclude(array('ispublished'=>FAQ::VISIBILITY_PRIVATE))
                                                ->limit(5) as $F) { ?>
                                                    <div class="popular-faq"><i class="icon-file-alt"></i>
                                                        <a href="faq.php?id=<?php echo $F->getId(); ?>">
                                                        <?php echo $F->getLocalQuestion() ?: $F->getQuestion(); ?>
                                                    </a></div>
                                            <?php       } ?>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                            <?php   } ?>
                        </ul>
                    <?php
                    } else {
                        echo __('NO FAQs found');
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
                    <div class="form-group">
                        <select class="form-control" name="topicId"  style="width:100%;max-width:100%"
                        onchange="javascript:this.form.submit();">
                        <option value="">—<?php echo __("Browse by Topic"); ?>—</option>
                        <?php
                        $topics = Topic::objects()
                            ->annotate(array('has_faqs'=>SqlAggregate::COUNT('faqs')))
                            ->filter(array('has_faqs__gt'=>0));
                        foreach ($topics as $T) { ?>
                            <option value="<?php echo $T->getId(); ?>"><?php echo $T->getFullName();
                            ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    </form>
                    <br/>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                            <?php echo __('Other Resources'); ?>
                            </h4>
                        </div>
                        <div class="box-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
