<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');
class Load
{
	public function file($name, $file)
	{
		require(APPLICATION . $name . '/' . strtolower($file) . '.php');
		if ($name != 'helpers')
		{
			return new $file();
		}
	}

	public function model($file)
	{
		$base_model = new Model();
		return self::file('models', $file);
	}

	public function library($file)
	{
		return self::file('libraries', $file);
	}

	public function helper($file)
	{
		return self::file('helpers', $file);
	}

    public function view($file, $data = NULL)
	{
	    if ($data != NULL)
	    {
	        extract($data);
	    }
		require_once APPLICATION . 'views/' . $file;
	}
}