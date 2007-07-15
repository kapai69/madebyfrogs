<?php use_helper('page'); ?>

<h1>Snippets</h1>

<p>Snippets are generally small pieces of content which are included in other pages or layouts.</p>

<table id="snippets" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th class="snippet">Snippet</th>
      <th class="modify">Modify</th>
    </tr>
  </thead>
  <tbody>
<?php foreach($snippets as $snippet) { ?>

    <tr class="node <?php echo odd_even() ?>">
      <td class="snippet">
        <img align="absmiddle" alt="snippet-icon" src="images/snippet.png" title="" />
        <a href="<?php echo get_url('snippets', 'edit', $snippet->id) ?>"><?php echo $snippet->name ?></a>
      </td>
      <td class="remove"><a href="<?php echo get_url('snippets', 'delete', $snippet->id) ?>" onclick="return confirm('Are you sure you wish to delete the <?php echo $snippet->name ?> snippet?');"><img alt="Remove Snippet" src="images/remove.png" /></a></td>
    </tr>

<?php } ?>

  </tbody>
</table>

<p><a href="<?php echo get_url('snippets', 'add') ?>"><img alt="Add Snippet" src="images/new-snippet.png" /></a></p>