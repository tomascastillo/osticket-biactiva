<?php
if(!defined('OSTCLIENTINC') || !$thisclient || !$ticket || !$ticket->checkUserAccess($thisclient)) die('Access Denied!');

$info=($_POST && $errors)?Format::htmlchars($_POST):array();

$dept = $ticket->getDept();

if ($ticket->isClosed() && !$ticket->isReopenable())
    $warn = sprintf(__('%s is marked as closed and cannot be reopened.'), __('This ticket'));

//Making sure we don't leak out internal dept names
if(!$dept || !$dept->isPublic())
    $dept = $cfg->getDefaultDept();

if ($thisclient && $thisclient->isGuest()
    && $cfg->isClientRegistrationEnabled()) { ?>
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-compass"></i><?php echo __('Looking for your other tickets?'); ?></h4>
        <a href="<?php echo ROOT_PATH; ?>login.php?e=<?php
            echo urlencode($thisclient->getEmail());
        ?>" style="text-decoration:underline"><?php echo __('Sign In'); ?></a>
        <?php echo sprintf(__('or %s register for an account %s for the best experience on our help desk.'),
            '<a href="account.php?do=create" style="text-decoration:underline">','</a>'); ?>
    </div>
<?php } ?>
<section class="invoice">
    <!-- Title Row -->
    <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <a href="tickets.php?id=<?php echo $ticket->getId(); ?>" title="<?php echo __('Reload'); ?>"><i class="refresh icon-refresh"></i></a>
            <b>
                <?php $subject_field = TicketForm::getInstance()->getField('subject');
                    echo $subject_field->display($ticket->getSubject()); ?>
            </b>
            <small class="pull-right">#<?php echo $ticket->getNumber(); ?></small>
          </h2>
          <div class="pull-right">
            <a class="btn btn-default action-button" href="tickets.php?a=print&id=<?php
                echo $ticket->getId(); ?>"><i class="icon-print"></i> <?php echo __('Print'); ?></a>
            <?php if ($ticket->hasClientEditableFields()
                // Only ticket owners can edit the ticket details (and other forms)
                && $thisclient->getId() == $ticket->getUserId()) { ?>
                    <a class=" btn btn-default action-button" href="tickets.php?a=edit&id=<?php
                        echo $ticket->getId(); ?>"><i class="icon-edit"></i> <?php echo __('Edit'); ?></a>
            <?php } ?>
          </div>
        </div>
        <!-- /.col -->
    </div>
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <?php echo __('Basic Ticket Information'); ?><br>
            <br>
            <b><?php echo __('Ticket Status');?>:</b> <?php echo ($S = $ticket->getStatus()) ? $S->getLocalName() : ''; ?><br>
            <b><?php echo __('Department');?>:</b> <?php echo Format::htmlchars($dept instanceof Dept ? $dept->getName() : ''); ?><br>
            <b><?php echo __('Create Date');?>:</b> <?php echo Format::datetime($ticket->getCreateDate()); ?>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <?php echo __('User Information'); ?><br>
            <br>
            <b><?php echo __('Name');?>:</b> <?php echo mb_convert_case(Format::htmlchars($ticket->getName()), MB_CASE_TITLE); ?><br>
            <b><?php echo __('Email');?>:</b> <?php echo Format::htmlchars($ticket->getEmail()); ?><br>
            <b><?php echo __('Phone');?>:</b> <?php echo $ticket->getPhoneNumber(); ?>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <!-- Custom Data -->
            <?php
            $sections = array();
            foreach (DynamicFormEntry::forTicket($ticket->getId()) as $i=>$form) {
                // Skip core fields shown earlier in the ticket view
                $answers = $form->getAnswers()->exclude(Q::any(array(
                    'field__flags__hasbit' => DynamicFormField::FLAG_EXT_STORED,
                    'field__name__in' => array('subject', 'priority'),
                    Q::not(array('field__flags__hasbit' => DynamicFormField::FLAG_CLIENT_VIEW)),
                    )));
                // Skip display of forms without any answers
                foreach ($answers as $j=>$a) {
                    if ($v = $a->display())
                        $sections[$i][$j] = array($v, $a);
                }
            }
            foreach ($sections as $i=>$answers) {
            ?>
            <table class="custom-data" cellspacing="0" cellpadding="4" width="100%" border="0">
                <tr><td colspan="2" class="headline flush-left"><?php echo $form->getTitle(); ?></th></tr>
                <?php foreach ($answers as $A) {
                    list($v, $a) = $A; ?>
                    <tr>
                        <th><?php
                        echo $a->getField()->get('label');
                        ?>:</th>
                        <td><?php
                        echo $v;
                        ?></td>
                    </tr>
                <?php } ?>
            </table>
            <?php
            } ?>
        </div>
        <!-- /.col -->
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <?php
            $ticket->getThread()->render(array('M', 'R'), array(
                'mode' => Thread::MODE_CLIENT,
                'html-id' => 'ticketThread')
            );
            ?>
            <?php if($errors['err']) { ?>
                <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><?php echo $errors['err']; ?></p>
                </div>
            <?php }elseif($msg) { ?>
                <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p><?php echo $msg; ?></p></div>
            <?php }elseif($warn) { ?>
                <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p><?php echo $warn; ?></p></div>
            <?php } ?>            
        </div>
    </div> 
    <div class="row">
        <div class="col-xs-12">
            <?php if (!$ticket->isClosed() || $ticket->isReopenable()) { ?>
                <form id="reply" action="tickets.php?id=<?php echo $ticket->getId();
                ?>#reply" name="reply" method="post" enctype="multipart/form-data">
                <?php csrf_token(); ?>
                    <h2><?php echo __('Post a Reply');?></h2>
                    <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
                    <input type="hidden" name="a" value="reply">
                    <div>
                    <p><em><?php
                        echo __('To best assist you, we request that you be specific and detailed'); ?></em>
                        <font class="error">*&nbsp;<?php echo $errors['message']; ?></font>
                    </p>
                    <textarea name="message" id="message" cols="50" rows="9" wrap="soft"
                    class="<?php if ($cfg->isRichTextEnabled()) echo 'richtext';
                    ?> draft" <?php
                    list($draft, $attrs) = Draft::getDraftAndDataAttrs('ticket.client', $ticket->getId(), $info['message']);
                    echo $attrs; ?>><?php echo $draft ?: $info['message'];
                    ?></textarea>
                    <?php
                    if ($messageField->isAttachmentsEnabled()) {
                        print $attachments->render(array('client'=>true));
                    }   ?>
                    </div>
                    <?php if ($ticket->isClosed()) { ?>
                        <div class="warning-banner">
                            <?php echo __('Ticket will be reopened on message post'); ?>
                        </div>
                    <?php } ?>
                    <p style="text-align:center">
                        <input class="btn btn-success" type="submit" value="<?php echo __('Post Reply');?>">
                        <input class="btn btn-info" type="reset" value="<?php echo __('Reset');?>">
                        <input class="btn btn-default" type="button" value="<?php echo __('Cancel');?>" onClick="history.go(-1)">
                    </p>
                </form>
            <?php
            } ?>
        </div>
    </div>
</section>
<script type="text/javascript">
<?php
// Hover support for all inline images
$urls = array();
foreach (AttachmentFile::objects()->filter(array(
    'attachments__thread_entry__thread__id' => $ticket->getThreadId(),
    'attachments__inline' => true,
)) as $file) {
    $urls[strtolower($file->getKey())] = array(
        'download_url' => $file->getDownloadUrl(),
        'filename' => $file->name,
    );
} ?>
    showImagesInline(<?php echo JsonDataEncoder::encode($urls); ?>);
</script>
