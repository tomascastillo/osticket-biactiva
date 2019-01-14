<?php
    // Form headline and deck with a horizontal divider above and an extra
    // space below.
    // XXX: Would be nice to handle the decoration with a CSS class
    ?>
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo Format::htmlchars($form->getTitle()); ?>
        <small><?php echo Format::display($form->getInstructions()); ?></small></h3>
    </div>
    <div class="box-body">
    <?php
    // Form fields, each with corresponding errors follows. Fields marked
    // 'private' are not included in the output for clients
    global $thisclient;
    foreach ($form->getFields() as $field) {
        if (isset($options['mode']) && $options['mode'] == 'create') {
            if (!$field->isVisibleToUsers() && !$field->isRequiredForUsers())
                continue;
        }
        elseif (!$field->isVisibleToUsers() && !$field->isEditableToUsers()) {
            continue;
        }
        ?>
        
            <?php if (!$field->isBlockLevel()) { ?>
                <div class="form-group">
                <label for="<?php echo $field->getFormName(); ?>"><span class="<?php
                    if ($field->isRequiredForUsers()) echo 'required'; ?>">
                        <?php echo Format::htmlchars($field->getLocal('label')); ?>
                    <?php if ($field->isRequiredForUsers()) { ?>
                        <span class="text-red error">*</span>
                    <?php }
                ?></span><?php
                if ($field->get('hint')) { ?>
                    <br /><em style="color:gray;display:inline-block"><?php
                        echo Format::viewableImages($field->getLocal('hint')); ?></em>
                <?php
                } ?>
            <br/>
            <?php
            }
            $field->render(array('client'=>true));
            ?></label>
            <?php
            foreach ($field->errors() as $e) { ?>
                <div class="text-red error"><?php echo $e; ?></div>
            <?php }
            $field->renderExtras(array('client'=>true));
            ?>
            </div>
        <?php
    }
?>
    </div>
