<?php
$info = $_POST;
if (!isset($info['timezone']))
    $info += array(
        'backend' => null,
    );
if (isset($user) && $user instanceof ClientCreateRequest) {
    $bk = $user->getBackend();
    $info = array_merge($info, array(
        'backend' => $bk::$id,
        'username' => $user->getUsername(),
    ));
}
$info = Format::htmlchars(($errors && $_POST)?$_POST:$info);

?>
<section class="content">
    <form action="account.php" method="post">
    <?php csrf_token(); ?>
    <div class="row">
        <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title"><?php echo __('Account Registration'); ?>
            <small><?php echo __(
            'Use the forms below to create or update the information we have on file for your account'
        ); ?></small></h2>
        </div>
        <div class="box-body">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="box box-primary">
                    <input type="hidden" name="do" value="<?php echo Format::htmlchars($_REQUEST['do']
                        ?: ($info['backend'] ? 'import' :'create')); ?>" />
                    <?php
                    $cf = $user_form ?: UserForm::getInstance();
                    $cf->render(false, false, array('mode' => 'create'));
                    ?>
            </div>
        </div>
        <!-- Right Column -->
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo __('Preferences'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label><?php echo __('Time Zone');?>:</label>
                        <?php
                        $TZ_NAME = 'timezone';
                        $TZ_TIMEZONE = $info['timezone'];
                        include INCLUDE_DIR.'staff/templates/timezone.tmpl.php'; ?>
                        <div class="error"><?php echo $errors['timezone']; ?></div>
                    </div>
                </div>
            </div>
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo __('Access Credentials'); ?></h3>
                </div>
                <div class="box-body">
                    <?php if ($info['backend']) { ?>
                    <div class="form-group">
                        <label ><?php echo __('Login With'); ?>:</label>
                        <input type="hidden" name="backend" value="<?php echo $info['backend']; ?>"/>
                        <input type="hidden" name="username" value="<?php echo $info['username']; ?>"/>
                        <?php foreach (UserAuthenticationBackend::allRegistered() as $bk) {
                            if ($bk::$id == $info['backend']) {
                                echo $bk->getName();
                                break;
                            }
                        } ?>
                    </div>
                    <?php } else { ?>
                    <div class="form-group">
                        <label><?php echo __('Create a Password'); ?>:</label>
                        <input class="form-control" type="password" size="18" name="passwd1" value="<?php echo $info['passwd1']; ?>">
                        &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd1']; ?></span>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Confirm New Password'); ?>:</label>
                        <input class="form-control" type="password" size="18" name="passwd2" value="<?php echo $info['passwd2']; ?>">
                        &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd2']; ?></span>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- Central Column -->
        <div class="col-md-12">
            <div class="row">
            <div class="col-md-4">
                <input class="btn btn-info" type="submit" value="Register"/>
                <input class="btn btn-default pull-right" type="button" value="Cancel" onclick="javascript:
                window.location.href='index.php';"/>
            </div>
            </div>
        </div>
        </div>
        </div>
    </div>
    </form>
</section>
<?php if (!isset($info['timezone'])) { ?>
<!-- Auto detect client's timezone where possible -->
<script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jstz.min.js?035fd0a"></script>
<script type="text/javascript">
$(function() {
    var zone = jstz.determine();
    $('#timezone-dropdown').val(zone.name()).trigger('change');
});
</script>
<?php }
