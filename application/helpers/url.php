<?php if (!defined('SYSTEM')) exit('No direct script access allowed');
class URL
{
	public function base($url = NULL)
	{
		return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url;
	}

	public function segment($number)
	{
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$segments = explode('/', $path);
		return $segments[$number];
	}
}