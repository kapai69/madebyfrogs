<?php

/**
	Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
	Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
	Class TranslateController
	
	This controller allows users to generate a template for a translation file.
	
	Package frog
	Author Martijn van der Kleijn <martijn.niji@gmail.com>
	Version 0.1
	Since 0.9.4
*/

class TranslateController extends Controller
{
	public function __construct()
	{
		if ( ! AuthUser::isLoggedIn())
			redirect(get_url('login'));
		
		$this->assignToLayout('sidebar', new View('translate/sidebar'));
	}

	public function index()
	{
		$this->setLayout('backend');
		$this->display('translate/index');
	}

	public function core()
	{
		$complete = array();
		$basedir = FROG_ROOT.'';
		$dirs = $this->listdir($basedir);

		foreach ($dirs as $id => $path) {
			$tmp = array();
			$strings = array();
			$fsize = filesize($path);
			
			if ($fsize > 0) {
				$fh = fopen($path, 'r');
				$data = fread($fh, $fsize);
				fclose($fh);

				if (strpos($data, '__(\'')) {
					$data = substr($data, strpos($data, '__(\'')+4);
					$tmp = explode('__(\'', $data);

					foreach ($tmp as $string) {
						$endpos = strpos($string, '\'');
						while (substr($string, $endpos-1, 1) == "\\") {
							$endpos = $endpos + strpos(substr($string, $endpos+1, strpos($string, '\'')), '\'') + 1;
						}
						$strings[] = substr($string, 0, $endpos);
					}

					if (sizeof($strings) > 0)
						$complete = array_merge($complete, $strings);
				}
			}
		}

		// These are a few generated strings which the TranslateController cannot pick out.
		// So we add them manually for now.
		$complete = array_merge($complete, array(
			'Add Page', 'Edit Page', 'Add snippet',
			'Edit snippet', 'Add layout' ,'Edit layout',
			'Add user', 'Edit user'
		));

		$this->display('translate/core', array('complete' => $complete));
	}
	
	public function plugins()
	{
		$files = array();
		$basedir = FROG_ROOT.'/frog/plugins';
		$dirs = $this->listdir($basedir, true);

		foreach ($dirs as $id => $path) {
			$tmp = array();
			$strings = array();
			$fsize = filesize($path);
			
			if ($fsize > 0) {
				$fh = fopen($path, 'r');
				$data = fread($fh, $fsize);
				fclose($fh);

				if (strpos($data, '__(\'')) {
					$data = substr($data, strpos($data, '__(\'')+4);
					$tmp = explode('__(\'', $data);

					foreach ($tmp as $string) {
						$endpos = strpos($string, '\'');
						while (substr($string, $endpos-1, 1) == "\\") {
							$endpos = $endpos + strpos(substr($string, $endpos+1, strpos($string, '\'')), '\'') + 1;
						}
						$strings[] = substr($string, 0, $endpos);
					}

					if (sizeof($strings) > 0)
						$files[$path] = $strings;
				}
			}
		}

		$this->display('translate/plugins', array('files' => $files));
	}
	
	public function listdir($start_dir='.', $plugins = false) {
		$files = array();
		if (is_dir($start_dir)) {
			$fh = opendir($start_dir);
			while (($file = readdir($fh)) !== false) {
				# loop through the files, skipping . and .., and recursing if necessary
				if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
				$filepath = $start_dir . '/' . $file;
				if ($plugins) {
					if ( is_dir($filepath) && !strpos($filepath, 'i18n') )
						$files = array_merge($files, $this->listdir($filepath, $plugins));
					else {
						if (!strpos($filepath, 'I18n') && strpos($filepath, '.php', strlen($filepath) - 5) || strpos($filepath, '.phtml', strlen($filepath) - 7))
							array_push($files, $filepath);
					}
				} else {
					if ( is_dir($filepath) && !strpos($filepath, 'i18n') && !strpos($filepath, 'plugins') )
						$files = array_merge($files, $this->listdir($filepath, $plugins));
					else {
						if (!strpos($filepath, 'I18n') && strpos($filepath, '.php', strlen($filepath) - 5) || strpos($filepath, '.phtml', strlen($filepath) - 7))
							array_push($files, $filepath);
					}
				}
			}
			closedir($fh);
		} else {
			# false if the function was called with an invalid non-directory argument
			$files = false;
		}

		return $files;
	}

} // TranslateController
