<?php
class Template
{
	private $template;
	public function __construct($template = NULL)
	{
		if (isset($template))
		{
			$this->load($template);
		}
	}
	public function load($file)
	{
		if (isset($file) && file_exists($file))
		{
			$this->template = file_get_contents($file);
		}
	}
	public function set($var, $content)
	{
		$this->template = str_replace("{$var}", $content, $this->template);
	}
	public function publish()
	{
		// $this->remove_empty();
		eval("?>" . $this->template . "<?");
	}
	public function remove_empty()
	{
		$this->template = preg_replace('^{.*}^', "", $this->template);
	}
	public function parse()
	{
		return $this->template;
	}
}
class Load
{
	public function assets($asset_type, $filename = NULL)
	{
		$assets_files = scandir("/opt/lampp/htdocs/slytherin/assets/" . $asset_type, 1);
		if ($filename != NULL)
		{
			$link = 'http://localhost/slytherin/assets/' . $asset_type . '/' . $filename;
			if (strpos($filename, ".css") !== FALSE) { $tags[] = "<link href=\"" . $link . "\" rel=\"stylesheet\">\n\t\t"; }
			else if (strpos($filename, ".js") !== FALSE ) { $tags[] = "<script src=\"" . $link . "\"></script>\n\t\t"; }
		}
		else
		{
			foreach ($assets_files as $asset_file)
			{
				if ($asset_file != '..' && $asset_file != '.')
				{
					$link = 'http://localhost/slytherin/assets/' . $asset_type . '/' . $asset_file;
					if (strpos($asset_file, ".css") !== FALSE) { $tags[] = "<link href=\"" . $link . "\" rel=\"stylesheet\">\n\t\t"; }
					else if (strpos($asset_file, ".js") !== FALSE ) { $tags[] = "<script src=\"" . $link . "\"></script>\n\t\t"; }
				}
			}
		}
		return implode($tags, NULL);
	}
}

function get_page($file)
{
	ob_start();
	require_once "/opt/lampp/htdocs/slytherin/views/" . $file . ".php";
	return ob_get_clean();
}

extract(array('hehehehe' => 'jejeej'));
ob_start();
require_once "/opt/lampp/htdocs/slytherin/views/layout.php";
$contents = ob_get_contents();
if (strpos($contents, "@") !== FALSE)
{
	$output = str_replace("@header", get_page('include/header'), $contents);
	$output = str_replace("@content", /*get_page('acheche')*/'welcome to laravel', $output);
	$output = str_replace("@footer", get_page('include/footer'), $output);
}
ob_end_clean();
echo $output;