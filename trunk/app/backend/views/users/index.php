<?php if (is_array($users)) { ?> 
<?php use_helper('page'); ?>
<table id="users" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th>Name / Username</th>
      <th>Email</th>
      <th>Roles</th>
      <th>Option</th>
    </tr>
  </thead>
  <tbody>
<?php 
foreach($users as $user) { 
?> 
    <tr class="node <?php echo even_odd() ?>">
      <td class="user">
        <a href="<?php echo get_url('users', 'edit', $user->id) ?>"><?php echo $user->name ?></a>
        <small><?php echo $user->username ?></small>
      </td>
      <td><?php echo $user->email ?></td>
      <td><?php echo $user->name ?></td>
      <td>
<?php if ($user->id > 1) { ?>
        <a href="<?php echo get_url('users', 'delete', $user->id) ?>" onclick="return confirm('<?php echo _('Are you sure you wish to delete it?') ?>');"><img alt="remove user" src="images/remove.png" /></a>
<?php } else { ?>
        <img alt="remove user" src="images/remove-disabled.png" />
<?php } ?>
      </td>
    </tr>
<?php } // foreach ?> 
  </tbody>
</table>

<p><a href="<?php echo get_url('users','add') ?>"><img alt="New User" src="images/new-user.png" /></a></p>

<?php } else {  ?> 
  <p><?php echo _('No user found!') ?></p>
<?php } // if ?> 