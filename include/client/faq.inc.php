<?php
if(!defined('OSTCLIENTINC') || !$faq  || !$faq->isPublished()) die('Access Denied');

$category=$faq->getCategory();

?>
<section class="content-header">
    <h1>
        <?php echo __('Frequently Asked Questions');?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i><?php echo __('All Categories');?></a></li>
        <li class="active"><a href="faq.php?cid=<?php echo $category->getId(); ?>"><?php echo $category->getName(); ?></a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-default">
                <div class="box-body">
                    <div class="post clearfix">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="<?php echo ASSETS_PATH; ?>images/check_status_icon.png" alt="question">
                            <span class="username">
                            <?php echo $faq->getLocalQuestion() ?>    
                            </span>
                            <span class="description"><?php echo sprintf(__('Last Updated %s'),
                            Format::relativeTime(Misc::db2gmtime($category->getUpdateDate()))); ?></span>
                        </div>
                        <!-- /.user-block -->
                        <?php echo $faq->getLocalAnswerWithImages(); ?>
                    </div>
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
                    <?php
                        if ($attachments = $faq->getLocalAttachments()->all()) { ?>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                <?php echo __('Attachments');?>:
                                </h4>
                            </div>
                            <div class="box-body">
                                <?php foreach ($attachments as $att) { ?>
                                <div>
                                    <a href="<?php echo $att->file->getDownloadUrl(); ?>" class="no-pjax">
                                    <i class="icon-file"></i>
                                    <?php echo Format::htmlchars($att->getFilename()); ?>
                                    </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php }
                        if ($faq->getHelpTopics()->count()) { ?>
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                <?php echo __('Help Topics'); ?>
                                </h4>
                            </div>
                            <div class="box-body">
                            <?php foreach ($faq->getHelpTopics() as $T) { ?>
                                <div><?php echo $T->topic->getFullName(); ?></div>
                            <?php } ?>
                            </div>
                        </div>
                        <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>