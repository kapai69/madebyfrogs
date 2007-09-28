<?php

define('CORE_ROOT', dirname(__FILE__).'/../');

$config_file = '../config/config.php';

//include '../env/functions/general.php';
include 'Template.php';
include 'sql.functions.php';

if (file_exists($config_file)) {
  include $config_file;
}

$msg = '';

// lets install this nice little CMS
if ( ! defined('DEBUG') && isset($_POST['commit']) && ((file_exists($config_file) && is_writable($config_file)) || is_writable('../config'))) {
 
    $config_tmpl = new Template('config.tmpl');
    
    // add type of database manualy
    $_POST['config']['db_type'] = 'mysql'; 
    
    $config_tmpl->assign($_POST['config']);
    $config_content = $config_tmpl->fetch();
    
    $schemas_content = file_get_contents('structure.sql');
    $schemas_content = str_replace('{TABLEPREFIX}', $_POST['config']['table_prefix'], $schemas_content);
    $schemas_content = str_replace('{DATETIME}', date('Y-m-d H:i:s'), $schemas_content);
    
    file_put_contents($config_file, $config_content);
    $msg .= "<p>config file writen with succes!</p>\n";
    
    include $config_file;
    
    $conn = mysql_connect($_POST['config']['db_host'], DB_USER, DB_PASS);
    if (mysql_select_db($_POST['config']['db_name'])) {
        $msg .= "<p>connexion with succes to database</p>\n";
        sql_import_content($schemas_content);
        $admin_url = str_replace('index.php', '', $_SERVER['SCRIPT_URI']).'../admin/';
        $msg .= "<p>tables loaded with succes! you can login with: <br /><strong>login</strong>: admin<br /><strong>password</strong>: password<br />
        <strong>at</strong>: <a href=\"$admin_url\">login page</a></p>\n";
    } else {
        $msg .= "<p>unable to connect the database! tables not loaded, you need to load structure.sql manualy!</p>\n";
    }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Frog - Install</title>
    <link href="../admin/stylesheets/admin.css" media="screen" rel="Stylesheet" type="text/css" />
  </head>
  <body>
    <div id="header">
      <div id="site-title">Frog</div>
      <div id="site-subtitle">Publishing for Small Teams</div>
    </div>
    </div>
    <hr class="hidden" />
    <div id="main">
      <div id="content">
              <!-- Content -->
        <h1>Frog Installation</h1>

<p style="color: red">
<?php if (file_exists($config_file) && ! is_writable($config_file)) { ?>
  <strong>error</strong>: config/config.php must be writable<br />
<?php } else if ( ! file_exists($config_file) && ! is_writable('../config')) { ?>
  <strong>error</strong>: config/ must be writable<br /> 
<?php } ?>
<?php if (!is_writable('../public/')) { ?>
  <strong>error</strong>: public/ must be writable<br />
<?php } ?>
</p>

<?php if (!defined('DEBUG')) { ?>
<form action="index.php" method="post">
  <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><h3>Site information</h3></td>
    </tr>
    <tr>
      <td class="label"><label for="config_language">Language</label></td>
      <td class="field">
        <select class="select" id="config_language" name="config[language]">
          <option value="en">English</option>
          <option value="fr">Français</option>
        </select>
      </td>
      <td class="help">Required. This will set your language for Frog admin only. Installation script is only in english.</td>
    </tr>
    <tr>
      <td class="label"><label class="optional" for="config_admin_site_title">Admin Site title</label></td>
      <td class="field"><input class="textbox" id="config_admin_site_title" maxlength="40" name="config[admin_title]" size="40" type="text" value="Frog" /></td>
      <td class="help">Optional. Changes the title shown in backend.</td>
    </tr>
    <tr>
      <td class="label"><label class="optional" for="config_admin_site_subtitle">Admin Site subtitle</label></td>
      <td class="field"><input class="textbox" id="config_admin_site_subtitle" maxlength="40" name="config[admin_subtitle]" size="40" type="text" value="Publishing for Small Teams" /></td>
      <td class="help">Optional. Changes the subtitle shown in backend.</td>
    </tr>
    <tr>
      <td colspan="3"><h3>Database information</h3></td>
    </tr>
    <tr>
      <td class="label"><label for="use_pdo-yes">Use <a href="http://php.net/pdo" target="_blank">PDO</a></label></td>
      <td class="field">
        <input class="radio" id="use_pdo-yes" name="config[use_pdo]" size="10" type="radio" value="1" checked="checked" /><label for="use_pdo-yes"> yes </label>
        <input class="radio" id="use_pdo-no" name="config[use_pdo]" size="10" type="radio" value="0" /><label for="use_pdo-no"> no </label>
      </td>
      <td class="help">Required. If you have PDO with MySQL driver installed in your server, I recommend you to use it.</td>
    </tr>
    <tr>
      <td class="label"><label for="user_name">Database server</label></td>
      <td class="field"><input class="textbox" id="user_name" maxlength="100" name="config[db_host]" size="100" type="text" value="localhost" /></td>
      <td class="help">Required.</td>
    </tr>
    <tr>
      <td class="label"><label for="config_db_user">Database user</label></td>
      <td class="field"><input class="textbox" id="config_db_user" maxlength="255" name="config[db_user]" size="255" type="text" value="root" /></td>

      <td class="help">Required.</td>
    </tr>
    <tr>
      <td class="label"><label class="optional" for="config_db_pass">Database password</label></td>
      <td class="field"><input class="textbox" id="config_db_pass" maxlength="40" name="config[db_pass]" size="40" type="password" value="" /></td>
      <td class="help">Optional. If no password let's it blank.</td>
    </tr>
    <tr>
      <td class="label"><label for="config_db_name">Database name</label></td>
      <td class="field"><input class="textbox" id="config_db_name" maxlength="40" name="config[db_name]" size="40" type="text" value="frog" /></td>
      <td class="help">You have to create a database manualy and then put the name here.</td>
    </tr>
    <tr>
      <td class="label"><label class="optional" for="config_table_prefix">Table prefix</label></td>
      <td class="field"><input class="textbox" id="user_confirm" maxlength="40" name="config[table_prefix]" size="40" type="text" value="" /></td>
      <td class="help">Optional. To prevent possible tables doubled.</td>
    </tr>
  </table>

  <p class="buttons">
    <button class="button" name="commit" type="submit"> Install now </button>
  </p>

</form>
<?php } else { ?>
  <?php echo $msg; ?>
  <p><strong>Frog</strong> is installed, you must delete install dir now</p>
<?php } ?>
        <!-- /Content -->
      </div>
    </div>
    <hr class="hidden" />
    <div id="footer">
      <p>
        This site was made with PHP and is powered by Frog
      </p>
    </div>
  </body>
</html>