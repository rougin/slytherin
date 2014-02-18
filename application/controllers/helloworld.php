<?php
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
	}

	public function method($name, $name2 = NULL)
	{
		echo 'this is ', $name, ' ', $name2, '<br/>';
	}

}