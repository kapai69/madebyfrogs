<h1 id="new_page"><?php echo $action=='add' ? 'New Page': 'Edit page'; ?></h1>

<form action="<?php if($action == 'add') echo get_url('pages/add'); else echo  get_url('pages/edit', $page->id); ?>" method="post">
<input id="page_parent_id" name="page[parent_id]" type="hidden" value="<?php echo $page->parent_id ?>" />
  <div class="form-area">
    <p class="title">
      <label for="page_title">Page Title</label>

      <input class="textbox" id="page_title" maxlength="255" name="page[title]" size="255" type="text" value="<?php echo $page->title ?>" />
    </p>
    <div id="extended-metadata" class="row" style="display: none">
      <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td><label for="page_slug">Slug</label></td>
          <td class="field"><input class="textbox" id="page_slug" maxlength="100" name="page[slug]" size="100" type="text" value="<?php echo $page->slug ?>" /></td>
        </tr>

        <tr>
          <td><label for="page_breadcrumb">Breadcrumb</label></td>
          <td class="field"><input class="textbox" id="page_breadcrumb" maxlength="160" name="page[breadcrumb]" size="160" type="text" value="<?php echo $page->breadcrumb ?>" /></td>
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
        <a href="javascript:Element.toggle('extended-metadata', 'more-extended-metadata', 'less-extended-metadata')" id="more-extended-metadata">More</a>
        <a href="javascript:Element.toggle('extended-metadata', 'more-extended-metadata', 'less-extended-metadata')" style="display: none" id="less-extended-metadata">Less</a>
      </small>
    </p>
    <div id="tab-control">
      <div id="tabs" class="tabs">
        <div id="tab-toolbar">

          <a href="javascript: toggle_add_part_popup()" title="Add Tab"><img alt="Plus" src="images/plus.png" /></a>
          <a href="javascript: tabControl.removeTab(tabControl.selected)" onclick="return confirm('Delete the current tab?');" title="Remove Tab"><img alt="Minus" src="images/minus.png" /></a>
        </div>
      </div>
      <div id="page_parts">
<?php $index_part =1; foreach ($page_parts as $page_part) { ?>
<div style="display: none;" class="page" id="page-<?php echo $index_part ?>">
  <div class="part" id="part-<?php echo $index_part ?>">
<?php if (isset($page_part->id)) { ?>
    <input id="part[<?php echo ($index_part-1) ?>][id]" name="part[<?php echo ($index_part-1) ?>][id]" type="hidden" value="<?php echo $page_part->id ?>" />
<?php } // if ?>
    <input id="part[<?php echo ($index_part-1) ?>][name]" name="part[<?php echo ($index_part-1) ?>][name]" type="hidden" value="<?php echo $page_part->name ?>" />
    <p>
      <label for="part[<?php echo ($index_part-1) ?>][filter_id]">Filter</label>
      <select id="part[<?php echo ($index_part-1) ?>][filter_id]" name="part[<?php echo ($index_part-1) ?>][filter_id]">
        <option value=""<?php if($page_part->filter_id == '') echo ' selected="selected"'; ?>>&#8212; none &#8212;</option>
<?php foreach ($filters as $filter) { ?>
        <option value="<?php echo$filter?>"<?php if($page_part->filter_id == $filter) echo ' selected="selected"'; ?>><?php echo$filter?></option>
<?php } // foreach ?>
      </select>
    </p>
    <div><textarea class="textarea" id="part[<?php echo ($index_part-1) ?>][content]" name="part[<?php echo ($index_part-1) ?>][content]" style="width: 100%"><?php echo $page_part->content; ?></textarea></div>
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

      <p><label for="page_layout_id">Layout</label>
        <select id="page_layout_id" name="page[layout_id]">
          <option value="">&#8212; inherit &#8212;</option>
<?php foreach ($layouts as $layout) { ?>
          <option value="<?php echo $layout->id ?>" <?php echo $layout->id==$page->layout_id ? 'selected="selected"': '' ?>><?php echo $layout->name ?></option>
<?php } // foreach ?>
        </select>
      </p>

      <p><label for="page_behavior_id">Behavior</label>
        <select id="page_behavior_id" name="page[behavior_id]">
          <option value=""<?php if ($page->behavior_id == '') echo ' selected'; ?>>&#8212; none &#8212;</option>
  <?php foreach ($behaviors as $behavior) { ?>
          <option value="<?php echo $behavior; ?>"<?php if ($page->behavior_id == $behavior) echo ' selected="selected"'; ?>><?php echo humanize($behavior); ?></option>
  <?php } // foreach ?>
  </select>
      </p>

      <p><label for="page_status_id">Status</label>
        <select id="page_status_id" name="page[status_id]">
          <option value="<?php echo PAGE_STATUS_DRAFT ?>" <?php echo $page->status_id==PAGE_STATUS_DRAFT ? 'selected="selected"': '' ?>>Draft</option>
          <option value="<?php echo PAGE_STATUS_REVIEWED ?>"<?php echo $page->status_id==PAGE_STATUS_REVIEWED ? 'selected="selected"': '' ?>>Reviewed</option>
          <option value="<?php echo PAGE_STATUS_PUBLISHED ?>"<?php echo $page->status_id==PAGE_STATUS_PUBLISHED ? 'selected="selected"': '' ?>>Published</option>
          <option value="<?php echo PAGE_STATUS_HIDDEN ?>"<?php echo $page->status_id==PAGE_STATUS_HIDDEN ? 'selected="selected"': '' ?>>Hidden</option>
        </select>
      </p>

    </div>
    <span class="clear">&nbsp;</span>
    <p class="clear">&nbsp;</p>
    <div id="attachments"></div>
<?php if (isset($page->updated_on)) { ?>
    <p style="clear: left"><small>Last updated by <?php echo $page->updated_by_name ?> on <?php echo $page->updated_on ?></small></p>
<?php } ?>
    
  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="Save (Alt+S)" />
    <input class="button" name="continue" type="submit" accesskey="e" value="Save and Continue Editing (Alt+E)" />
    or <a href="<?php echo get_url('pages') ?>">Cancel</a>
  </p>
</form>

<div id="popups">
  <div class="popup" id="add-part-popup" style="display:none;">
    <div id="busy" class="busy" style="display: none"><img alt="Spinner" src="images/spinner.gif" /></div>
    <h3>Add Part</h3>
    <form action="<?php echo get_url('pages/addPart') ?>" method="post" onsubmit="if (valid_part_name()) { new Ajax.Updater('page_parts', '<?php echo get_url('pages/addPart') ?>', {asynchronous:true, evalScripts:true, insertion:Insertion.Bottom, onComplete:function(request){part_added()}, onLoading:function(request){part_loading()}, parameters:Form.serialize(this)}); }; return false;"> 
      <div>
        <input id="part-index-field" name="part[index]" type="hidden" value="<?php echo $index_part ?>" />
        <input id="part-name-field" maxlength="100" name="part[name]" type="text" value="" /> 
        <input id="add-part-button" name="commit" type="submit" value="Add Part" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('add-part-popup'); return false;">Close</a></p>
    </form>
  </div>
</div>
    <script type="text/javascript">
    // <![CDATA[
  	  Field.activate('page_title');
    // ]]>
    </script>