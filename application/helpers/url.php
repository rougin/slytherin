<?php if (!defined('SYSTEM')) exit('No direct script access allowed');
class URL
{
	public function base($url = NULL)
	{
		return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url;
	}
}