<?php namespace Rougin\Slytherin;

/**
 * View Class
 *
 * @package Slytherin
 */
class View {

	/**
	 * Render the specified view file to the browser
	 * 
	 * @param  string  $view
	 * @param  array   $data
	 * @param  boolean $return
	 */
	public static function render($view, $data = NULL, $return = FALSE)
	{
		/**
		 * Assume the directory of the view file
		 */
		
		$file = 'app/views/' . preg_replace('{/$}', '', $view) . '.php';

		if ( ! file_exists($file)) {
			return trigger_error('The view file that you specified cannot be found from the <b>app/views</b> directory', E_USER_ERROR);
		}

		/**
		 * Extract the specific parameters to the view
		 */

		if ($data) {
			extract($data);
		}

		/**
		 * Buffer the file and get its output
		 * else include the file
		 */

		if ($return) {
			ob_start();
			
			echo eval('?>'.preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', file_get_contents($file))));

			$stringResult = ob_get_contents();
			
			@ob_end_clean();

			return $stringResult;
		} else {
			include $file;
		}
	}

}