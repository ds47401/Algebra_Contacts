<?php

class Config
{
	private function __construct(){}
	private function __clone(){}
	
	public static function get($file = null)
	{
		if(is_file($file)) {
			$items = require_once 'config/'.$file.'.php';
			return $items;
		}
		
		return false;
	}
}