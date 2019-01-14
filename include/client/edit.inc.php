<?php

if(!defined('OSTCLIENTINC') || !$thisclient || !$ticket || !$ticket->checkUserAccess($thisclient)) die('Access Denied!');

?>
<section class="content-header">
    <h1>
    <?php echo sprintf(__('Editing Ticket <small>#%s</small>'), $ticket->getNumber()); ?>
    </h1>
</section>
<section class="content">
    <div class="box box-default">
        <div class="box-body">
        <form action="tickets.php" method="post">
            <?php echo csrf_token(); ?>
            <input type="hidden" name="a" value="edit"/>
            <input type="hidden" name="id" value="<?php echo Format::htmlchars($_REQUEST['id']); ?>"/>
            <div class="box box-solid">
                <?php if ($forms)
                foreach ($forms as $form) {
                    $form->render(false);
                } ?>
            </div>
            <p style="text-align: center;">
                <input class="btn btn-success" type="submit" value="Update"/>
                <input class="btn btn-info" type="reset" value="Reset"/>
                <input class="btn btn-default" type="button" value="Cancel" onclick="javascript:
                window.location.href='index.php';"/>
            </p>
        </form>
        </div>
    </div>
</section>

