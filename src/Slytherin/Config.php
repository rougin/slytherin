<?php namespace Slytherin;

/**
 * Config Class
 */

class Config
{

	protected static $_config = array();

	/**
	 * Set the specified data to the $_config array with a keyword
	 * 
	 * @param string $name
	 * @param array  $data
	 */
	public static function set($name, $data)
	{
		self::$_config[$name] = $data;
	}

	/**
	 * Get the specified data from the $_config array
	 * 
	 * @param  string $name
	 * @param  string $index
	 * @return array
	 */
	public static function get($name, $index = NULL)
	{
		return ($index) ? self::$_config[$name][$index] : self::$_config[$name];
	}

	/**
	 * Load the specified configuration to the application
	 * 
	 * @param  string $config
	 * @return error | include
	 */
	public static function load($config)
	{
		$configFile = 'app/config/' . ucfirst($config) . '.php';

		if ( ! file_exists($configFile)) {
			return trigger_error('"' . ucfirst($config) . '.php" cannot be found from the "app/config/" directory', E_USER_ERROR);
		}

		return include $configFile;
	}

}