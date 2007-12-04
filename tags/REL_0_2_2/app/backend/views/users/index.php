<?php use_helper('page'); ?>

<h1><?php echo __('Users') ?></h1>

<table id="users" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th><?php echo __('Name') ?> / <?php echo __('Username') ?></th>
      <th><?php echo __('Email') ?></th>
      <th><?php echo __('Roles') ?></th>
      <th><?php echo __('Modify') ?></th>
    </tr>
  </thead>
  <tbody>
<?php foreach($users as $user): ?> 
    <tr class="node <?php echo even_odd() ?>">
      <td class="user">
        <a href="<?php echo get_url('users', 'edit', $user->id) ?>"><?php echo $user->name ?></a>
        <small><?php echo $user->username ?></small>
      </td>
      <td><?php echo $user->email ?></td>
      <td><?php echo $user->name ?></td>
      <td>
<?php if ($user->id > 1): ?>
        <a href="<?php echo get_url('users/delete/'.$user->id) ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete it?') ?>');"><img alt="<?php echo __('Remove user') ?>" src="images/remove.png" /></a>
<?php else: ?>
        <img alt="<?php echo __('Remove user') ?>" src="images/remove-disabled.png" />
<?php endif; ?>
      </td>
    </tr>
<?php endforeach; ?> 
  </tbody>
</table>

<p><a href="<?php echo get_url('users/add') ?>"><img alt="<?php echo __('New User') ?>" src="images/new-user.png" /></a></p>
