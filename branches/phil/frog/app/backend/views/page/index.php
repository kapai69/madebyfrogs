<h1><?php echo __('Pages'); ?></h1>

<?php $languages = explode(',', Setting::get('backend_language')); ?>
<?php if (count($languages) > 1): ?>
<form id="form-backend_language" action="<?php echo get_url('page/language') ?>" method="post">
	<label for="setting-backend_language"><?php echo __('Language'); ?></label>
	<select id="setting-backend_language" name="language" onchange="$('form-backend_language').submit();">
<?php foreach ($languages as $language): ?>
		<option value="<?php echo $language; ?>"<?php if($language == $current_language) echo ' selected="selected"'; ?>><?php echo Language::nameOf($language); ?></option>
<?php endforeach; ?>
	</select>
</form>
<?php endif; ?>

<?php if ($root): ?>
<div id="site-map-def">
    <div class="page"><?php echo __('Page'); ?> (<a href="#" onclick="toggle_handle = !toggle_handle; $$('.handle').each(function(e) { e.style.display = toggle_handle ? 'inline': 'none'; }); return false;"><?php echo __('reorder'); ?></a>)</div>
    <div class="status"><?php echo __('Status'); ?></div>
    <div class="modify"><?php echo __('Modify'); ?></div>
</div>

<ul id="site-map-root">
    <li id="page-0" class="node level-0">
      <div class="page" style="padding-left: 4px">
        <span class="w1">
<?php if ($root->is_protected && !AuthUser::hasPermission('administrator') && !AuthUser::hasPermission('developer')): ?>
          <img align="middle" class="icon" src="app/backend/assets/images/page.png" alt="page icon" /> <span class="title"><?php echo $root->title; ?></span>
<?php else: ?>
          <a href="<?php echo get_url('page/edit/'.$root->id); ?>" title="/<?php echo $root->slug; ?>"><img align="middle" class="icon" src="app/backend/assets/images/page.png" alt="page icon" /> <span class="title"><?php echo $root->title; ?></span></a>
<?php endif; ?>
        </span>
      </div>
      <div class="status published-status"><?php echo __('Published'); ?></div>
      <div class="modify">
          <a href="<?php echo get_url('page/add/'.$root->id); ?>"><img src="app/backend/assets/images/plus.png" align="middle" alt="<?php echo __('Add child'); ?>" /></a>&nbsp; 
          <img class="remove" src="app/backend/assets/images/icon-remove-disabled.gif" align="middle" alt="remove icon disabled" />
      </div>
    </li>
</ul>

<?php echo $content_children; ?>
<?php else: ?>
<a href="<?php echo get_url('page/add'); ?>"><?php echo __('Create a new site for this language'); ?></a>
<?php endif; ?>
