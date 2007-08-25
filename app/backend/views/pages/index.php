<?php use_helper('page'); ?>
<h1>Pages</h1>

<div id="site-map-def">
    <div class="page">Page</div>
    <div class="status">Status</div>
    <div class="modify">Modify</div>
    <div class="order">Order</div>
</div>

<ul id="site-map-root">
    <li id="page_root" class="node level-0">
      <div class="page" style="padding-left: 4px">
        <span class="w1">
          <a href="<?php echo get_url('pages/edit/1'); ?>" title="/"><img align="center" alt="page-icon" class="icon" src="images/page.png" title="" /> <span class="title"><?php echo $root->title; ?></span></a>
        </span>
      </div>
      <div class="status published-status">Published</div>
      <div class="modify">
          <a href="<?php echo get_url('pages/add/1'); ?>"><img alt="add child" src="images/add-child.png" /></a></td>
          <img class="remove" alt="remove page" src="images/remove-disabled.png" />
          <img src="images/drag-disabled.png" alt="drag and drop disable" />
      </div>
    </li>
</ul>

<?php echo $content_children; ?>

<script type="text/javascript">
// <![CDATA[
  new SiteMap('site-map', [1]);
// ]]>
</script>
