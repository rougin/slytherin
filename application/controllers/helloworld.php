<?php
class Helloworld extends Controller
{
	public $names = array('Frank', 'Todd', 'James', 'Rougin', 'Royce', 'Gutib');
	public function __construct()
	{
		// echo URL::base('assets/css/bootstrap.css') . "<br/>";
		// echo "hello world<br/>";
		// echo "<pre>";
		// print_r($this->names);
		// echo "</pre>";
	}
	public function index()
	{
		// echo "<pre>";
		// print_r($this->names);
		// echo "</pre>";
		// echo "<pre>";
		// print_r($this->names);
		// echo "</pre>";
		// $name = 'slytherin';
		if (Input::post())
		{
			// $this->names[] = Input::post('username');
			echo "<pre>";
			print_r(Input::post());
			echo "</pre>";
		}
		// $_SESSION['name'] = 'rougin';
		// Session::add('adsadassd', 'acheche');
		echo microtime();
		echo "<pre>";
		print_r(Session::data(''));
		echo "</pre>";
		// Session::destroy();
		// $data = array(
		// 	'first_name' => 'Acheche' ,
		// 	'last_name' => 'Aluloy' ,
		// 	'username' => 'acheche',
		// 	'password' => 'acheche',
		// 	'created_at' => '2014-01-03 15:26:57',
		// 	'updated_at' => '2014-01-03 15:26:57',
		// 	);
		// $data = array(
		// 	array(
		// 		'first_name' => 'Acheche' ,
		// 		'last_name' => 'Aluloy' ,
		// 		'username' => 'acheche',
		// 		'password' => 'acheche',
		// 		'created_at' => '2014-01-03 15:26:57',
		// 		'updated_at' => '2014-01-03 15:26:57',),
		// 	array(
		// 		'first_name' => 'Acheche' ,
		// 		'last_name' => 'Aluloy' ,
		// 		'username' => 'acheche',
		// 		'password' => 'acheche',
		// 		'created_at' => '2014-01-03 15:26:57',
		// 		'updated_at' => '2014-01-03 15:26:57',)
		// 	);
		// Database::insert('users', $data);
		// View::page('acheche', array('name' => $name));
		// $acheche = array('acheche' => array('name' => $name));
		// View::template('layout', array('header' => $acheche));
		// Database::select()->where('name', 'acheche')->or_where('name', 'aluloy')
		// ->where_in('name', array('Rougin', 'Royce', 'Gutib'))
		// ->or_where_in('name', array('Rougin', 'Royce', 'Gutib'))
		// ->or_where_in('name', array('Rougin', 'Royce', 'Gutib'))
		// ->where_not_in('name', array('Rougin', 'Royce', 'Gutib'))
		// ->or_where_not_in('name', array('Rougin', 'Royce', 'Gutib'));
		// Database::select('acheche')->get('accounts');
		// Database::select_max('acheche', 'erghevsaf')->get('accounts');
		// Database::select_min('acheche', 'fdgafdddg')->get('accounts');
		// Database::select_avg('acheche', 'fasdfadsf')->get('accounts');
		// Database::select_sum('acheche', 'jujujujuju');
		// Database::like('hehehe', 'no')->or_like('hehehe', 'no')->not_like('hehehe', 'no')->or_not_like('hehehe', 'no');
		// echo "<pre>";
		// print_r(Database::get('accounts')->into('object'));
		// echo "</pre>";
		// View::pages(array('include/header', 'acheche' => array('names' => $this->names), 'include/footer'));
		// $load = new Template('/opt/lampp/htdocs/slytherin/views/layout.php');
		// Template::load('layout');
		// Template::set('localhello', View::page('acheche'));
		// Template::set('world', 'hello po');
		// echo Template::output();
		// Helloworld::hello();
	}
	public function hello($name = 'ph0fxzs')
	{
		echo "hi " . $name . "!";
	}
}