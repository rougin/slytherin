<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');
class Controller
{
	public function __construct()
	{
		require_once SYSTEM . 'load.php';
	}
}