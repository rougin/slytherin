<?php
class Input
{
	public function post($name = NULL)
	{
		/* if the selected $_POST value does not exists, set a NULL value */
		if (!isset($_POST[$name]) && $name != NULL) {
			return NULL;
		}
		/* else return the selected $_POST value */
		if ($name != NULL) {
			return $_POST[$name];
		}
		/* else return the whole $_POST value */
		else {
			return $_POST;
		}
	}

	public function get($name = NULL)
	{
		/* if the selected $_GET value does not exists, set a NULL value */
		if (!isset($_GET[$name]) && $name != NULL) {
			return NULL;
		}
		/* else return the selected $_GET value */
		if ($name != NULL) {
			return $_GET[$name];
		}
		/* else return the whole $_GET value */
		else {
			return $_GET;
		}
	}
}