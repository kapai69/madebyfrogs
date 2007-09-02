<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php echo ADMIN_TITLE ?> - <?php echo ADMIN_SUBTITLE ?></title>
    
    <base href="<?php echo substr(BASE_URL, -1, 1) == '?' ? substr(BASE_URL, 0, strlen(BASE_URL)-1): BASE_URL.'/'; ?>" />
    <link href="stylesheets/admin.css" media="screen" rel="Stylesheet" type="text/css" />

    <script src="javascripts/prototype.js" type="text/javascript"></script>
    <script src="javascripts/string.js" type="text/javascript"></script>
    <script src="javascripts/effects.js" type="text/javascript"></script>
    <script src="javascripts/controls.js" type="text/javascript"></script>
    <script src="javascripts/dragdrop.js" type="text/javascript"></script>
    <script src="javascripts/tabcontrol.js" type="text/javascript"></script>
    <script src="javascripts/ruledtable.js" type="text/javascript"></script>
    <script src="javascripts/ruledlist.js" type="text/javascript"></script>
    <script src="javascripts/sitemap.js?021" type="text/javascript"></script>
<?php echo include_javascript() ?>

    <!--[if lt IE 7]>
    <script defer type="text/javascript" src="javascripts/pngfix.js"></script>
    <![endif]-->

  </head>
  <body>
    <div id="header">
      <div id="site-title"><a href="<?php echo get_url() ?>"><?php echo ADMIN_TITLE ?></a></div>
      <div id="site-subtitle"><?php echo ADMIN_SUBTITLE ?></div>
      <div id="navigation">
<?php $ctrl = get_controller(); if ($ctrl == 'pages') { ?>
        <strong><a href="<?php echo get_url('pages') ?>">Pages</a></strong> <span class="separator"> | </span>
<?php } else { ?>
        <a href="<?php echo get_url('pages') ?>">Pages</a> <span class="separator"> | </span>
<?php } ?>
<?php if ($ctrl == 'snippets') { ?>
        <strong><a href="<?php echo get_url('snippets') ?>">Snippets</a></strong><span class="separator"> | </span>
<?php } else { ?>
        <a href="<?php echo get_url('snippets') ?>">Snippets</a></strong><span class="separator"> | </span>
<?php } ?>
<?php if ($ctrl == 'layouts') { ?>
        <strong><a href="<?php echo get_url('layouts') ?>">Layouts</a></strong><span class="separator"> | </span>
<?php } else { ?>
        <a href="<?php echo get_url('layouts') ?>">Layouts</a></strong><span class="separator"> | </span>
<?php } ?>
<?php if ($ctrl == 'files') { ?>
        <strong><a href="<?php echo get_url('files') ?>">Files</a></strong><span class="separator"> | </span>
<?php } else { ?>
        <a href="<?php echo get_url('files') ?>">Files</a></strong><span class="separator"> | </span>
<?php } ?>
      </div>
    </div>
    </div>
    <hr class="hidden" />
    <div id="main">
      <div id="content">
      
      <?php if (flash_get('error') !== '') { ?>
        <div id="error" onclick="this.style.display = 'none'"><?php echo flash_get('error') ?></div>
<?php } ?>
<?php if (flash_get('success') !== '') { ?>
        <div id="success" onclick="this.style.display = 'none'"><?php echo flash_get('success') ?></div>
<?php } ?>
        <!-- Content -->
        <?php echo $content_for_layout ?>
        <!-- /Content -->
      </div>
    </div>
    <hr class="hidden" />
    <div id="footer">
      <p>
      This site was made with PHP and is powered by <a href="http://www.philworks.com/frog/">Frog</a> version <?php echo FROG_VERSION ?><br />
<?php if (DEBUG) { ?>
      Page rendered in <?php echo execution_time() ?> seconds<br />
      Memory usage: <?php echo memory_usage() ?>
<?php } // if ?>
      </p>

      <p id="site-links">
          <a href="<?php echo get_url('users') ?>">Users</a>
          <span class="separator"> | </span>
          <a href="<?php echo get_url('logout') ?>">Log Out</a>
          <span class="separator"> | </span>
          <a href="<?php echo substr(BASE_URL, 0, strrpos(BASE_URL, 'a')); ?>" target="_blank">View Site</a>
      </p>
    </div>
  </body>
</html>