<?php
if(!defined('OSTCLIENTINC') || !$category || !$category->isPublic()) die('Access Denied');
?>
<section class="content-header">
    <h1>
        <?php echo __('Frequently Asked Questions');?>
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <?php echo $category->getLocalName() ?> - <?php echo Format::safe_html($category->getLocalDescriptionWithImages()); ?>
                    </h4>
                </div>
                <div class="box-body">
                    <?php
                    $faqs = FAQ::objects()
                        ->filter(array('category'=>$category))
                        ->exclude(array('ispublished'=>FAQ::VISIBILITY_PRIVATE))
                        ->annotate(array('has_attachments' => SqlAggregate::COUNT(SqlCase::N()
                        ->when(array('attachments__inline'=>0), 1)
                        ->otherwise(null)
                        )))
                        ->order_by('-ispublished', 'question');
                    if ($faqs->exists(true)) {
                    echo '<h2>'.__('Further Articles').'</h2>
                          <div id="faq">
                            <ol>';
                        foreach ($faqs as $F) {
                            $attachments=$F->has_attachments?'<span class="Icon file"></span>':'';
                            echo sprintf('
                                <li><a href="faq.php?id=%d" >%s &nbsp;%s</a></li>',
                                $F->getId(),Format::htmlchars($F->question), $attachments);
                        }
                        echo '  </ol>
                        </div>';
                    }else {
                        echo '<strong>'.__('This category does not have any FAQs.').' <a href="index.php">'.__('Back To Index').'</a></strong>';
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
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <?php echo __('Help Topics'); ?>
                            </h4>
                        </div>
                        <div class="box-body">
                            <?php
                            foreach (Topic::objects()
                                ->filter(array('faqs__faq__category__category_id'=>$category->getId()))
                            as $t) { ?>
                                <a href="?topicId=<?php echo urlencode($t->getId()); ?>"
                                ><?php echo $t->getFullName(); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
