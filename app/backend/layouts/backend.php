<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php echo ADMIN_TITLE ?> - <?php echo ADMIN_SUBTITLE ?></title>
    
    <base href="<?php echo trim(BASE_URL, '?/').'/'; ?>" />
    <link href="stylesheets/admin.css" media="screen" rel="Stylesheet" type="text/css" />
    <link href="stylesheets/markdown.css" media="screen" rel="Stylesheet" type="text/css" />
    
    <script src="javascripts/prototype.js" type="text/javascript"></script>
    <script src="javascripts/string.js" type="text/javascript"></script>
    <script src="javascripts/effects.js" type="text/javascript"></script>
    <script src="javascripts/controls.js" type="text/javascript"></script>
    <script src="javascripts/dragdrop.js" type="text/javascript"></script>
    <script src="javascripts/tabcontrol.js" type="text/javascript"></script>
    <script src="javascripts/ruledtable.js" type="text/javascript"></script>
    <script src="javascripts/ruledlist.js" type="text/javascript"></script>
    <script src="javascripts/sitemap.js" type="text/javascript"></script>
    <script src="javascripts/pages.js" type="text/javascript"></script>
    <script src="javascripts/control.textarea.2.0.0.RC1.js" type="text/javascript"></script>
    <script src="javascripts/control.textarea.markdown.js" type="text/javascript"></script>

    <!--[if lt IE 7]>
    <script defer type="text/javascript" src="javascripts/pngfix.js"></script>
    <![endif]-->

  </head>
  <body>
    <div id="header">
      <div id="site-title"><a href="<?php echo get_url() ?>"><?php echo ADMIN_TITLE ?></a></div>
      <div id="site-subtitle"><?php echo ADMIN_SUBTITLE ?></div>
      <div id="navigation">
<?php $ctrl = Dispatcher::getController(); if ($ctrl == 'page') { ?>
        <strong><a href="<?php echo get_url('page') ?>"><?php echo __('Pages') ?></a></strong> <span class="separator"> | </span>
<?php } else { ?>
        <a href="<?php echo get_url('page') ?>"><?php echo __('Pages') ?></a> <span class="separator"> | </span>
<?php } ?>
<?php if ($ctrl == 'snippet') { ?>
        <strong><a href="<?php echo get_url('snippet') ?>"><?php echo __('Snippets') ?></a></strong><span class="separator"> | </span>
<?php } else { ?>
        <a href="<?php echo get_url('snippet') ?>"><?php echo __('Snippets') ?></a></strong><span class="separator"> | </span>
<?php } ?>
<?php if ($ctrl == 'layout') { ?>
        <strong><a href="<?php echo get_url('layout') ?>"><?php echo __('Layouts') ?></a></strong><span class="separator"> | </span>
<?php } else { ?>
        <a href="<?php echo get_url('layout') ?>"><?php echo __('Layouts') ?></a></strong><span class="separator"> | </span>
<?php } ?>
<?php if ($ctrl == 'file') { ?>
        <strong><a href="<?php echo get_url('file') ?>"><?php echo __('Files') ?></a></strong><span class="separator"> | </span>
<?php } else { ?>
        <a href="<?php echo get_url('file') ?>"><?php echo __('Files') ?></a></strong><span class="separator"> | </span>
<?php } ?>
      </div>
    </div>
    </div>
    <hr class="hidden" />
    <div id="main">
      <div id="content">    
<?php if (Flash::get('error') !== null) { ?>
        <div id="error" onclick="this.style.display = 'none'"><?php echo Flash::get('error') ?></div>
<?php } ?>
<?php if (Flash::get('success') !== null) { ?>
        <div id="success" onclick="this.style.display = 'none'"><?php echo Flash::get('success') ?></div>
<?php } ?>
        <!-- Content -->
        <?php echo $content_for_layout ?>
        <!-- /Content -->
      </div>
    </div>
    <hr class="hidden" />
    <div id="footer">
      <p>
      <?php echo __('This site was made with PHP and is powered by') ?> <a href="http://www.philworks.com/frog/">Frog</a> <?php echo __('version') ?> <?php echo FROG_VERSION ?><br />
<?php if (DEBUG) { ?>
      <?php echo __('Page rendered in') ?> <?php echo execution_time() ?> <?php echo __('seconds') ?><br />
      <?php echo __('Memory usage:') ?> <?php echo memory_usage() ?>
<?php } ?>
      </p>

      <p id="site-links">
          <a href="<?php echo get_url('user') ?>"><?php echo __('Users') ?></a>
          <span class="separator"> | </span>
          <a href="<?php echo get_url('login/logout') ?>"><?php echo __('Log Out') ?></a>
          <span class="separator"> | </span>
          <a href="<?php echo substr(BASE_URL, 0, strrpos(BASE_URL, 'a')); ?>"><?php echo __('View Site') ?></a>
      </p>
    </div>
  </body>
</html>