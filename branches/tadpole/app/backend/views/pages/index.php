<?php use_helper('page'); ?>
<h1>Pages</h1>

<table id="site-map" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th class="page">Page</th>
      <th class="status">Status</th>
      <th class="modify" colspan="2">Modify</th>
    </tr>
  </thead>
  <tbody>
    <tr id="page-1" class="node level-0 children-visible">
      <td class="page" style="padding-left: 4px">
        <span class="w1">
          <img align="center" alt="toggle children" class="expander" src="images/collapse.png" title="" /><a href="<?php echo get_url('pages/edit/1'); ?>" title="/"><img align="center" alt="page-icon" class="icon" src="images/page.png" title="" /> <span class="title"><?php echo $root->title; ?></span></a> 
          <img align="center" alt="" class="busy" id="busy-1" src="images/spinner.gif" style="display: none;" title="" />
        </span>
      </td>
      <td class="status published-status">Published</td>
      <td class="add-child"><a href="<?php echo get_url('pages/add/1'); ?>"><img alt="add child" src="images/add-child.png" /></a></td>
      <td class="remove"><img alt="remove page" src="images/remove-disabled.png" /></td>
    </tr>
    <?php echo $content_children; ?>
  </tbody>
</table>

<script type="text/javascript">
// <![CDATA[
  new SiteMap('site-map', [1]);
// ]]>
</script>