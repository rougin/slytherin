<?php
class Error
{
	public function no_controller($controller)
	{
		echo "<b>Error:</b> The controller '$controller' was not found in the requested url.";
		exit;
	}
	public function no_default_controller()
	{
		echo "<b>Error:</b> No default controller was specified in the configuration.";
		exit;
	}
	public function no_file_exists($filename)
	{
		echo "<b>Error:</b> '$filename' does not exists in the system.";
		exit;
	}
	public function no_method($controller, $method)
	{
		echo "<b>Error:</b> The method '$method' was not found in the '$controller' controller.";
		exit;
	}
	public function not_extended($name, $category)
	{
		echo "<b>Error:</b> The $category '$name' was not extended in the '" . ucfirst(str_replace('s', '', $category)) . "' class.";
		exit;
	}
	public function mysql_error($error)
	{
		echo "<b>MySQL Error:</b> " . $error;
		exit;
	}
	public function must_be_array()
	{
		echo "<b>Error:</b> The argument must be in array.";
		exit;
	}
}