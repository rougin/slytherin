<?php
class Template
{
	/* load static variables */
	protected static $file;
	protected static $values = array();

	static public function merge($templates, $separator = "\n")
	{
		$output = "";
		foreach ($templates as $template)
		{
			$content = (get_class($template) !== "Template")
			? "Error, incorrect type - expected Template."
			: $template->output();
			$output .= $content . $separator;
		}
		return $output;
	}

	public function load($path)
	{
		self::$file = BASEPATH . "/views/" . $path . ".php";
	}

	public function output()
	{
		if (class_exists(self::$file)) { echo "yeah"; }
		if (!file_exists(self::$file)) { return "Error loading template file (self::$file).<br />"; }
		$output = file_get_contents(self::$file);
		foreach (self::$values as $key => $value)
		{
			$tag_to_replace = "@$key";
			if (strpos($value, ".php") !== FALSE)
			{
				$value = file_get_contents(BASEPATH . "/views/" . $value);
				$value = eval("?>" . $value . "<?");
			}
			$output = str_replace($tag_to_replace, $value, $output);
		}
		return eval("?>" . $output . "<?");
	}

	public function set($key, $value)
	{
		self::$values[$key] = $value;
	}

}