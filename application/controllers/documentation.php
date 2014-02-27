<?php if ( ! defined('SYSTEM')) exit('No direct script access allowed');
class Documentation extends Controller
{

	public function __construct()
	{
		Load::view('include/header');
	}

	public function index()
	{
		Load::view('documentation/welcome');
		Load::view('include/footer');
	}

}