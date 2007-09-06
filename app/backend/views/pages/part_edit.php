<div style="display: none;" class="page" id="page-<?php echo $index ?>">
  <div class="part" id="part-<?php echo $index ?>">
    <input id="part[<?php echo ($index-1) ?>][name]" name="part[<?php echo ($index-1) ?>][name]" type="hidden" value="<?php echo $name ?>" />
    <p>
      <label for="part[<?php echo ($index-1) ?>][filter_id]"><?php echo __('Filter') ?></label>
      <select id="part[<?php echo ($index-1) ?>][filter_id]" name="part[<?php echo ($index-1) ?>][filter_id]">
        <option value=""<?php if(isset($filter_id) && $filter_id == '') echo ' selected'; ?>>&#8212; <?php echo __('none') ?> &#8212;</option>
<?php foreach ($filters as $filter): ?>
        <option value="<?php echo $filter ?>"<?php if($filter_id == $filter) echo ' selected="selected"'; ?>><?php echo $filter ?></option>
<?php endforeach; ?>
</select>
    </p>
    <div><textarea class="textarea" id="part[<?php echo ($index-1) ?>][content]" name="part[<?php echo ($index-1) ?>][content]" style="width: 100%"><?php if(isset($content)) echo htmlentities($content, ENT_COMPAT, 'UTF-8'); ?></textarea></div>
  </div>
</div>