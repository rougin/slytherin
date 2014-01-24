<?php
class Form
{

	public function _input($name, $value = NULL, $attributes = NULL, $type)
	{
		$input = '<input type="' . $type . '" name="' . $name . '" ';
		if ($value != NULL) { $input .= 'value="' . $value . '"'; }
		if ($attributes != NULL) { $input .= $attributes; }
		return $input . "/>\n";
	}

	public function close($data = NULL)
	{
		return "</form>\n" . $data;
	}

	public function input($name, $value = NULL, $attributes = NULL)
	{
		return self::_input($name, $value, $attributes, 'text');
	}

	public function open($action = '', $attributes = NULL)
	{
		$form = '<form method="post" accept-charset="utf-8" action="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $action . '" ' . $attributes;
		return $form . "/>\n";
	}

	public function password($name, $value = NULL, $attributes = NULL)
	{
		return self::_input($name, $value, $attributes, 'password');
	}

	public function submit ($name = NULL, $value = NULL, $attributes = NULL)
	{
		return self::_input($name, $value, $attributes, 'submit');
	}

}