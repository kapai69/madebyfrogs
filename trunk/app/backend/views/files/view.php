<?php
  $out = '';
  $progres_path = '';
  $paths = explode('/', $filename); 
  $nb_path = count($paths);
  foreach ($paths as $i => $path) {
    if ($i+1 == $nb_path) {
      $out .= $path;
    } else {
      $progres_path .= $path.'/';
      $out .= '<a href="'.get_url('files', 'browse', $progres_path).'">'.$path.'</a>/';
    }
  }
?>
<h1><a href="<?php echo get_url('files'); ?>">public</a>/<?php echo $out; ?></h1>
<?php if ($is_image) { ?>
  <img src="<?php echo BASE_FILES_DIR.'/'.$filename; ?>" />
<?php } else { ?>
<form method="post" action="<?php echo get_url('files', 'save'); ?>">
  <input type="hidden" name="file[name]" value="<?php echo $filename; ?>" />
  <textarea class="textarea" id="file_content" name="file[content]" style="width: 100%; height: 400px;"><?php echo htmlentities($content, ENT_COMPAT, 'UTF-8') ?></textarea><br />
  <p><input type="submit" name="save" accesskey="s" value="Save (Alt+S)" />
   or <a href="<?php echo get_url('files', 'browse', $progres_path); ?>">Cancel</a>
  </p>
</form>
<?php } ?>