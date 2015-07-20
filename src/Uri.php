<?php namespace Rougin\Slytherin;

/**
 * Uri Class
 *
 * @package Slytherin
 */
class Uri
{
	/**
	 * Render the specified view file to the browser
	 * 
	 * @param  string  $index
	 * @param  array   $segment
	 */
	public static function get($index = 0)
	{
		$segments = explode('/', self::parseRequest());
		unset($segments[0]);

		if ($index == 0 || ! is_numeric($index)) {
			return $segments;
		}

		return $segments[$index];
	}

	/**
	 * Parse the $_SERVER['REQUEST_URI']
	 * @return [type] [description]
	 */
	protected static function parseRequest()
	{
		if ( ! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
			return '';
		}

		$uri   = parse_url($_SERVER['REQUEST_URI']);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri   = isset($uri['path']) ? $uri['path'] : '';

		$_SERVER['QUERY_STRING'] = $query;

		if (isset($_SERVER['SCRIPT_NAME'][0])) {
			if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
				$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
			} else if (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
				$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
		}

		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0) {
			$query = explode('?', $query, 2);
			$uri = $query[0];

			$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
		}

		parse_str($_SERVER['QUERY_STRING'], $_GET);

		if ($uri === '/' OR $uri === '') {
			return '/';
		}

		return $uri;
	}
}