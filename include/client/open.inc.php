<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhoneNumber());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;

$form = null;
if (!$info['topicId']) {
    if (array_key_exists('topicId',$_GET) && preg_match('/^\d+$/',$_GET['topicId']) && Topic::lookup($_GET['topicId']))
        $info['topicId'] = intval($_GET['topicId']);
    else
        $info['topicId'] = $cfg->getDefaultTopicId();
}

$forms = array();
if ($info['topicId'] && ($topic=Topic::lookup($info['topicId']))) {
    foreach ($topic->getForms() as $F) {
        if (!$F->hasAnyVisibleFields())
            continue;
        if ($_POST) {
            $F = $F->instanciate();
            $F->isValidForClient();
        }
        $forms[] = $F;
    }
}

?>
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title"><?php echo __('Open a New Ticket');?>
            <small><?php echo __('Please fill in the form below to open a new ticket.');?></small></h2>
        </div>
        <div class="box-body">
            <form id="ticketForm" method="post" action="open.php" enctype="multipart/form-data">
                <?php csrf_token(); ?>
                <input type="hidden" name="a" value="open">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        if (!$thisclient) {
                            $uform = UserForm::getUserForm()->getForm($_POST);
                            if ($_POST) $uform->isValid();
                            $uform->render(false);
                        }
                        else { ?>
                            <dl>
                                <dt><?php echo __('Email'); ?>:</dt><dd><?php
                                echo $thisclient->getEmail(); ?></dd>
                                <dt><?php echo __('Client'); ?>:</dt><dd><?php
                                echo Format::htmlchars($thisclient->getName()); ?></dd>
                            </dl>
                        <?php } ?>
                        <div class="form-group">
                            <label><?php echo __('Help Topic'); ?><font class="text-red error"> *&nbsp;<?php echo $errors['topicId']; ?></font></label>
                            <select class="form-control" id="topicId" name="topicId" onchange="javascript:
                            var data = $(':input[name]', '#dynamic-form').serialize();
                            $.ajax(
                            'ajax.php/form/help-topic/' + this.value,
                            {
                            data: data,
                            dataType: 'json',
                            success: function(json) {
                                $('#dynamic-form').empty().append(json.html);
                                $(document.head).append(json.media);
                            }
                            });">
                            <option value="" selected="selected">&mdash; <?php echo __('Select a Help Topic');?> &mdash;</option>
                            <?php
                            if($topics=Topic::getPublicHelpTopics()) {
                                foreach($topics as $id =>$name) {
                                    echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
                                }
                            } else { ?>
                                <option value="0" ><?php echo __('General Inquiry');?></option>
                            <?php
                            } ?>
                            </select>
                            
                        </div>
                        <div id="dynamic-form">
                        <?php foreach ($forms as $form) { ?>
                            <div class="box">
                                <?php include(CLIENTINC_DIR . 'templates/dynamic-form.tmpl.php'); ?>
                            </div>
                        <?php } ?>
                        </div>
                        <?php
                            if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
                                if($_POST && $errors && !$errors['captcha'])
                                    $errors['captcha']=__('Please re-enter the text again');
                                ?>
                            <div class="box">
                                <tr class="captchaRow">
                                    <td class="required"><?php echo __('CAPTCHA Text');?>:</td>
                                    <td>
                                        <span class="captcha"><img src="captcha.php" border="0" align="left"></span>
                                        &nbsp;&nbsp;
                                        <input id="captcha" type="text" name="captcha" size="6" autocomplete="off">
                                        <em><?php echo __('Enter the text shown on the image.');?></em>
                                        <font class="text-red error">*&nbsp;<?php echo $errors['captcha']; ?></font>
                                    </td>
                                </tr>
                            </div>
                        <?php
                        } ?>
                        <p class="buttons" style="text-align:center;">
                            <input class="btn btn-success" type="submit" value="<?php echo __('Create Ticket');?>">
                            <input class="btn btn-info" type="reset" name="reset" value="<?php echo __('Reset');?>">
                            <input class="btn btn-default" type="button" name="cancel" value="<?php echo __('Cancel'); ?>" onclick="javascript:
                            $('.richtext').each(function() {
                                var redactor = $(this).data('redactor');
                                if (redactor && redactor.opts.draftDelete)
                                    redactor.deleteDraft();
                            });
                            window.location.href='index.php';">
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
  

