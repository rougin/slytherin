<?php if (!defined('SYSTEM')) exit('No direct script access allowed');
class URL
{
	public function base($url = NULL)
	{
		return URL . $url;
	}

	public function segment($number)
	{
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$segments = explode('/', $path);
		if (!isset($segments[$number]) || $segments[$number] == NULL) {
			return NULL;
		}
		else {
			return $segments[$number];
		}
	}

	public function current($url = NULL)
	{
		return URL . substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);
	}
}