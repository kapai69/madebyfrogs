<?php use_helper('page'); ?>
<h1>Pages</h1>

<div id="site-map-def">
    <div class="page"><?php echo __('Page') ?></div>
    <div class="status"><?php echo __('Status') ?></div>
    <div class="modify"><?php echo __('Modify') ?></div>
    <div class="order"><?php echo __('Order') ?></div>
</div>

<ul id="site-map-root">
    <li id="page_root" class="node level-0">
      <div class="page" style="padding-left: 4px">
        <span class="w1">
          <a href="<?php echo get_url('pages/edit/1'); ?>" title="/"><img align="center" alt="page-icon" class="icon" src="images/page.png" title="" /> <span class="title"><?php echo $root->title; ?></span></a>
        </span>
      </div>
      <div class="status published-status"><?php echo __('Published') ?></div>
      <div class="modify">
          <a href="<?php echo get_url('pages/add/1'); ?>"><img alt="<?php echo __('Add child') ?>" src="images/add-child.png" /></a></td>
          <img class="remove" alt="<?php echo __('Remove page disable') ?>" src="images/remove-disabled.png" />
          <img src="images/drag-disabled.png" alt="<?php echo __('Drag and Drop disable') ?>" />
      </div>
    </li>
</ul>

<?php echo $content_children; ?>

<script type="text/javascript">
// <![CDATA[
  new SiteMap('site-map', [1]);
// ]]>
</script>
