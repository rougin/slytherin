<?php

class Helloworld extends Controller
{

	public function hello($name, $name2 = NULL)
	{
		if ($name2 != NULL) {
			echo 'Hello ', ucfirst($name), ' and ', ucfirst($name2), '.<br/>';
		}
		else {
			echo 'Hello ', ucfirst($name), '.<br/>';
		}
	}

	public function index()
	{
		Load::view('welcome');
	}

}