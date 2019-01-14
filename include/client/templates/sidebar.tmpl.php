<?php if($sdshow) { ?>
    <div class="col-md-4">
        <div class="box box-primary"><?php
            if ($faqs->all()) { ?>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <?php echo __('Featured Questions'); ?>
                        </h4>
                    </div>
                    <div class="box-body">
                        <?php   foreach ($faqs as $F) { ?>
                            <div><a href="<?php echo ROOT_PATH; ?>kb/faq.php?id=<?php
                            echo urlencode($F->getId());
                            ?>"><?php echo $F->getLocalQuestion(); ?></a></div>
                        <?php } ?>
                    </div>
                </div>
            <?php
            }
            if ($resources->all()) { ?>
                <div class="panel box box-danger">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <?php echo __('Other Resources'); ?>
                        </h4>
                    </div>
                    <div class="box-body">
                        <?php   foreach ($resources as $page) { ?>
                            <div><a href="<?php echo ROOT_PATH; ?>pages/<?php echo $page->getNameAsSlug();
                            ?>"><?php echo $page->getLocalName(); ?></a></div>
                        <?php   } ?>
                    </div>
                </div>
            <?php
            }
        ?></div>
    </div>
<?php } ?>

