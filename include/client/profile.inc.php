<section class="content">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo __('Manage Your Profile Information'); ?>
            <small> <?php echo __('Use the forms below to update the information we have on file for your account'); ?>
            </small></h3>  
        </div>
        <form action="profile.php" method="post">
            <?php csrf_token(); ?>
        <div class="box-body">
            <?php
            foreach ($user->getForms() as $f) { ?>
                <div class="box box-primary">
                    <?php $f->render(false); ?>
                </div>
            <?php }
            if ($acct = $thisclient->getAccount()) {
                $info=$acct->getInfo();
                $info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
            ?>
            <div class="box box-primary">
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
                    <?php if ($cfg->getSecondaryLanguages()) { ?>
                    <div class="row">
                        <div class="col-md-4">
                        <div class="form-group">
                            <label><?php echo __('Preferred Language'); ?>:</label>
                            <?php
                            $langs = Internationalization::getConfiguredSystemLanguages(); ?>
                            <select id="lang-pack" class="form-control" name="lang" style="width: 100%">
                                <option value="">&mdash; <?php echo __('Use Browser Preference'); ?> &mdash;</option>
                                <?php foreach($langs as $l) {
                                    $selected = ($info['lang'] == $l['code']) ? 'selected="selected"' : ''; ?>
                                    <option value="<?php echo $l['code']; ?>" <?php echo $selected;
                                    ?>><?php echo Internationalization::getLanguageDescription($l['code']); ?></option>
                                <?php } ?>
                            </select>
                            <span class="error">&nbsp;<?php echo $errors['lang']; ?></span>
                        </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php if ($acct->isPasswdResetEnabled()) { ?>
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><?php echo __('Access Credentials'); ?></h3>
                </div>
                <div class="box-body">
                    <?php if (!isset($_SESSION['_client']['reset-token'])) { ?>
                        <div class="form-group">
                            <label><?php echo __('Current Password'); ?>:</label>
                            <input class="form-control" type="password" size="18" name="cpasswd" value="<?php echo $info['cpasswd']; ?>">
                            &nbsp;<span class="error">&nbsp;<?php echo $errors['cpasswd']; ?></span>
                        </div>
                    <?php } ?>
                        <div class="form-group">
                            <label><?php echo __('New Password'); ?>:</label>
                            <input class="form-control" type="password" size="18" name="passwd1" value="<?php echo $info['passwd1']; ?>">
                            &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd1']; ?></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo __('Confirm New Password'); ?>:</label>
                            <input class="form-control" type="password" size="18" name="passwd2" value="<?php echo $info['passwd2']; ?>">
                            &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd2']; ?></span>
                        </div>

                </div>
            </div>
            <?php } ?>
            <?php } ?>
        </div>
        <div class="box-footer">
            <p style="text-align: center;">
                <input class="btn btn-success" type="submit" value="Update"/>
                <input class="btn btn-info" type="reset" value="Reset"/>
                <input class="btn btn-default" type="button" value="Cancel" onclick="javascript:
                window.location.href='index.php';"/>
            </p>
        </div>
        </form>
    </div>
</section>
<script type="text/javascript">
$(function() {
    $('#lang-pack').select2({
    width: 'resolve' // need to override the changed default
    });
});
</script>
