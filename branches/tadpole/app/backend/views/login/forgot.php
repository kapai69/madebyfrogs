<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
  <title><?php echo _('Forgot password') ?></title>
  <link href="stylesheets/login.css" rel="Stylesheet" type="text/css" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
  <div id="dialog">
    <h1><?php echo _('Forgot password') ?></h1>
<?php if (flash_get('error') !== '') { ?>
    <div id="error" onclick="this.style.display = 'none'"><?php echo flash_get('error') ?></div>
<?php } ?>
<?php if (flash_get('success') !== '') { ?>
    <div id="success" onclick="this.style.display = 'none'"><?php echo flash_get('success') ?></div>
<?php } ?>
    <form action="<?php echo get_url('login', 'forgot') ?>" method="post">
      <div>
        <label for="forgot_email"><?php echo _('Email address'); ?>:</label>
        <input class="long" id="forgot_email" type="text" name="forgot[email]" value="" />
      </div>
      <div id="forgot_submit">
        <input class="submit" type="submit" accesskey="s" value="<?php echo _('Send password'); ?> (Alt+S)" />
        <span>(<a href="<?php echo get_url('login') ?>"><?php echo _('Login'); ?></a>)</span>
      </div>
    </form>
  </div>
</body>
</html>