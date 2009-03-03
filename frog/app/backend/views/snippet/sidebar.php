<?php if (Dispatcher::getAction() == 'index'): ?>

<p class="button"><a href="<?php echo get_url('snippet/add'); ?>"><img src="app/backend/assets/images/snippet.png" align="middle" alt="snippet icon" /> <?php echo __('New Snippet'); ?></a></p>

<div class="box">
    <h2><?php echo __('What is a Snippet?'); ?></h2>
    <p><?php echo __('Snippets are generally small pieces of content which are included in other pages or layouts.'); ?></p>
</div>
<div class="box">
    <h2><?php echo __('Tag to use this snippet'); ?></h2>
    <p><?php echo __('Just replace <b>snippet</b> by the snippet name you want to include.'); ?></p>
    <p><code>&lt;?php $this->includeSnippet('snippet'); ?&gt;</code></p>
</div>

<?php endif; ?>