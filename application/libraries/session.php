<?php
class Session
{
	public function __construct()
	{
		session_start();
	}
	public function add($name, $data)
	{
		return $_SESSION[$name] = $data;
	}
	public function data($name = NULL)
	{
		if ($name == NULL || $name == '') {
			return $_SESSION;
		}
		return $_SESSION[$name];
	}
	public function destroy()
	{
		return session_destroy();
	}
}