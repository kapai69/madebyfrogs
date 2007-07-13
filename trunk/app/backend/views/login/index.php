<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
  <title><?php echo _('Login') ?></title>
  <link href="stylesheets/login.css" rel="Stylesheet" type="text/css" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
  <div id="dialog">
    <h1><?php echo _('Login') ?></h1>
<?php if (flash_get('error') !== '') { ?>
        <div id="error" onclick="this.style.display = 'none'"><?php echo flash_get('error') ?></div>
<?php } ?>
<?php if (flash_get('success') !== '') { ?>
        <div id="success" onclick="this.style.display = 'none'"><?php echo flash_get('success') ?></div>
<?php } ?>
    <form action="<?php echo get_url('login', 'login') ?>" method="post">
      <div id="login_username_div">
        <label for="login_username"><?php echo _('Username') ?>:</label>
        <input id="login_username" class="medium" type="text" name="login[username]" value="" />
      </div>
      <div id="login_password_div">
        <label for="login_password"><?php echo _('Password') ?>:</label>
        <input id="login_password" class="medium" type="password" name="login[password]" value="" />
      </div>
      <div class="clean"></div>
      <div style="margin-top: 6px">
        <input id="login_remember_me" type="checkbox" class="checkbox" name="login[remember]" value="checked" />
        <label class="checkbox" for="login_remember_me"><?php echo _('Remember me for 14 days') ?></label>
      </div>
      <div id="login_submit">
        <input class="submit" type="submit" accesskey="s" value="<?php echo _('Login') ?> (Alt+S)" />
        <span>(<a href="<?php echo get_url('login', 'forgot') ?>"><?php echo _('Forgot password?') ?></a>)</span>
      </div>
    </form>
  </div>
</body>
</html>