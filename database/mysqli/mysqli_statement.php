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
 * MySQL Improved Statement handler
 * @package Meridian
 * @subpackage Database
 */
class MySQLi_Statement
{
	public function __construct($result)
	{
		$this->result = $result;
	}
	
	public function fetch_array()
	{
		return mysqli_fetch_array($this->result);
	}
	public function fetchArray()
	{
		return $this->fetch_array();
	}
	
	public function fetch_assoc()
	{
		return mysqli_fetch_assoc($this->result);
	}
	public function fetchAssoc()
	{
		return $this->fetch_assoc();
	}
	
	public function fetch_all()
	{
		$rows = array();
		while($row = $this->fetch_assoc())
		{
			$rows[] = $row;
		}
		return $rows;
	}
	public function fetchAll()
	{
		return $this->fetch_all();
	}
	
	public function num_rows()
	{
		return mysqli_num_rows($this->result);
	}
	public function numRows()
	{
		return $this->num_rows();
	}
}