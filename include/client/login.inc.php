<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');

$email=Format::input($_POST['luser']?:$_GET['e']);
$passwd=Format::input($_POST['lpasswd']?:$_GET['t']);

$content = Page::lookupByType('banner-client');

if ($content) {
    list($title, $body) = $ost->replaceTemplateVariables(
        array($content->getName(), $content->getBody()));
} else {
    $title = __('Sign In');
    $body = __('To better serve you, we encourage our clients to register for an account and verify the email address we have on record.');
}

?>
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title"><?php echo Format::display($title); ?>
            <small><?php echo Format::display($body); ?></small></h2>
        </div>
        <div class="box-body">
            <form action="login.php" method="post" id="clientLogin">
            <?php csrf_token(); ?>
            <div class="row">
                <div class="col-md-6 form-horizontal">
                    <div class="box-body">
                        <strong><?php echo Format::htmlchars($errors['login']); ?></strong>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <input id="username" placeholder="<?php echo __('Email or Username'); ?>" type="text" name="luser" size="30" value="<?php echo $email; ?>" class="nowarn form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <input id="passwd" placeholder="<?php echo __('Password'); ?>" type="password" name="lpasswd" size="30" value="<?php echo $passwd; ?>" class="nowarn form-control">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input class="btn btn-info" type="submit" value="<?php echo __('Sign In'); ?>">
                        <?php if ($suggest_pwreset) { ?>
                            <a class="pull-right" href="pwreset.php"><?php echo __('Forgot My Password'); ?></a>
                        <?php } ?>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <div class="col-md-6">
                    <?php if ($cfg && $cfg->isClientRegistrationEnabled()) {
                        if (count($ext_bks)) echo '<hr style="width:70%"/>'; ?>
                        <div style="margin-bottom: 5px">
                            <?php echo __('Not yet registered?'); ?> <a href="account.php?do=create"><?php echo __('Create an account'); ?></a>
                        </div>
                    <?php } ?>
                    <div>
                        <b><?php echo __("I'm an agent"); ?></b> —
                        <a href="<?php echo ROOT_PATH; ?>scp/"><?php echo __('sign in here'); ?></a>
                    </div>            
                </div>
                <div class="col-md-6">
                    <?php
                        $ext_bks = array();
                        foreach (UserAuthenticationBackend::allRegistered() as $bk)
                            if ($bk instanceof ExternalAuthentication)
                                $ext_bks[] = $bk;
                        if (count($ext_bks)) {
                            foreach ($ext_bks as $bk) { ?>
                                <div class="external-auth"><?php $bk->renderExternalLink(); ?></div><?php
                            }
                        } ?>
                </div>
            </div>
            </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?php
            if ($cfg->getClientRegistrationMode() != 'disabled'
                || !$cfg->isClientLoginRequired()) {
                echo sprintf(__('If this is your first time contacting us or you\'ve lost the ticket number, please %s open a new ticket %s'),
                '<a href="open.php">', '</a>');
            } ?>
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->
</section>
