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
        <a class="remove" href="<?php echo get_url('pages/delete', $child->id); ?>"><img alt="<?php echo __('Remove page') ?>" src="images/remove.png" /></a>
        <img class="handle" src="images/drag.png" alt="<?php echo __('Drag and Drop') ?>" />
      </div>
<?php if ($child->is_expanded) echo $child->children_rows; ?>
    </li>
<?php endforeach; ?>
</ul>
<script type="text/javascript" language="javascript">
// <![CDATA[
  Sortable.destroy('site-map');
  Sortable.create('site-map', 
    { constraint:'vertical', scroll:window, handle:'handle', tree:true, only:'dragable', 
      onChange: function(element) {
        // this will make the page displayed at the level + 1 of the parent
        var currentLevel = 1;
        var parentLevel = 0;
        currentElementSelected = element;
        
        if (/level-(\d+)/i.test(element.className))
          currentLevel = RegExp.$1.toInteger();
          
        if (/level-(\d+)/i.test(element.parentNode.parentNode.className))
          parentLevel = RegExp.$1.toInteger();

        if (currentLevel != parentLevel+1) {
           Element.removeClassName(element, 'level-'+currentLevel);
           Element.addClassName(element, 'level-'+(parentLevel+1));
        }
        // this will update all childs level
        var container = Element.findChildren(element, false, false, 'ul');
        if (container.length == 1) {
            var childs = Element.findChildren(container[0], false, false, 'li');
            for (var i=0; i<childs.length;i++) {
                childs[i].className = childs[i].className.replace(/level-(\d+)/, 'level-'+(parentLevel+2));
            }
        }
      },
      onUpdate: function() {
        var parent = null;
        var parent_id = 1;
        var pages = [];
        var data = '';
        
        if (/page_(\d+)/i.test(currentElementSelected.parentNode.parentNode.id)) {
          parent_id = RegExp.$1.toInteger();
          parent = currentElementSelected.parentNode;
        } else {
          parent = $('site-map');
        }
        
        pages = Element.findChildren(parent, false, false, 'li');
        
        for(var i=0; i<pages.length; i++) {
          data += 'pages[]='+SiteMap.prototype.extractPageId(pages[i])+'&';
        }
        
        new Ajax.Request('<?php echo get_url('pages/reorder/') ?>'+parent_id, {
          method: "post",
          parameters: { 'data': data }
        });
      }
    });
// ]]>
</script>