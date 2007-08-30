
<h1><?php echo camelize($action) ?> Layout</h1>

<form action="<?php echo $action=='edit' ? get_url('layouts', 'edit', $layout->id): get_url('layouts', 'add');  ?>" method="post">
  <div class="form-area">
    <p class="title">
      <label for="layout_name">Name</label>
      <input class="textbox" id="layout_name" maxlength="100" name="layout[name]" size="100" type="text" value="<?php echo $layout->name ?>" />
    </p>
    <div id="extended-metadata" class="row">
      <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td><label for="layout_content_type">Content-Type</label></td>
          <td class="field"><input class="textbox" id="layout_content_type" maxlength="40" name="layout[content_type]" size="40" type="text" value="<?php echo $layout->content_type ?>" /></td>
        </tr>
      </table>
    </div>
    <p class="content">
      <label for="layout_content">Body</label>
      <textarea class="textarea" cols="40" id="layout_content" name="layout[content]" rows="20" style="width: 100%"><?php echo htmlentities($layout->content, ENT_COMPAT, 'UTF-8') ?></textarea>
    </p>
    <p class="clear">&nbsp;</p>
<?php if (isset($layout->updated_on)) { ?>
    <p style="clear: left"><small>Last updated by <?php echo $layout->updated_by_name ?> on <?php echo $layout->updated_on ?></small></p>
<?php } ?>
  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="Save (Alt+S)" />
    <input class="button" name="continue" type="submit" accesskey="e" value="Save and Continue Editing (Alt+E)" />
    or <a href="<?php echo get_url('layouts') ?>">Cancel</a>
  </p>
</form>

<script type="text/javascript">
// <![CDATA[
  document.getElementById('layout_name').focus();
// ]]>
</script>