<?php
/**
 * Meridian
 * Copyright (C) 2010-2011 Jack Polgar
 * 
 * This file is part of Meridian.
 * 
 * Meridian is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 only.
 * 
 * Meridian is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Meridian. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Paramater cleaning/safening class for _POST, _GET, _COOKIE and _REQUEST
 * @package Meridian
 */
class Param
{
	public static $post = array();
	public static $get = array();
	public static $request = array();
	public static $cookie = array();
	
	/**
	 * Initialize the class and clean and/or safen _POST, _GET, _COOKIE and _REQUEST
	 */
	public static function init()
	{
		// Strip magic quotes from request data.
		if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
			$quotes_sybase = strtolower(ini_get('magic_quotes_sybase'));
			$unescape_function = (empty($quotes_sybase) || $quotes_sybase === 'off') ? 'stripslashes($value)' : 'str_replace("\'\'","\'",$value)';
			$stripslashes_deep = create_function('&$value, $fn', '
				if (is_string($value)) {
					$value = ' . $unescape_function . ';
				} else if (is_array($value)) {
					foreach ($value as &$v) $fn($v, $fn);
				}
			');
		   
			// Unescape data
			$stripslashes_deep($_POST, $stripslashes_deep);
			$stripslashes_deep($_GET, $stripslashes_deep);
			$stripslashes_deep($_COOKIE, $stripslashes_deep);
			$stripslashes_deep($_REQUEST, $stripslashes_deep);
		}
		
		// Safen data from < and >
		self::$post = self::clean($_POST);
		self::$get = self::clean($_GET);
		self::$cookie = self::clean($_COOKIE);
		self::$request = self::clean($_REQUEST);
	}
	
	/**
	 * Safens the specified value, disabling HTML.
	 * @param mixed $val The value to clean.
	 * @return string
	 */
	public static function clean($val)
	{
		if(is_array($val))
		{
			foreach($val as $i => $v)
			{
				$val[$i] = self::clean($v);
			}
		}
		else
		{
			$val = str_replace(array('<','>'), array('&lt;', '&gt;'), $val);
		}
		
		return $val;
	}
}