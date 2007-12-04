<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php echo Setting::get('admin_title') . ' - ' . ucfirst($ctrl = Dispatcher::getController()); ?></title>
    
    <base href="<?php echo trim(BASE_URL, '?/').'/'; ?>" />
    <link href="stylesheets/admin.css" media="screen" rel="Stylesheet" type="text/css" />
    <link href="stylesheets/toolbar.css" media="screen" rel="Stylesheet" type="text/css" />
    
    <script src="javascripts/cp-protolous.js" type="text/javascript"></script>
    <script src="javascripts/cp-datepicker.js" type="text/javascript"></script>
    <script src="javascripts/frog.js" type="text/javascript"></script>
    <script src="javascripts/control.textarea.js" type="text/javascript"></script>
    <script src="javascripts/control.textarea.default.js" type="text/javascript"></script>
    
    <!-- filters files -->
<?php foreach(Filter::findAll() as $filter_id): ?>
<?php $filter_dir = Inflector::underscore($filter_id); ?>
<?php if (file_exists(CORE_ROOT . '/filters/' . $filter_dir . '/' . $filter_id . '.js')): ?>
    <script src="../frog/filters/<?php echo $filter_dir.'/'.$filter_id ?>.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (file_exists(CORE_ROOT . '/filters/' . $filter_dir . '/' . $filter_id . '.css')): ?>
    <link href="../frog/filters/<?php echo $filter_dir.'/'.$filter_id ?>.css" media="screen" rel="Stylesheet" type="text/css" />
<?php endif; ?>
<?php endforeach; ?>
    <!-- end filters files -->
    
  </head>
  <body id="body_<?php echo $ctrl.'_'.Dispatcher::getAction() ?>">
    <div id="header">
      <div id="site-title"><a href="<?php echo get_url() ?>"><?php echo Setting::get('admin_title') ?></a></div>
      <div id="mainTabs">
        <ul>
          <li><a href="<?php echo get_url('page') ?>"<?php if ($ctrl=='page') echo ' class="current"'; ?>><?php echo __('Pages') ?></a></li>
<?php if (AuthUser::hasPermission('administrator') || AuthUser::hasPermission('developer') ): ?> 
          <li><a href="<?php echo get_url('snippet') ?>"<?php if ($ctrl=='snippet') echo ' class="current"'; ?>><?php echo __('Snippets') ?></a></li>
          <li><a href="<?php echo get_url('layout') ?>"<?php if ($ctrl=='layout') echo ' class="current"'; ?>><?php echo __('Layouts') ?></a></li>
<?php endif; ?>

<?php if (AuthUser::hasPermission('administrator')): ?> 
          <li class="right"><a href="<?php echo get_url('setting') ?>"<?php if ($ctrl=='setting') echo ' class="current"'; ?>><?php echo __('Settings') ?></a></li>
          <li class="right"><a href="<?php echo get_url('user') ?>"<?php if ($ctrl=='user') echo ' class="current"'; ?>><?php echo __('Users') ?></a></li>
<?php endif; ?>
<?php if (Setting::get('enable_comment')): ?> 
          <li class="right"><a href="<?php echo get_url('comment') ?>"<?php if ($ctrl=='comment') echo ' class="current"'; ?>><?php echo __('Comments') ?></a></li>
<?php endif; ?>
<?php if (Setting::get('display_file_manager')): ?> 
          <li class="right"><a href="<?php echo get_url('file') ?>"<?php if ($ctrl=='file') echo ' class="current"'; ?>><?php echo __('Files') ?></a></li>
<?php endif; ?>
        </ul>
      </div>
    </div>
    <div id="main">
      <div id="content-wrapper"><div id="content">
<?php if (Flash::get('error') !== null): ?>
        <div id="error" style="display: none"><?php echo Flash::get('error') ?></div>
        <script type="text/javascript">Effect.Appear('error', {duration:.5});</script>
<?php endif; ?>
<?php if (Flash::get('success') !== null): ?>
        <div id="success" style="display: none"><?php echo Flash::get('success') ?></div>
        <script type="text/javascript">Effect.Appear('success', {duration:.5});</script>
<?php endif; ?>
        <!-- content -->
        <?php echo $content_for_layout ?>
        <!-- end content -->
      </div></div>
      <div id="sidebar-wrapper"><div id="sidebar">
          <!-- sidebar -->
          <?php echo isset($sidebar) ? $sidebar: '&nbsp;' ?>
          <!-- end sidebar -->
        </div></div>
    </div>

    <hr class="hidden" />
    <div id="footer">
      <p>
      <?php echo __('Thank you for creating with') ?> <a href="http://www.madebyfrog.com/">Frog CMS</a> <?php echo FROG_VERSION ?> | <a href="http://forum.madebyfrog.com/"><?php echo __('Feedback') ?></a>
      </p>
<?php if (DEBUG): ?>
      <p class="stats"> <?php echo __('Page rendered in') ?> <?php echo execution_time() ?> <?php echo __('seconds') ?>
      | <?php echo __('Memory usage:') ?> <?php echo memory_usage() ?></p>
<?php endif; ?>

      <p id="site-links">
        You are currently logged in as <a href="<?php echo get_url('user/edit/'.AuthUser::getId()) ?>"><?php echo AuthUser::getRecord()->name ?></a>
        <span class="separator"> | </span>
        <a href="<?php echo get_url('login/logout') ?>"><?php echo __('Log Out') ?></a>
        <span class="separator"> | </span>
        <a href="<?php echo substr(BASE_URL, 0, strrpos(BASE_URL, 'a')); ?>"><?php echo __('View Site') ?></a>
      </p>
    </div>
  </body>
</html>