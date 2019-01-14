<?php
$desc = $event->getDescription(ThreadEvent::MODE_CLIENT);
if (!$desc)
    return;
?>
<li>
  <i class="fa fa-thumb-tack bg-aqua"></i>
  <div class="timeline-item">
    <h3 class="timeline-header no-border"><div class="thread-event <?php if ($event->uid) echo 'action'; ?>">
        <span class="type-icon">
          <i class="faded icon-<?php echo $event->getIcon(); ?>"></i>
        </span>
        <span class="faded description"><?php echo $desc; ?></span>
</div></h3>
  </div>
</li>
