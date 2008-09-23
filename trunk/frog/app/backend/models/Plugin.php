<?php

/**
   Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
   Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as
   published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * class Plugin 
 *
 * Provide a Plugin API to make frog more flexible
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.9
 */

class Plugin
{
	static $plugins = array();
	static $plugins_infos = array();

	static $controllers = array();
    static $javascripts = array();

	/**
	 * Initialize all activated plugin by including is index.php file
	 */
	static function init()
	{
		self::$plugins = unserialize(Setting::get('plugins'));
		foreach (self::$plugins as $plugin_id => $tmp)
		{
			$file = CORE_ROOT.'/plugins/'.$plugin_id.'/index.php';
			if (file_exists($file))
				include $file;
			
			$file = CORE_ROOT.'/plugins/'.$plugin_id.'/i18n/'.I18n::getLocale().'-message.php';
			if (file_exists($file))
			{
				$array = include $file;
				I18n::add($array);
			}
		}
	}

	/**
	 * Set plugin informations (id, title, description, version and website)
	 *
	 * @param infos array Assoc array with plugin informations
	 */
	static function setInfos($infos)
	{
		self::$plugins_infos[$infos['id']] = (object) $infos;
	}

	/**
	 * Activate a plugin
	 * Notes: it will execute the install.php file of the plugin is founded
	 *
	 * @param plugin_id string	The plugin name to activate
	 */
	static function activate($plugin_id)
	{
		self::$plugins[$plugin_id] = 1;
		self::save();

		$file = CORE_ROOT.'/plugins/'.$plugin_id.'/enable.php';
		if (file_exists($file))
			include $file;
        
        $class_name = Inflector::camelize($plugin_id).'Controller';        
        AutoLoader::addFile($class_name, self::$controllers[$plugin_id]->file);
	}
	
	/**
	 * Deactivate a plugin
	 *
	 * @param plugin_id string	The plugin name to deactivate
	 */
	static function deactivate($plugin_id)
	{
		if (isset(self::$plugins[$plugin_id]))
		{
			unset(self::$plugins[$plugin_id]);
			self::save();

			$file = CORE_ROOT.'/plugins/'.$plugin_id.'/disable.php';
			if (file_exists($file))
				include $file;
		}
	}

	/**
	 * Save activated plugins to the setting 'plugins'
	 */
	static function save()
	{
		Setting::saveFromData(array('plugins' => serialize(self::$plugins)));
	}

	/**
	 * Find all plugins installed in the plugin folder
	 *
	 * @return array
	 */
	static function findAll()
	{
		$dir = CORE_ROOT.'/plugins/';

		if ($handle = opendir($dir))
		{
			while (false !== ($plugin_id = readdir($handle)))
			{
				if ( ! isset(self::$plugins[$plugin_id]) && is_dir($dir.$plugin_id) && strpos($plugin_id, '.') !== 0)
				{
					$file = CORE_ROOT.'/plugins/'.$plugin_id.'/index.php';
					if (file_exists($file))
						include $file;
				}
			}
			closedir($handle);
		}

		ksort(self::$plugins_infos);
		return self::$plugins_infos;
	}

    /**
     * Check the file mentioned as update_url for the latest plugin version available.
     *
     * @param plugin     object A plugin object.
     *
     * @return           string The latest version number or 'n/a' when latest version couldn't be determined.
     */
    static function checkLatest($plugin)
    {
        if ( ! isset($plugin->update_url) || ! $xml = simplexml_load_file($plugin->update_url)) {
            return 'unknown';
        }

        foreach($xml as $node) {
            if ($plugin->id == $node->id)
                if ($plugin->version == $node->version)
                    return 'latest';
                else
                    return (string) $node->version;
        }

        return 'error';
    }


	/**
	 * Add a controller (tab) to the administration
	 *
	 * @param plugin_id     string  The folder name of the plugin
	 * @param label         string  The tab label
	 * @param permissions   string  List of roles that will have the tab displayed
	 *                              separate by coma ie: 'administrator,developer'
	 *
	 * @return void
	 */
	static function addController($plugin_id, $label, $permissions=false)
	{
		$class_name = Inflector::camelize($plugin_id).'Controller';

		self::$controllers[$plugin_id] = (object) array(
			'label' => ucfirst($label),
			'class_name' => $class_name,
			'file'	=> CORE_ROOT.'/plugins/'.$plugin_id.'/'.$class_name.'.php',
			'permissions' => $permissions
		);
        
        AutoLoader::addFile($class_name, self::$controllers[$plugin_id]->file);
	}

    /**
     * Add a javascript file to be added to the html page for a plugin.
     * Backend only right now.
     *
     * @param $plugin_id    string  The folder name of the plugin
     * @param $file         string  The path to the javascript file relative to plugin root
     */
    static function addJavascript($plugin_id, $file)
    {
        if (file_exists(CORE_ROOT . '/plugins/' . $plugin_id . '/' . $file))
        {
            self::$javascripts[$plugin_id] = $file;
        }
    }

} // end Plugin class
