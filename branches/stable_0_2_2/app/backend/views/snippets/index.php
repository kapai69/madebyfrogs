<?php use_helper('page'); ?>

<h1><?php echo __('Snippets') ?></h1>

<p><?php echo __('Snippets are generally small pieces of content which are included in other pages or layouts.') ?></p>

<table id="snippets" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th class="snippet"><?php echo __('Snippet') ?></th>
      <th class="modify"><?php echo __('Modify') ?></th>
    </tr>
  </thead>
  <tbody>
<?php foreach($snippets as $snippet): ?>

    <tr class="node <?php echo odd_even() ?>">
      <td class="snippet">
        <img align="absmiddle" alt="snippet-icon" src="images/snippet.png" title="" />
        <a href="<?php echo get_url('snippets/edit/'.$snippet->id) ?>"><?php echo $snippet->name ?></a>
      </td>
      <td class="remove"><a href="<?php echo get_url('snippets/delete/'.$snippet->id) ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete') ?> <?php echo $snippet->name ?>?');"><img alt="<?php echo __('Remove Snippet') ?>" src="images/remove.png" /></a></td>
    </tr>

<?php endforeach; ?>

  </tbody>
</table>

<p><a href="<?php echo get_url('snippets/add') ?>"><img alt="<?php echo __('Add Snippet') ?>" src="images/new-snippet.png" /></a></p>