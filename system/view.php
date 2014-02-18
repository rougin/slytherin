<?php
class View
{

	public function __construct()
	{
		/* if the the header template is set in the configuration */
		if (isset($GLOBALS['template']['header']))
		{
			/* start the output buffering */
			ob_start();
			/* get the header file */
			$header_file = APPPATH . "views/" . $GLOBALS['template']['header'] . ".php";
			/* if the header file exists */
			if (file_exists($header_file))
			{
				/* load the header file */
				require_once $header_file;
				/* stop the buffering and load the output */
				echo ob_get_clean();
			}
		}
	}

	public function page($page = NULL, $arguments = NULL)
	{
		/* if arguments is not NULL, extract all arguments to the html */
		if ($arguments != NULL) { extract($arguments); }
		/* if the files doesn't exists, return an error */
		if (!file_exists(APPPATH . "views/" . $page . ".php")) { Error::no_file_exists($page); }
		/* else load the specified file */
		else { require_once APPPATH . "views/" . $page . ".php"; }
		self::_load_footer();
	}

	public function pages($pages)
	{
		/* breaks pages into seperate page */
		while ($page = current($pages))
		{
			/* if the page contains arguments, load the page */
			if (is_array($page)) { self::page(key($pages), $page); }
			/* else if the file doesn't exists in the directory */
			else if (!file_exists(APPPATH . "views/" . $page . ".php")) { Error::no_file_exists($page); }
			/* else, load the .php file */
			else { require_once APPPATH . "views/" . $page . ".php"; }
			/* next page */
			next($pages);
		}
		self::_load_footer();
	}

	public function _load_footer()
	{
		/* if the the footer template is set in the configuration */
		if (isset($GLOBALS['template']['footer']))
		{
			/* start the output buffering */
			ob_start();
			/* get the footer file */
			$footer_file = APPPATH . "views/" . $GLOBALS['template']['footer'] . ".php";
			/* if the footer file exists */
			if (file_exists($footer_file))
			{
				/* load the footer file */
				require_once $footer_file;
				/* stop the buffering and load the output */
				echo ob_get_clean();
			}
		}
		/* else return NULL */
		else { return NULL; }
	}

}