<?php
class Router
{
	static $url;
	static $url_full;
	public function __construct()
	{
		/* retrieve the URI request from the server */
		$URI_request = explode('/', $_SERVER['REQUEST_URI']);
		/* retrieve the script name from the server */
		$script_name = explode('/', $_SERVER['SCRIPT_NAME']);
		/* transfer them in an array */
		self::$url = array_values(array_diff_assoc($URI_request, $script_name));
		/* transfer the controller name to a $GLOBALS */
		$GLOBALS['controller'] = self::$url[0];
		
		/* concatenate all uri segments into a full url */
		for ($counter = 0; $counter < count(self::$url); $counter++)
		{
			/* remove empty uri segments */
			if (self::$url[$counter] == '') { unset(self::$url[$counter]); }
			else { self::$url_full .= "/" . self::$url[$counter]; }
		}
		self::$url_full = substr(self::$url_full, 1);
		// echo "<pre>URLS: ";
		// print_r(self::$url);
		// echo "</pre>";
	}
	private function check_wildcard($key, $type)
	{
		/* initialize the set of wildcards */
		$wildcard = array('any' => '(:any)', 'num' => '(:num)');
		/* check if $key contains a (:any) string */
		if (strpos($key, $wildcard[$type]) !== FALSE)
		{
			$keys = explode('/', $key);
			/* check for (:any) in $key */
			for ($counter = 0; $counter < count($keys); $counter++)
			{
				/* if the $keys array contains a $wildcard[$type] */
				if ($keys[$counter] == $wildcard[$type]) { $key_counter = $counter; }
			}
			/* check a numeric value in the url */
			for ($counter = 0; $counter < count(self::$url); $counter++)
			{
				/* if the $key_counter matches the $counter and the url segment contains a numeric value */
				if ($type == 'any' && $key_counter == $counter && (is_numeric(self::$url[$counter]) || is_string(self::$url[$counter]))) { return 1; }
				else if ($key_counter == $counter && is_numeric(self::$url[$counter])) { return 1; }
			}
		}
		else { return 0; }
	}
	public function load()
	{
		/* if there is no controller called, load the default index page */
		if (!array_key_exists(0, self::$url))
		{
			if (isset($GLOBALS['route']['default'])) { self::$url[0] = $GLOBALS['route']['default']; }
			else { Error::no_default_controller(); }
		}
		
		/* if there is an URI present in the array */
		else if (self::$url[0] != NULL)
		{
			/* check routes in configuration.php */
			foreach ($GLOBALS['route'] as $key => $value)
			{
				$key_counter = 0;
				/* if the url matches to the routed key */
				if (self::$url_full == $key || self::check_wildcard($key, 'num') || self::check_wildcard($key, 'any'))
				{
					/* explode the $value into segments */
					$values = explode('/', $value);
					/* assign the values to the URL */
					self::$url = $values;
				}
			}
		}
		/* checks if the controller entered in the url exists */
		if (!file_exists(APPPATH . "controllers/" . self::$url[0] . ".php")) { Error::no_controller(self::$url[0]); }
		
		/* checks if there is no method entered in the url */
		if (!array_key_exists(1, self::$url) || self::$url[1] == '')
		{
			/* checks if the 'index' method exists inside the controller */
			if (method_exists(self::$url[0], "index")) { self::$url[1] = 'index'; }
			/* else print no index page found and exit */
			else { Error::no_method(self::$url[0], 'index'); }
		}
		
		/* checks if the method cannot found in the url exists in the controller */
		if (!method_exists(self::$url[0], self::$url[1])) { Error::no_method(self::$url[0], self::$url[1]); }
		
		/* get the parameters */
		$arguments = array();
		for ($counter = 2; $counter < count(self::$url); $counter++) { $arguments[] = self::$url[$counter]; }
		/* assign the method value to $method variable */
		$method = self::$url[1];
		/* load the controller */
		$controller = new self::$url[0]();
		/* insert the arguments to the method of the controller */
		call_user_method_array($method, $controller, $arguments);
	}
}