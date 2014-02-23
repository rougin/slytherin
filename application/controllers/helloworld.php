<?php

class Helloworld extends Controller
{

	public function index()
	{
		Load::view('welcome.php');
	}

	public function hello($name, $name2 = NULL)
	{
	    if ($name2 != NULL)
	    {
	        echo 'hello ', $name, ' and ', $name2, '!<br/>';
	    }
	    else
	    {
	        echo 'hello ', $name, '!<br/>';
	    }
	}

}