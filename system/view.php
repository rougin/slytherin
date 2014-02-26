<?php
class View
{

	public function get($file, $data = NULL)
	{
		if ($data != NULL)
		{
			extract($data);
		}
		require_once SYSTEM . $file . ".php";
	}
	
}