<h1 id="new_page"><?php echo $action=='add' ? __('New Page'): __('Edit page'); ?></h1>

<form action="<?php if($action == 'add') echo get_url('page/add'); else echo  get_url('page/edit/'.$page->id); ?>" method="post">
<input id="page_parent_id" name="page[parent_id]" type="hidden" value="<?php echo $page->parent_id ?>" />
  <div class="form-area">
    <p class="title">
      <label for="page_title"><?php echo __('Page Title') ?></label>
      <input class="textbox" id="page_title" maxlength="255" name="page[title]" size="255" type="text" value="<?php echo $page->title ?>" />
    </p>
    <div id="extended-metadata" class="row" style="display: none">
      <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td><label for="page_slug"><?php echo __('Slug') ?></label></td>
          <td class="field"><input class="textbox" id="page_slug" maxlength="100" name="page[slug]" size="100" type="text" value="<?php echo Page::getSlug($page->slug) ?>" /></td>
        </tr>
        <tr>
          <td><label for="page_breadcrumb"><?php echo __('Breadcrumb') ?></label></td>
          <td class="field"><input class="textbox" id="page_breadcrumb" maxlength="160" name="page[breadcrumb]" size="160" type="text" value="<?php echo htmlentities($page->breadcrumb, ENT_COMPAT, 'UTF-8') ?>" /></td>
        </tr>
      </table>
      <script type="text/javascript">
      // <![CDATA[
        $title = $('page_title');
        $slug = $('page_slug');
        $breadcrumb = $('page_breadcrumb');
        $old_title = $title.value || '';
        function title_updated() {
          if ($old_title.toSlug() == $slug.value) $slug.value = $title.value.toSlug();
          if ($old_title == $breadcrumb.value) $breadcrumb.value = $title.value;
          $old_title = $title.value;
        }
        Event.observe('page_title', 'keyup', title_updated);
      // ]]>
      </script>
    </div>
    <p class="more-or-less">
      <small>
        <a href="#" onclick="Element.toggle('extended-metadata'); Element.toggle('more-extended-metadata'); Element.toggle('less-extended-metadata'); return false;" id="more-extended-metadata"><?php echo __('More') ?></a>
        <a href="#" onclick="Element.toggle('extended-metadata'); Element.toggle('more-extended-metadata'); Element.toggle('less-extended-metadata'); return false;" style="display: none" id="less-extended-metadata"><?php echo __('Less') ?></a>
      </small>
    </p>
    <div id="tab-control">
      <div id="tabs" class="tabs">
        <div id="tab-toolbar">
          <a href="#" onclick="toggle_add_part_popup(); return false;" title="<?php echo __('Add Tab') ?>"><img alt="<?php echo __('Plus') ?>" src="images/plus.png" /></a>
          <a href="#" onclick="if(tabControl._tabify(tabControl.selected).tab_id == 'tab-1') { alert('You can\'t remove the Body Tab');} else if(confirm('<?php echo __('Delete the current tab?') ?>')) { tabControl.removeTab(tabControl.selected) }; return false;" title="<?php echo __('Remove Tab') ?>"><img alt="<?php echo __('Minus') ?>" src="images/minus.png" /></a>
        </div>
      </div>
      <div id="page_parts">
<?php $index_part =1; foreach ($page_parts as $page_part) { ?>
<div style="display: none;" class="page" id="page-<?php echo $index_part ?>">
  <div class="part" id="part-<?php echo $index_part ?>">
<?php if (isset($page_part->id)) { ?>
    <input id="part[<?php echo ($index_part-1) ?>][id]" name="part[<?php echo ($index_part-1) ?>][id]" type="hidden" value="<?php echo $page_part->id ?>" />
<?php } ?>
    <input id="part[<?php echo ($index_part-1) ?>][name]" name="part[<?php echo ($index_part-1) ?>][name]" type="hidden" value="<?php echo $page_part->name ?>" />
    <p>
      <label for="part[<?php echo ($index_part-1) ?>][filter_id]"><?php echo __('Filter') ?></label>
      <select id="part[<?php echo ($index_part-1) ?>][filter_id]" name="part[<?php echo ($index_part-1) ?>][filter_id]" onchange="$('markdown_toolbar_<?php echo ($index_part-1) ?>').style.display = this.options[this.selectedIndex].value == 'Markdown' ? 'block': 'none';">
        <option value=""<?php if ($page_part->filter_id == '') echo ' selected="selected"'; ?>>&#8212; <?php echo __('none') ?> &#8212;</option>
<?php foreach ($filters as $filter) { ?>
        <option value="<?php echo $filter?>"<?php if ($page_part->filter_id == $filter) echo ' selected="selected"'; ?>><?php echo$filter?></option>
<?php } // foreach ?>
      </select>
    </p>
    <div><textarea class="textarea" id="part[<?php echo ($index_part-1) ?>][content]" name="part[<?php echo ($index_part-1) ?>][content]" style="width: 100%"><?php echo htmlentities($page_part->content, ENT_COMPAT, 'UTF-8') ?></textarea></div>
    <script type="text/javascript">
    // <![CDATA[
      markdown_toolbar = new Control.TextArea.ToolBar.Markdown('part[<?php echo ($index_part-1) ?>][content]');
      markdown_toolbar.toolbar.container.id = 'markdown_toolbar_<?php echo ($index_part-1) ?>';
      <?php if ($page_part->filter_id != 'Markdown') echo '$(\'markdown_toolbar_'.($index_part-1).'\').style.display = \'none\';'; ?>
    // ]]>
    </script>
  </div>
</div>
<?php $index_part++; } // foreach (page_parts) ?>
      </div>
    </div>
    <script type="text/javascript">
    // <![CDATA[
      var tabControl = new TabControl('tab-control');
<?php $index_part=1; foreach ($page_parts as $page_part) { ?>
      tabControl.addTab('tab-<?php echo $index_part ?>', '<?php echo $page_part->name ?>', 'page-<?php echo $index_part ?>');
<?php $index_part++; } // foreach (page_parts) ?>
      tabControl.select(tabControl.firstTab());
    // ]]>
    </script>
    <div class="row">
      <p><label for="page_layout_id"><?php echo __('Layout') ?></label>
        <select id="page_layout_id" name="page[layout_id]">
          <option value="">&#8212; <?php echo __('inherit') ?> &#8212;</option>
<?php foreach ($layouts as $layout) { ?>
          <option value="<?php echo $layout->id ?>" <?php echo $layout->id==$page->layout_id ? 'selected="selected"': '' ?>><?php echo $layout->name ?></option>
<?php } // foreach ?>
        </select>
      </p>

      <p><label for="page_behavior_id"><?php echo __('Page Type') ?></label>
        <select id="page_behavior_id" name="page[behavior_id]">
          <option value=""<?php if ($page->behavior_id == '') echo ' selected'; ?>>&#8212; <?php echo __('none') ?> &#8212;</option>
  <?php foreach ($behaviors as $behavior) { ?>
          <option value="<?php echo $behavior; ?>"<?php if ($page->behavior_id == $behavior) echo ' selected="selected"'; ?>><?php echo humanize($behavior); ?></option>
  <?php } // foreach ?>
  </select>
      </p>

      <p><label for="page_status_id"><?php echo __('Status') ?></label>
        <select id="page_status_id" name="page[status_id]">
          <option value="<?php echo Page::STATUS_DRAFT ?>" <?php echo $page->status_id == Page::STATUS_DRAFT ? 'selected="selected"': '' ?>><?php echo __('Draft') ?></option>
          <option value="<?php echo Page::STATUS_REVIEWED ?>"<?php echo $page->status_id == Page::STATUS_REVIEWED ? 'selected="selected"': '' ?>><?php echo __('Reviewed') ?></option>
          <option value="<?php echo Page::STATUS_PUBLISHED ?>"<?php echo $page->status_id == Page::STATUS_PUBLISHED || ! $page->status_id ? 'selected="selected"': '' ?>><?php echo __('Published') ?></option>
          <option value="<?php echo Page::STATUS_HIDDEN ?>"<?php echo $page->status_id == Page::STATUS_HIDDEN ? 'selected="selected"': '' ?>><?php echo __('Hidden') ?></option>
        </select>
      </p>

    </div>
    <span class="clear">&nbsp;</span>
    <p class="clear">&nbsp;</p>
    <div id="attachments"></div>
<?php if (isset($page->updated_on)) { ?>
    <p style="clear: left"><small><?php echo __('Last updated by') ?> <?php echo $page->updated_by_name ?> <?php echo __('on') ?> <?php echo $page->updated_on ?></small></p>
<?php } ?>
    
  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save') ?> (Alt+S)" />
    <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing') ?> (Alt+E)" />
    <?php echo __('or') ?> <a href="<?php echo get_url('page') ?>"><?php echo __('Cancel') ?></a>
  </p>
</form>

<div id="popups">
  <div class="popup" id="add-part-popup" style="display:none;">
    <div id="busy" class="busy" style="display: none"><img alt="Spinner" src="images/spinner.gif" /></div>
    <h3><?php echo __('Add Part') ?></h3>
    <form action="<?php echo get_url('page/addPart') ?>" method="post" onsubmit="if (valid_part_name()) { new Ajax.Updater('page_parts', '<?php echo get_url('page/addPart') ?>', {asynchronous:true, evalScripts:true, insertion:Insertion.Bottom, onComplete:function(request){part_added()}, onLoading:function(request){part_loading()}, parameters:Form.serialize(this)}); }; return false;"> 
      <div>
        <input id="part-index-field" name="part[index]" type="hidden" value="<?php echo $index_part ?>" />
        <input id="part-name-field" maxlength="100" name="part[name]" type="text" value="" /> 
        <input id="add-part-button" name="commit" type="submit" value="<?php echo __('Add') ?>" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('add-part-popup'); return false;"><?php echo __('Close') ?></a></p>
    </form>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
Field.activate('page_title');
// ]]>
</script>