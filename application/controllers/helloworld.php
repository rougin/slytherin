<?php

use Illuminate\Database;
class Helloworld extends Controller
{

	public function __construct()
	{
		echo 'this is __construct<br/>';
	}

	public function index()
	{
		echo 'hello illuminati<br/>';
		$acheche = Load::model('acheche');
		$acheche->add();
		echo '<pre>';
		print_r(get_declared_classes());
		echo '</pre>';
		$x = new Connection();
	}

	public function method($name, $name2 = NULL)
	{
		echo 'this is ', $name, ' ', $name2, '<br/>';
	}

}