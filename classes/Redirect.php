<?php

class Redirect
{
	private function __construct(){}
	private function __clone(){}
	
	public static function to($location=null)
	{
		if($location){
			if(is_numeric($location)){
				switch($location){
					case 404:
						header('HTTP/1.0 404 Not Found');
						include 'includes/errors/404.php';
						exit();
						break;
				}
			}
			header('Location: '.$location.'.php');
			exit();
		}
	}
}