<?php
/*
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * The Comment plugin provides an interface to enable adding and moderating page comments.
 *
 * @package frog
 * @subpackage plugin.comment
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Bebliuc George <bebliuc.george@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 1.2.0
 * @since Frog version 0.9.3
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault, Bebliuc George & Martijn van der Kleijn, 2008
 */
?>
<h1><?php echo __('Edit comment'); ?></h1>

<form action="<?php echo get_url('plugin/comment/edit/'.$comment->id); ?>" method="post">
  <div class="form-area">
    <p class="content">
      <label for="comment_body"><?php echo __('Body'); ?></label>
      <textarea class="textarea" cols="40" id="comment_body" name="comment[body]" rows="20" style="width: 100%" onkeydown="return allowTab(event, this);"><?php echo htmlentities($comment->body, ENT_COMPAT, 'UTF-8'); ?></textarea>
    </p>
  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/comment'); ?>"><?php echo __('Cancel'); ?></a>
  </p>
</form>
