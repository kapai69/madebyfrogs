<h1><?php echo __(camelize($action).' User') ?></h1>

<form action="<?php echo $action=='edit' ? get_url('users/edit/'.$user->id): get_url('users/add');  ?>" method="post">
  <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
    <tr>

      <td class="label"><label for="user_name"><?php echo __('Name') ?></label></td>
      <td class="field"><input class="textbox" id="user_name" maxlength="100" name="user[name]" size="100" type="text" value="<?php echo $user->name ?>" /></td>
      <td class="help"><?php echo __('Required.') ?></td>
    </tr>
    <tr>
      <td class="label"><label class="optional" for="user_email"><?php echo __('E-mail') ?></label></td>
      <td class="field"><input class="textbox" id="user_email" maxlength="255" name="user[email]" size="255" type="text" value="<?php echo $user->email ?>" /></td>

      <td class="help"><?php echo __('Optional. Please use a valid e-mail address.') ?></td>
    </tr>
    <tr>
      <td class="label"><label for="user_login"><?php echo __('Username') ?></label></td>
      <td class="field"><input class="textbox" id="user_username" maxlength="40" name="user[username]" size="40" type="text" value="<?php echo $user->username ?>" /></td>
      <td class="help"><?php echo __('At least 3 characters. Must be unique.') ?></td>
    </tr>

    <tr>
      <td class="label"><label for="user_password"><?php echo __('Password') ?></label></td>
      <td class="field"><input class="textbox" id="user_password" maxlength="40" name="user[password]" size="40" type="password" value="" /></td>
      <td class="help" rowspan="2"><?php echo __('At least 5 characters.') ?> <?php if($action=='edit') { echo __('Leave password blank for it to remain unchanged.'); } ?></td>
    </tr>
    <tr>
      <td class="label"><label for="user_confirm"><?php echo __('Confirm Password') ?></label></td>

      <td class="field"><input class="textbox" id="user_confirm" maxlength="40" name="user[confirm]" size="40" type="password" value="" /></td>
    </tr>
    <tr>
      <td class="label"><label for="user_is_admin"><?php echo __('Roles') ?></label></td>
      <td class="field">
        <span class="checkbox"><input<?php if (isset($user->is_admin) && $user->is_admin) echo ' checked="checked"'; ?>  id="user_is_admin" name="user[is_admin]" type="checkbox" value="1" />&nbsp;<label for="user_is_admin"><?php echo __('Administrator') ?></label></span>
        <span class="checkbox"><input<?php if (isset($user->is_developer) && $user->is_developer) echo ' checked="checked"'; ?> id="user_is_developer" name="user[is_developer]" type="checkbox" value="1" />&nbsp;<label for="user_is_developer"><?php echo __('Developer') ?></label></span>

      </td>
      <td class="help"><?php echo __('Roles restrict user privileges and turn parts of the administrative interface on or off.') ?></td>
    </tr>
  </table>

  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save') ?> (Alt+S)" />
    <?php echo __('or') ?> <a href="<?php echo get_url('users'); ?>"><?php echo __('Cancel') ?></a>
  </p>

</form>

<script type="text/javascript">
// <![CDATA[
Field.activate('user_name');
// ]]>
</script>
