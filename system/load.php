<?php
class Load
{

	public function assets($asset_type, $filename = NULL)
	{
		/* scan the directory for the asset files */
		$assets_files = scandir(BASEPATH . "/assets/" . $asset_type, 1);
		/* if the filename is an array or if the filename is NULL */
		if (is_array($filename) || $filename == NULL)
		{
			/* if the filename is NULL, scan the directory of the specified folder */
			if ($filename == NULL ) { $filename = scandir(BASEPATH . "/assets/" . $asset_type, 1); }
			/* loop the $filename array as $file */
			foreach ($filename as $file)
			{
				/* get the asset file of each file */
				$tags[] = self::get_asset($asset_type, $file);
			}
		}
		/* else if the $filename is a variable */
		else { $tags[] = self::get_asset($asset_type, $filename); }
		/* implode the tags and return the result */
		return implode($tags, NULL);
	}

	public function controllers()
	{
		/* load the controllers */
		return self::_load('application/controllers', 'Controller');
	}

	public function core($classes = NULL)
	{
		/* load each class */
		foreach ($classes as $class)
		{
			/* define the core classes' path */
			$classpath = BASEPATH . "/system/" . $class . ".php";
			/* if the core class exists */
			if (file_exists($classpath)) { require_once $classpath; }
			else { Error::no_file_exists($class); }
			/* load the __construct of the selected class */
			$class = new $class();
			// $class = new ReflectionClass($class);
			// $instance = $class->newInstanceArgs(array());
		}
	}

	public function get_asset($asset_type, $filename)
	{
		/* if the file doesnt exists in the assets folder */
		if (!file_exists(BASEPATH . "/assets/" . $asset_type . "/" . $filename)) { Error::no_file_exists($filename); }
		/* get the link of the asset file */
		$link = URL::base('assets/' . $asset_type . '/' . $filename);
		/* if the file contains a css file */
		if (strpos($filename, ".css") !== FALSE) { return "<link href=\"" . $link . "\" rel=\"stylesheet\">\n"; }
		/* else if the file contains a js file */
		else if (strpos($filename, ".js") !== FALSE ) { return "<script src=\"" . $link . "\"></script>\n"; }
	}

	public function library()
	{
		/* load the library */
		return self::_load('library');
	}

	public function models()
	{
		/* load the models */
		return self::_load('application/models', 'Model');
	}

	private function _load($location, $parent_class = NULL)
	{
		$classes = array('application/controllers' => 'controller', 'application/models' => 'model', 'library' => 'library');
		$files = scandir(BASEPATH . "/" . $location, 1);
		foreach ($files as $file)
		{
			if (preg_match("/.php/i", $file))
			{
				$filepath = BASEPATH . "/" . $location . "/" . $file;
				require_once $filepath;
				$class = str_replace(".php", "", $file);
				// echo $class . "<br/>";
				if (class_exists($class) && strpos($location, "library") === FALSE)
				{
					$class = new $class();
					if (!is_subclass_of($class, $parent_class))
					{
						echo $class;
						Error::not_extended($file, ucfirst($parent_class));
					}
				}
			}
		}
	}
}