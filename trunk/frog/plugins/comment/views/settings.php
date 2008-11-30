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
<h1><?php echo __('Comments Plugin'); ?></h1>

<form action="<?php echo get_url('plugin/comment/save'); ?>" method="post">
    <fieldset style="padding: 0.5em;">
        <legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Comments settings'); ?></legend>
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="label"><label for="autoapprove"><?php echo __('Auto approve'); ?>: </label></td>
                <td class="field">
					<select name="autoapprove">
						<option value="1" <?php if($approve == "1") echo 'selected ="";' ?>><?php echo __('Yes'); ?></option>
						<option value="0" <?php if($approve == "0") echo 'selected ="";' ?>><?php echo __('No'); ?></option>
					</select>	
				</td>
                <td class="help"><?php echo __('Choose yes if you want your comments to be auto approved. Otherwise, they will be placed in the moderation queue.'); ?></td>
            </tr>
            <tr>
                <td class="label"><label for="captcha"><?php echo __('Use captcha'); ?>: </label></td>
                <td class="field">
					<select name="captcha">
						<option value="1" <?php if($captcha == "1") echo 'selected ="";' ?>><?php echo __('Yes'); ?></option>
						<option value="2" <?php if($captcha == "2") echo 'selected ="";' ?>><?php echo __('No'); ?></option>
					</select>	
				</td>
                <td class="help"><?php echo __('Choose yes if you want to use a captcha to protect yourself against spammers.'); ?></td>
            </tr>	
            <tr>
                <td class="label"><label for="rowspage"><?php echo __('Comments per page'); ?>: </label></td>
                <td class="field">
					<input type="text" class="textinput" value="<?php echo $rowspage; ?>" name="rowspage" />
				</td>
                <td class="help"><?php echo __('Sets the number of comments to be displayed per page in the backend.'); ?></td>
        	</tr>	
        </table>
    </fieldset>
    <br/>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    </p>
</form>
