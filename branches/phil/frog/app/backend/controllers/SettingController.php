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
	Class SettingsController

	Since  0.8.7
*/

class SettingController extends Controller
{
	public function __construct()
	{
		if ( ! AuthUser::isLoggedIn())
		{
			redirect(get_url('login'));
		}
		else if ( ! AuthUser::hasPermission('administrator'))
		{
			Flash::set('error', __('You do not have permission to access the requested page!'));
			redirect(get_url());
		}
		
		$this->setLayout('backend');
	}
	
	public function index()
	{
		// check if trying to save
		if (get_request_method() == 'POST')
			return $this->_save();
		
		$this->display('setting/index');
	}
	
	private function _save()
	{
		$data = $_POST['setting'];
		
		if (!isset($data['allow_html_title']))
			$data['allow_html_title'] = 'off';
		
		Setting::saveFromData($data);
		Flash::set('success', __('Settings has been saved!'));
		
		redirect(get_url('setting'));
	}
	
	public function activate_plugin($plugin)
	{
		if ( ! AuthUser::hasPermission('administrator'))
		{
			Flash::set('error', __('You do not have permission to access the requested page!'));
			redirect(get_url());
		}
		
		Plugin::activate($plugin);
	}
	
	public function deactivate_plugin($plugin)
	{
		if ( ! AuthUser::hasPermission('administrator'))
		{
			Flash::set('error', __('You do not have permission to access the requested page!'));
			redirect(get_url());
		}
		
		Plugin::deactivate($plugin);
	}

} // end SettingController class
