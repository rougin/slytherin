<?php

namespace Slytherin;

class View
{

	public static function render($view, $data)
	{
		/**
		 * Assume the directory of the view file
		 */
		
		$viewFile = 'app/views/' . $view . '.php';

		if ( ! file_exists($viewFile)) {
			return self::error('The view file you specified cannot be found from the app/views/ directory');
		}

		/**
		 * Extract the specific parameters to the view
		 */

		extract($data);

		include $viewFile;
	}

	public static function error($message)
	{
		return trigger_error($message, E_USER_ERROR);
	}

}