<?php foreach($children as $child): ?> 
    <tr id="page-<?php echo $child->id; ?>" class="node level-<?php echo $level; if ( ! $child->has_children) echo ' no-children'; else if ($child->is_expanded) echo ' children-visible'; else echo ' children-hidden'; ?>">
      <td class="page" style="padding-left: <?php echo ($level*22+4); ?>px">
        <span class="w1">
          <?php if ($child->has_children): ?><img align="center" alt="toggle children" class="expander" src="images/<?php echo $child->is_expanded ? 'collapse': 'expand'; ?>.png" title="" /><?php endif; ?><a href="<?php echo get_url('pages/edit', $child->id); ?>" title="<?php echo $child->slug; ?>/"><img align="center" alt="page-icon" class="icon" src="images/page.png" title="" /> <span class="title"><?php echo $child->title; ?></span></a> 
          <img align="center" alt="" class="busy" id="busy-<?php echo $child->id; ?>" src="images/spinner.gif" style="display: none;" title="" />
        </span>
      </td>
      <?php 
        switch ($child->status_id) {
          case PAGE_STATUS_DRAFT: echo '<td class="status draft-status">Draft</td>'; break;
          case PAGE_STATUS_REVIEWED: echo '<td class="status reviewed-status">Reviewed</td>'; break;
          case PAGE_STATUS_PUBLISHED: echo '<td class="status published-status">Published</td>'; break;
          case PAGE_STATUS_HIDDEN: echo '<td class="status hidden-status">Hidden</td>'; break;
        }
        ?> 
      <td class="add-child"><a href="<?php echo get_url('pages/add', $child->id); ?>"><img alt="add child" src="images/add-child.png" /></a></td>
      <td class="remove"><a href="<?php echo get_url('pages/delete', $child->id); ?>"><img alt="remove page" src="images/remove.png" /></a></td>
    </tr>
<?php if ($child->is_expanded) echo $child->children_rows; ?>
<?php endforeach; ?>