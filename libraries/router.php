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
 * URL Router
 * @package Meridian
 */
class Router
{
	public static $routes;
	public static $namespace;
	public static $controller;
	public static $method;
	
	public static function route()
	{
		$request = (Request::$extension !== null ? str_replace('.'.Request::$extension, '', Request::$request) : Request::$request);
		
		// Check if we only have one route
		if(count(self::$routes) == 1)
		{
			self::_set_request($request);
			return;
		}
		
		// Loop through the route array looking for wild-cards
		foreach(self::$routes as $key => $val)
		{
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			
			// Do we have a back-reference?
			if(strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				$val = preg_replace('#^'.$key.'$#', $val, $request);
			
			// Check if theres a RegEx match
			if(preg_match('#^'.$key.'$#', $request))
			{
				self::_set_request($val);
				return;
			}
		}

		// No matches found, give it the current request
		self::_set_request(Request::$request);
	}
	
	// Private function used to set the request controller and method.
	private static function _set_request($request)
	{
		$segs = explode('/', str_replace('::', '/', $request));
		
		if($segs[0] == '')
			$segs = explode('/', str_replace('::', '/', self::$routes['/']));
		
		if(count($segs) == 3)
		{
			self::$namespace = $segs[0];
			self::$controller = $segs[1];
			self::$method = $segs[2];
		}
		else
		{
			self::$namespace = null;
			self::$controller = $segs[0];
			self::$method = (isset($segs[1]) ? $segs[1] : 'index');
		}
		
		unset($segs);
	}
}