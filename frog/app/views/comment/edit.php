<?php
/**
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 *
 * This file is part of Frog CMS.
 *
 * Frog CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Frog CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Frog CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Frog CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package frog
 * @subpackage views
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Philippe Archambault, 2008
 */
?>
<h1><?php echo __('Edit comment'); ?></h1>

<form action="<?php echo get_url('comment/edit/'.$comment->id); ?>" method="post">
  <div class="form-area">
    <p class="content">
      <label for="comment_body"><?php echo __('Body'); ?></label>
      <textarea class="textarea" cols="40" id="comment_body" name="comment[body]" rows="20" style="width: 100%" onkeydown="return allowTab(event, this);"><?php echo htmlentities($comment->body, ENT_COMPAT, 'UTF-8'); ?></textarea>
    </p>
  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    <?php echo __('or'); ?> <a href="<?php echo get_url('comment'); ?>"><?php echo __('Cancel'); ?></a>
  </p>
</form>
