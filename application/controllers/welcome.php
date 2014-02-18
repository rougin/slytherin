<?php
class Welcome extends Controller
{
	public function index()
	{
		if (Input::post())
		{
			$user = Database::get_where('librarians', array('librarians_id' => Input::post('id')))->into('object_row');
			echo "<pre>";
			print_r($user);
			echo "</pre>";
		}
		View::page('login_form');
	}

	public function swag()
	{
		echo "yolo";
	}
}