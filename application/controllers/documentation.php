<?php if (!defined('SYSTEM')) exit('No direct script access allowed');
class Documentation extends Controller
{
	public function __construct()
	{
		$urls = array(
					'about' => '',
					'installation' => '',
					'mvc' => '',
					'quickstart' => ''
				);
		$urls[URL::segment(2)] = 'class="active"';
		Load::view('include/header', array('urls' => $urls));
	}

	public function index()
	{
		Load::view('documentation/welcome');
		Load::view('include/footer');
	}

	public function about()
	{
		Load::view('documentation/about');
		Load::view('include/footer');
	}

	public function installation()
	{
		Load::view('documentation/installation');
		Load::view('include/footer');
	}

	public function mvc()
	{
		Load::view('documentation/mvc');
		Load::view('include/footer');
	}

	public function quickstart()
	{
		Load::view('documentation/quickstart');
		Load::view('include/footer');
	}	
}