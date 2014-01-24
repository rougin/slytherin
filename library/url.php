<?php
class URL
{
	public function base($url = NULL)
	{
		$URI = explode("/", $_SERVER['REQUEST_URI']);
		unset($URI[0]);
		return 'http://' . $_SERVER['HTTP_HOST'] . "/" . $URI[1] . "/" . $url;
	}
}