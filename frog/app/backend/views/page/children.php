<ul<?php if ($level == 1) echo ' id="site-map"'; ?>>
<?php foreach($childrens as $child): ?> 
    <li id="page_<?php echo $child->id; ?>" class="node level-<?php echo $level; if ( ! $child->has_children) echo ' no-children'; else if ($child->is_expanded) echo ' children-visible'; else echo ' children-hidden'; ?>">
      <div class="page">
        <span class="w1">
          <?php if ($child->has_children): ?><img align="middle" alt="toggle children" class="expander" src="app/backend/assets/images/<?php echo $child->is_expanded ? 'collapse': 'expand'; ?>.png" title="" /><?php endif; ?>
<?php if ( ! AuthUser::hasPermission('administrator') && ! AuthUser::hasPermission('developer') && $child->is_protected): ?>
<img align="middle" class="icon" src="app/backend/assets/images/page.png" alt="page icon" /> <span class="title protected"><?php echo $child->title; ?></span> <img class="handle" src="app/backend/assets/images/drag.gif" alt="<?php echo __('Drag and Drop'); ?>" align="middle" />
<?php else: ?>
<a href="<?php echo get_url('page/edit/'.$child->id); ?>" title="/<?php echo ($parent->url === '' ? '': $parent->url.'/').$child->slug; ?>"><img align="middle" class="icon" src="app/backend/assets/images/page.png" alt="page icon" /> <span class="title"><?php echo $child->title; ?></span></a> <img class="handle" src="app/backend/assets/images/drag.gif" alt="<?php echo __('Drag and Drop'); ?>" align="middle" />
<?php endif; ?>
          <?php if (! empty($child->behavior_id)): ?> <small class="info">(<?php echo Inflector::humanize($child->behavior_id); ?>)</small><?php endif; ?> 
          <img align="middle" alt="" class="busy" id="busy-<?php echo $child->id; ?>" src="app/backend/assets/images/spinner.gif" style="display: none;" title="" />
        </span>
      </div>
<?php switch ($child->status_id) {
      case Page::STATUS_DRAFT: echo '<div class="status draft-status">'.__('Draft').'</div>'; break;
      case Page::STATUS_REVIEWED: echo '<div class="status reviewed-status">'.__('Reviewed').'</div>'; break;
      case Page::STATUS_PUBLISHED: echo '<div class="status published-status">'.__('Published').'</div>'; break;
      case Page::STATUS_HIDDEN: echo '<div class="status hidden-status">'.__('Hidden').'</div>'; break;
} ?> 
      <div class="modify">
        <a href="<?php echo get_url('page/add', $child->id); ?>"><img src="app/backend/assets/images/plus.png" align="middle" alt="<?php echo __('Add child'); ?>" /></a>&nbsp; 
<?php if ( ! $child->is_protected || AuthUser::hasPermission('administrator') || AuthUser::hasPermission('developer')): ?>
        <a class="remove" href="<?php echo get_url('page/delete/'.$child->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete'); ?> <?php echo $child->title; ?>?');"><img src="app/backend/assets/images/icon-remove.gif" align="middle" alt="<?php echo __('Remove page'); ?>" /></a>
<?php endif; ?>
      </div>
<?php if ($child->is_expanded) echo $child->children_rows; ?>
    </li>
<?php endforeach; ?>
</ul>