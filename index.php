<?php
/*********************************************************************
    index.php

    Helpdesk landing page. Please customize it to fit your needs.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('client.inc.php');

require_once INCLUDE_DIR . 'class.page.php';

$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>
<!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
    <?php
    $sdshow=false;
    $faqs = FAQ::getFeatured()->select_related('category')->limit(5);
    $resources = Page::getActivePages()->filter(array('type'=>'other'));
    if ($faqs->all() || $resources->all()) {
        $sdshow = true;
    } ?>
    <div class="row">
        <?php if($sdshow) {?>
            <div class="col-md-8">
        <?php } else { ?>
            <div class="col-md-12">
        <?php } ?>
        <div class="box box-default">
            <div class="box-body">
                <?php
                if ($cfg && $cfg->isKnowledgebaseEnabled()) { ?>
                <form method="get" action="kb/faq.php">
                    <div class="input-group">
                        <input type="hidden" name="a" value="search"/>
                        <input type="text" name="q" class="form-control" placeholder="<?php echo __('Search our knowledge base'); ?>">
                        <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-flat" data-toggle="modal" data-target="#myload"><i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
                <?php
                }
                if($cfg && ($page = $cfg->getLandingPage()))
                    echo $page->getBodyWithImages();
                else
                    echo  '<h1>'.__('Welcome to the Support Center').'</h1>';
                ?>
                <?php
                $BUTTONS = isset($BUTTONS) ? $BUTTONS : true;
                ?>
                <?php if ($BUTTONS) { ?>
                <div class="row">
                <?php
                if ($cfg->getClientRegistrationMode() != 'disabled'
                    || !$cfg->isClientLoginRequired()) { ?>
                    <div class="col-lg-6 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php echo __('Open');?></h3>

                            <p><?php echo __('New Ticket'); ?></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-paper-plane-o "></i>
                        </div>
                        <a href="open.php" class="small-box-footer">
                        <?php
                        echo __('Open a New Ticket');?> <i class="fa fa-arrow-circle-right"></i>
                        </a>
                        </div>
                    </div>
                <?php } ?>
                    <div class="col-lg-6 col-xs-6">
                    <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?php echo __('Check');?></h3>

                                <p><?php echo __('Ticket Status');?></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-check-circle-o"></i>
                            </div>
                            <a href="view.php" class="small-box-footer">
                            <?php
                    echo __('Check Ticket Status');?> <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        </div>
        <?php include CLIENTINC_DIR.'templates/sidebar.tmpl.php'; ?>
        <?php
        if($cfg && $cfg->isKnowledgebaseEnabled()){
            //FIXME: provide ability to feature or select random FAQs ??
            ?>
                <?php if($sdshow) {?>
                <div class="col-md-8">
                <?php } else { ?>
                <div class="col-md-12">
                <?php } ?>
                    <div class="box box-info">
                    <?php
                    $cats = Category::getFeatured();
                    if ($cats->all()) { ?>
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo __('Featured Knowledge Base Articles'); ?></h3>
                        </div>
                        <?php
                    } ?>
                        <div class="box-body">
                    <?php foreach ($cats as $C) { ?>
                            <div class="box-group" id="accordion">
                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $C->getName(); ?>">
                                        <i class="fa fa-folder-open-o"></i><?php echo $C->getName(); ?>
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="<?php echo $C->getName(); ?>" class="panel-collapse collapse">
                                        <div class="box-body">
                                            <?php foreach ($C->getTopArticles() as $F) { ?>
                                            <div class="article-headline">
                                                <div class="article-title"><a href="<?php echo ROOT_PATH;
                                                ?>kb/faq.php?id=<?php echo $F->getId(); ?>"><?php
                                                echo $F->getQuestion(); ?></a></div>
                                                <div class="article-teaser"><?php echo $F->getTeaser(); ?></div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                    } ?>
                        </div>
        <?php }
        ?>
    </div>
    </section>
    <!-- /.content -->
<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
