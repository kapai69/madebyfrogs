<?php
  use_helper('page');

  $out = '';
  $progres_path = '';
  $paths = explode('/', $dir); 
  $nb_path = count($paths)-1; // -1 to didn't display current dir as a link
  foreach ($paths as $i => $path) {
    if ($i+1 == $nb_path) {
      $out .= $path;
    } else if ($path != '') {
      $progres_path .= $path.'/';
      $out .= '<a href="'.get_url('files', $progres_path).'">'.$path.'</a>/';
    }
  }
?>
<h1><a href="<?php echo get_url('files'); ?>">public</a>/<?php echo $out; ?></h1>
<table id="files-list" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th class="files">File</th>
      <th class="size">Size</th>
      <th class="permissions">Permissions</th>
      <th class="mtime">Modification</th>
      <th class="modify">Action</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($files as $file) { ?>
    <tr class="<?php echo even_odd() ?>">
      <td><?php echo $file->link ?></td>
      <td><code><?php echo $file->size ?></code></td>
      <td><code><?php echo $file->perms ?></code> <a href="javascript: toggle_chmod_popup('<?php echo $dir.$file->name; ?>')" alt="Change mode">chmod</a></td>
      <td><code><?php echo $file->mtime ?></code></td>
      <td>
        <a href="<?php echo get_url('files', 'delete', $dir.$file->name); ?>" onclick="return confirm('Are you sure you wish to delete <?php echo $file->name ?>?');"><img alt="remove file" src="images/remove.png" /></a>
      </td>
    <tr>
<?php } // foreach ?>
  </tbody>
</table>
<p>
  <form method="post" action="<?php echo get_url('files', 'upload', $dir); ?>">
    <input type="button" onclick="toggle_popup('create-file-popup', 'create_file_name');" value="Create new file" />
    <input type="button" onclick="toggle_popup('create-directory-popup', 'create_directory_name');" value="Create new directory" />
    <input type="button" onclick="toggle_popup('upload-file-popup', 'upload_file');" value="Upload file" /><br />
  </form>
</p>

<div id="popups">
  <div class="popup" id="create-file-popup" style="display:none;">
    <h3>Create new file</h3>
    <form action="<?php echo get_url('files', 'create_file') ?>" method="post"> 
      <div>
        <input id="create_file_path" name="file[path]" type="hidden" value="<?php echo ($dir == '') ? '/': $dir; ?>" />
        <input id="create_file_name" maxlength="255" name="file[name]" type="text" value="" /> 
        <input id="create_file_button" name="commit" type="submit" value="Create file" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('create-file-popup'); return false;">Close</a></p>
    </form>
  </div>
  <div class="popup" id="create-directory-popup" style="display:none;">
    <h3>Create new directory</h3>
    <form action="<?php echo get_url('files', 'create_directory') ?>" method="post"> 
      <div>
        <input id="create_directory_path" name="directory[path]" type="hidden" value="<?php echo ($dir == '') ? '/': $dir; ?>" />
        <input id="create_directory_name" maxlength="255" name="directory[name]" type="text" value="" /> 
        <input id="file_button" name="commit" type="submit" value="Create dir" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('create-directory-popup'); return false;">Close</a></p>
    </form>
  </div>
  <div class="popup" id="upload-file-popup" style="display:none;">
    <form action="<?php echo get_url('files', 'upload') ?>" method="post" enctype="multipart/form-data"> 
      <h3>Upload file &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input id="upload_overwrite" name="upload[overwrite]" type="checkbox" value="1" /><label for="upload_overwrite"><small>overwrite it?</small></label></h3>
      <div>
        <input id="upload_path" name="upload[path]" type="hidden" value="<?php echo ($dir == '') ? '/': $dir; ?>" />
        <input id="upload_file" name="upload_file" type="file" />
        <input id="upload_file_button" name="commit" type="submit" value="Upload" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('upload-file-popup'); return false;">Close</a></p>
    </form>
  </div>
  <div class="popup" id="chmod-popup" style="display:none;">
    <h3>Change mode</h3>
    <form action="<?php echo get_url('files', 'chmod') ?>" method="post"> 
      <div>
        <input id="chmod_file_name" name="file[name]" type="hidden" value="" />
        <input id="chmod_file_mode" maxlength="4" name="file[mode]" type="text" value="" /> 
        <input id="chmod_file_button" name="commit" type="submit" value="Change mode" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('chmod-popup'); return false;">Close</a></p>
    </form>
  </div>
</div>