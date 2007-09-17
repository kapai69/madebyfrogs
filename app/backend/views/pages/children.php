<ul<?php if ($level == 1) echo ' id="site-map"'; ?>>
<?php foreach($children as $child): ?> 
    <li id="page_<?php echo $child->id; ?>" class="node level-<?php echo $level; if ( ! $child->has_children) echo ' no-children'; else if ($child->is_expanded) echo ' children-visible'; else echo ' children-hidden'; ?> dragable">
      <div class="page">
        <span class="w1">
          <?php if ($child->has_children): ?><img align="center" alt="toggle children" class="expander" src="images/<?php echo $child->is_expanded ? 'collapse': 'expand'; ?>.png" title="" /><?php endif; ?><a href="<?php echo get_url('pages/edit/'.$child->id); ?>" title="<?php echo $child->slug; ?>/"><img align="center" alt="page-icon" class="icon" src="images/page.png" title="" /> <span class="title"><?php echo $child->title; ?></span></a> 
          <img align="center" alt="" class="busy" id="busy-<?php echo $child->id; ?>" src="images/spinner.gif" style="display: none;" title="" />
        </span>
      </div>
      <?php 
        switch ($child->status_id) {
          case PAGE_STATUS_DRAFT: echo '<div class="status draft-status">'.__('Draft').'</div>'; break;
          case PAGE_STATUS_REVIEWED: echo '<div class="status reviewed-status">'.__('Reviewed').'</div>'; break;
          case PAGE_STATUS_PUBLISHED: echo '<div class="status published-status">'.__('Published').'</div>'; break;
          case PAGE_STATUS_HIDDEN: echo '<div class="status hidden-status">'.__('Hidden').'</div>'; break;
        }
        ?> 
      <div class="modify">
        <a href="<?php echo get_url('pages/add', $child->id); ?>"><img alt="<?php echo __('Add child') ?>" src="images/add-child.png" /></a>
        <a class="remove" href="<?php echo get_url('pages/delete/'.$child->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete') ?> <?php echo $child->title ?>?');"><img alt="<?php echo __('Remove page') ?>" src="images/remove.png" /></a>
        <img class="handle" src="images/drag.png" alt="<?php echo __('Drag and Drop') ?>" />
      </div>
<?php if ($child->is_expanded) echo $child->children_rows; ?>
    </li>
<?php endforeach; ?>
</ul>