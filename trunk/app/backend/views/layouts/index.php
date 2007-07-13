<?php use_helper('page'); ?>
<h1>Layouts</h1>

<p>Use layouts to apply a visual look to a  Web page. Layouts can contain special tags to include
  page content and other elements such as the header or footer. Click on a layout name below to
  edit it or click <strong>Remove</strong> to delete it.</p>

<table id="layouts" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th class="layout">Layout</th>
      <th class="modify">Modify</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($layouts as $layout) { ?>

    <tr class="node <?php echo odd_even() ?>">
      <td class="layout">
        <img align="absmiddle" alt="layout-icon" src="images/layout.png" title="" />
        <a href="<?php echo get_url('layouts', 'edit', $layout->id) ?>"><?php echo $layout->name ?></a>
      </td>
      <td class="remove"><a href="<?php echo get_url('layouts', 'delete', $layout->id) ?>" onclick="return confirm('Are you sure you wish to delete the <?php echo $layout->name ?> layout?');"><img alt="Remove Layout" src="images/remove.png" /></a></td>
    </tr>

<?php } ?>

  </tbody>
</table>

<p><a href="<?php echo get_url('layouts', 'add') ?>"><img alt="Add Layout" src="images/new-layout.png" /></a></p>